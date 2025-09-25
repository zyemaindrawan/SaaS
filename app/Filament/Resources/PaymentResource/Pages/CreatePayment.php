<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Payments')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(PaymentResource::getUrl('index')),
        ];
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        // Hide "Create & create another" button as requested
        return parent::getCreateAnotherFormAction()->hidden();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Payment Created')
            ->body('The payment record has been created successfully. Payment logs will track all activities.')
            ->duration(6000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate payment code if not provided
        if (empty($data['code'])) {
            $data['code'] = Payment::generateCode();
        }
        
        // Set default status if not provided
        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }
        
        // Set default expiry if not provided (2 hours from now)
        if (empty($data['expired_at'])) {
            $data['expired_at'] = now()->addHours(2);
        }
        
        // Calculate final_amount if not provided
        if (empty($data['final_amount'])) {
            $amount = $data['amount'] ?? 0;
            $fee = $data['fee'] ?? 0;
            $discount = $data['discount'] ?? 0;
            $voucherDiscount = $data['voucher_discount'] ?? 0;
            
            $grossAmount = $amount + $fee - $discount - $voucherDiscount;
            $data['gross_amount'] = max(0, $grossAmount);
            $data['final_amount'] = $data['gross_amount'];
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $payment = $this->record;
        
        // Create initial payment log
        $payment->createLog(
            PaymentLog::ACTION_CREATED,
            'Payment record created manually by admin',
            [
                'created_by' => auth()->user()?->name ?? 'System',
                'initial_status' => $payment->status,
                'final_amount' => $payment->final_amount,
            ]
        );
        
        // Log additional info if voucher was used
        if ($payment->voucher_code) {
            $payment->createLog(
                PaymentLog::ACTION_CREATED,
                "Voucher applied: {$payment->voucher_code}",
                [
                    'voucher_code' => $payment->voucher_code,
                    'voucher_discount' => $payment->voucher_discount,
                ]
            );
        }
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Validate that website content exists and belongs to user
        if (!empty($data['website_content_id']) && !empty($data['user_id'])) {
            $websiteContent = \App\Models\WebsiteContent::find($data['website_content_id']);
            if ($websiteContent && $websiteContent->user_id !== $data['user_id']) {
                $this->notify('danger', 'Selected website content does not belong to the selected user');
                $this->halt();
            }
        }
        
        return parent::handleRecordCreation($data);
    }

    public function getTitle(): string
    {
        return 'Create New Payment';
    }

    public function getSubheading(): ?string
    {
        return 'Create a new payment record for manual processing';
    }
}
