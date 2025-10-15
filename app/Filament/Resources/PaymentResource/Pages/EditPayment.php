<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentLog;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            // Actions\Action::make('recalculate_amounts')
            //     ->label('Recalculate Amounts')
            //     ->icon('heroicon-o-calculator')
            //     ->color('warning')
            //     ->action(function () {
            //         $this->recalculateAmounts();
            //     })
            //     ->requiresConfirmation()
            //     ->modalHeading('Recalculate Payment Amounts')
            //     ->modalDescription('This will recalculate gross_amount and final_amount based on amount, fee, discount, and voucher_discount.')
            //     ->modalSubmitActionLabel('Recalculate'),
            
            Actions\Action::make('sync_with_gateway')
                ->label('Sync with Gateway')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->action(function () {
                    // Placeholder for gateway sync logic
                    $this->record->createLog(
                        PaymentLog::ACTION_NOTIFICATION_RECEIVED,
                        'Manual sync with payment gateway attempted'
                    );
                    $this->notify('info', 'Gateway sync feature will be available soon');
                })
                ->visible(fn (): bool => $this->record->status === 'pending'),
            
            Actions\Action::make('add_manual_log')
                ->label('Add Manual Log')
                ->icon('heroicon-o-plus-circle')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\Select::make('action')
                        ->label('Action')
                        ->options(PaymentLog::getActions())
                        ->required(),
                    
                    \Filament\Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->required()
                        ->placeholder('Describe what happened...')
                        ->rows(3),
                    
                    \Filament\Forms\Components\KeyValue::make('data')
                        ->label('Additional Data (Optional)')
                        ->reorderable(false),
                ])
                ->action(function (array $data) {
                    $this->record->createLog(
                        $data['action'],
                        $data['description'],
                        array_merge($data['data'] ?? [], [
                            'manual_entry' => true,
                            'created_by' => auth()->user()?->name ?? 'System'
                        ])
                    );
                    $this->notify('success', 'Manual log entry added');
                }),
            
            Actions\Action::make('change_status')
                ->label('Change Status')
                ->icon('heroicon-o-flag')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\Select::make('new_status')
                        ->label('New Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'expired' => 'Expired',
                            'cancelled' => 'Cancelled',
                            'refunded' => 'Refunded',
                        ])
                        ->required()
                        ->default($this->record->status),
                    
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('Change Reason')
                        ->required()
                        ->placeholder('Explain why you are changing the status...')
                        ->rows(3),
                    
                    \Filament\Forms\Components\Toggle::make('update_website_status')
                        ->label('Update Website Status')
                        ->default(true)
                        ->visible(fn (\Filament\Forms\Get $get) => $get('new_status') === 'paid'),
                ])
                ->action(function (array $data) {
                    $oldStatus = $this->record->status;
                    
                    $updateData = ['status' => $data['new_status']];
                    
                    // Set paid_at if marking as paid
                    if ($data['new_status'] === 'paid' && $oldStatus !== 'paid') {
                        $updateData['paid_at'] = now();
                    }
                    
                    $this->record->update($updateData);
                    
                    // Update website content if requested
                    if ($data['new_status'] === 'paid' && 
                        isset($data['update_website_status']) && 
                        $data['update_website_status'] && 
                        $this->record->websiteContent) {
                        
                        $this->record->websiteContent->update([
                            'status' => 'active',
                            'activated_at' => now(),
                        ]);
                    }
                    
                    // Log the status change
                    $this->record->createLog(
                        $data['new_status'],
                        "Status changed from {$oldStatus} to {$data['new_status']}: " . $data['reason'],
                        [
                            'old_status' => $oldStatus,
                            'new_status' => $data['new_status'],
                            'changed_by' => auth()->user()?->name ?? 'System',
                        ]
                    );
                    
                    $this->notify('success', "Payment status changed to {$data['new_status']}");
                }),

        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Payment Updated')
            ->body('The payment has been updated successfully.')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure payment code uniqueness if changed
        if (isset($data['code']) && $data['code'] !== $this->record->getOriginal('code')) {
            if (Payment::where('code', $data['code'])->where('id', '!=', $this->record->id)->exists()) {
                Notification::make()
                    ->danger()
                    ->title('Duplicate Payment Code')
                    ->body('This payment code is already in use.')
                    ->persistent()
                    ->send();
                
                $this->halt();
            }
        }
        
        // Recalculate amounts if base values changed
        $amountFields = ['amount', 'fee', 'discount', 'voucher_discount'];
        $hasAmountChanges = false;
        
        foreach ($amountFields as $field) {
            if (isset($data[$field]) && $data[$field] != $this->record->getOriginal($field)) {
                $hasAmountChanges = true;
                break;
            }
        }
        
        if ($hasAmountChanges) {
            $amount = $data['amount'] ?? $this->record->amount;
            $fee = $data['fee'] ?? $this->record->fee;
            $discount = $data['discount'] ?? $this->record->discount;
            $voucherDiscount = $data['voucher_discount'] ?? $this->record->voucher_discount;
            
            $grossAmount = $amount + $fee - $discount - $voucherDiscount;
            $data['gross_amount'] = max(0, $grossAmount);
            $data['final_amount'] = $data['gross_amount'];
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Log the update
        $changes = $this->record->getChanges();
        if (!empty($changes)) {
            $this->record->createLog(
                'updated',
                'Payment details updated by admin',
                [
                    'changes' => $changes,
                    'updated_by' => auth()->user()?->name ?? 'System',
                ]
            );
        }
    }

    private function recalculateAmounts(): void
    {
        $amount = $this->record->amount ?? 0;
        $fee = $this->record->fee ?? 0;
        $discount = $this->record->discount ?? 0;
        $voucherDiscount = $this->record->voucher_discount ?? 0;
        
        $grossAmount = $amount + $fee - $discount - $voucherDiscount;
        $finalAmount = max(0, $grossAmount);
        
        $this->record->update([
            'gross_amount' => $grossAmount,
            'final_amount' => $finalAmount,
        ]);
        
        $this->record->createLog(
            'updated',
            'Amounts recalculated automatically',
            [
                'old_gross_amount' => $this->record->getOriginal('gross_amount'),
                'new_gross_amount' => $grossAmount,
                'old_final_amount' => $this->record->getOriginal('final_amount'),
                'new_final_amount' => $finalAmount,
            ]
        );
        
        $this->notify('success', 'Payment amounts recalculated successfully');
    }

    private function duplicatePayment(): void
    {
        $duplicate = $this->record->replicate();
        $duplicate->code = Payment::generateCode();
        $duplicate->status = 'pending';
        $duplicate->paid_at = null;
        //$duplicate->transaction_time = null;
        $duplicate->midtrans_data = null;
        $duplicate->save();
        
        $duplicate->createLog(
            PaymentLog::ACTION_CREATED,
            "Payment duplicated from {$this->record->code}",
            [
                'original_payment_id' => $this->record->id,
                'duplicated_by' => auth()->user()?->name ?? 'System',
            ]
        );
        
        $this->notify('success', 'Payment duplicated successfully');
        $this->redirect(PaymentResource::getUrl('edit', ['record' => $duplicate]));
    }

    public function getTitle(): string
    {
        return "Edit Payment: {$this->record->code}";
    }

    public function getSubheading(): ?string
    {
        return "Customer: {$this->record->user->name} • Status: {$this->record->status}";
    }
}
