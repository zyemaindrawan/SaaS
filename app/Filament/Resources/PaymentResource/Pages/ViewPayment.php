<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Services\PaymentService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Illuminate\Support\Facades\Auth;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
            
            Actions\Action::make('generate_snap_token')
                ->label('Generate Snap Token')
                ->icon('heroicon-o-key')
                ->color('info')
                ->action(function () {
                    try {
                        $paymentService = app(PaymentService::class);
                        $snapToken = $paymentService->getSnapToken($this->record);
                        
                        $this->notify('success', 'Snap token generated successfully');
                    } catch (\Exception $e) {
                        $this->notify('danger', 'Failed to generate snap token: ' . $e->getMessage());
                    }
                })
                ->visible(fn (): bool => $this->record->status === 'pending'),
            
            Actions\Action::make('mark_as_paid')
                ->label('Mark as Paid')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    
                    // Update website content status
                    if ($this->record->websiteContent) {
                        $this->record->websiteContent->update([
                            'status' => 'active',
                            'activated_at' => now(),
                        ]);
                    }
                    
                    $this->record->createLog(PaymentLog::ACTION_PAID, 'Payment marked as paid manually by admin');
                    $this->notify('success', 'Payment marked as paid successfully');
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => in_array($this->record->status, ['pending', 'failed'])),
            
            Actions\Action::make('process_refund')
                ->label('Process Refund')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\Textarea::make('refund_reason')
                        ->label('Refund Reason')
                        ->required()
                        ->placeholder('Explain the reason for refund...')
                        ->rows(3),
                    
                    \Filament\Forms\Components\TextInput::make('refund_amount')
                        ->label('Refund Amount')
                        ->numeric()
                        ->prefix('Rp')
                        ->default($this->record->final_amount)
                        ->helperText('Leave empty for full refund'),
                ])
                ->action(function (array $data) {
                    $refundAmount = $data['refund_amount'] ?? $this->record->final_amount;
                    
                    $this->record->update(['status' => 'refunded']);
                    $this->record->createLog(
                        PaymentLog::ACTION_REFUNDED, 
                        'Refund processed: ' . $data['refund_reason'],
                        [
                            'refund_amount' => $refundAmount,
                            'original_amount' => $this->record->final_amount,
                            'processed_by' => Auth::check() ? Auth::user()->name : 'System',
                        ]
                    );
                    
                    $this->notify('success', 'Refund processed successfully');
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'paid'),
            
            Actions\Action::make('view_invoice')
                ->label('View Invoice')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(fn (): string => route('invoice.show', $this->record->code))
                ->openUrlInNewTab(),

        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Payment Overview')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Payment Code')
                            ->icon('heroicon-o-hashtag')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Payment code copied!'),
                        
                        TextEntry::make('status')
                            ->label('Current Status')
                            ->badge()
                            ->color(fn (string $state): string => match($state) {
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed', 'expired', 'cancelled' => 'danger',
                                'refunded' => 'orange',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match($state) {
                                'pending' => 'heroicon-o-clock',
                                'paid' => 'heroicon-o-check-circle',
                                'failed', 'expired', 'cancelled' => 'heroicon-o-x-circle',
                                'refunded' => 'heroicon-o-arrow-uturn-left',
                                default => 'heroicon-o-question-mark-circle',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        
                        TextEntry::make('user.name')
                            ->label('Customer')
                            ->icon('heroicon-o-user')
                            ->color('primary')
                            ->action(
                                InfolistAction::make('viewUser')
                                    ->modalHeading(fn ($record) => "User Details: {$record->user->name}")
                                    ->modalContent(fn ($record) => view('filament.infolists.user-details', ['user' => $record->user]))
                                    ->modalWidth('xl')
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Close')
                            ),
                        
                        TextEntry::make('websiteContent.template.name')
                            ->label('Template')
                            ->icon('heroicon-o-document-duplicate')
                            ->url(fn ($record) => $record->websiteContent ? 
                                route('filament.admin.resources.website-contents.view', $record->websiteContent) : null)
                            ->openUrlInNewTab()
                            ->placeholder('No website content')
                            ->color('info'),
                    ]),

                Section::make('Amount Breakdown')
                    ->icon('heroicon-o-calculator')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('amount')
                            ->label('Base Amount')
                            ->icon('heroicon-o-banknotes')
                            ->money('IDR')
                            ->color('blue'),
                        
                        TextEntry::make('fee')
                            ->label('Platform Fee')
                            ->icon('heroicon-o-receipt-percent')
                            ->money('IDR')
                            ->color('orange'),
                        
                        TextEntry::make('discount')
                            ->label('Discount')
                            ->icon('heroicon-o-tag')
                            ->money('IDR')
                            ->color('green'),
                        
                        TextEntry::make('voucher_code')
                            ->label('Voucher Code')
                            ->icon('heroicon-o-ticket')
                            ->placeholder('No voucher used')
                            ->badge()
                            ->color('purple'),
                        
                        TextEntry::make('voucher_discount')
                            ->label('Voucher Discount')
                            ->icon('heroicon-o-gift')
                            ->money('IDR')
                            ->color('green'),
                        
                        TextEntry::make('final_amount')
                            ->label('Final Amount')
                            ->icon('heroicon-o-currency-dollar')
                            ->money('IDR')
                            ->size('lg')
                            ->weight('bold')
                            ->color('success'),
                    ]),

                // Section::make('Timeline & Dates')
                //     ->icon('heroicon-o-calendar')
                //     ->columns(2)
                //     ->schema([
                //         TextEntry::make('created_at')
                //             ->label('Created At')
                //             ->icon('heroicon-o-plus-circle')
                //             ->dateTime('M j, Y \a\t g:i A')
                //             ->color('info'),
                        
                //         TextEntry::make('paid_at')
                //             ->label('Paid At')
                //             ->icon('heroicon-o-check-circle')
                //             ->dateTime('M j, Y \a\t g:i A')
                //             ->placeholder('Not paid yet')
                //             ->color('success'),
                        
                //         TextEntry::make('expired_at')
                //             ->label('Expires At')
                //             ->icon('heroicon-o-calendar-days')
                //             ->dateTime('M j, Y \a\t g:i A')
                //             ->color(fn ($record): string => 
                //                 $record->expired_at && $record->expired_at->isPast() ? 'danger' : 'warning'
                //             ),
                        
                //         // TextEntry::make('transaction_time')
                //         //     ->label('Transaction Time')
                //         //     ->icon('heroicon-o-clock')
                //         //     ->dateTime('M j, Y \a\t g:i A')
                //         //     ->placeholder('No transaction time')
                //         //     ->color('gray'),
                //     ]),

                // Section::make('Payment Methods & Gateway')
                //     ->icon('heroicon-o-credit-card')
                //     ->columns(3)
                //     ->schema([
                //         TextEntry::make('payment_type')
                //             ->label('Payment Method')
                //             ->icon('heroicon-o-credit-card')
                //             ->placeholder('Not specified')
                //             ->badge()
                //             ->color('blue'),
                        
                //         TextEntry::make('gross_amount')
                //             ->label('Gross Amount')
                //             ->icon('heroicon-o-calculator')
                //             ->money('IDR'),
                        
                //         IconEntry::make('has_midtrans_data')
                //             ->label('Gateway Data Available')
                //             ->getStateUsing(fn ($record): bool => !empty($record->midtrans_data))
                //             ->boolean()
                //             ->trueIcon('heroicon-o-check-circle')
                //             ->falseIcon('heroicon-o-x-circle')
                //             ->trueColor('success')
                //             ->falseColor('danger'),

                //     ]),

                Section::make('Payment Gateway Response')
                    ->icon('heroicon-o-server')
                    ->collapsed()
                    ->schema([
                        KeyValueEntry::make('midtrans_data')
                            ->columnSpanFull()
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->placeholder('No gateway data available'),
                    ]),

                Section::make('Payment Activity Logs')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('payment_logs_timeline')
                            ->label('Activity Timeline')
                            ->getStateUsing(function ($record): string {
                                $logs = $record->logs()->orderBy('created_at', 'desc')->limit(20)->get();
                                
                                if ($logs->isEmpty()) {
                                    return 'No activity logs found';
                                }
                                
                                return $logs->map(function ($log) {
                                    $timeAgo = $log->created_at->diffForHumans();
                                    $action = $log->formatted_action;
                                    $description = $log->description ? " - {$log->description}" : '';
                                    
                                    return "• **{$action}** ({$timeAgo}){$description}";
                                })->join("\n");
                            })
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                // Section::make('Related Information')
                //     ->icon('heroicon-o-link')
                //     ->collapsed()
                //     ->columns(2)
                //     ->schema([
                //         TextEntry::make('user.email')
                //             ->label('Customer Email')
                //             ->icon('heroicon-o-envelope')
                //             ->copyable(),
                        
                //         TextEntry::make('user.phone')
                //             ->label('Customer Phone')
                //             ->icon('heroicon-o-phone')
                //             ->copyable()
                //             ->placeholder('No phone number'),
                        
                //         TextEntry::make('websiteContent.subdomain')
                //             ->label('Website Subdomain')
                //             ->icon('heroicon-o-link')
                //             ->formatStateUsing(fn (?string $state): string => 
                //                 $state ? $state . '.' . config('app.domain') : 'Not set'
                //             )
                //             ->url(fn ($record) => $record->websiteContent?->getPublicUrl())
                //             ->openUrlInNewTab(),
                        
                //         TextEntry::make('websiteContent.status')
                //             ->label('Website Status')
                //             ->icon('heroicon-o-globe-alt')
                //             ->badge()
                //             ->color(fn (?string $state): string => match($state) {
                //                 'active' => 'success',
                //                 'suspended' => 'danger',
                //                 'paid' => 'info',
                //                 default => 'gray',
                //             }),
                //     ]),

            ]);
    }

    public function getTitle(): string
    {
        return "Payment: {$this->record->code}";
    }

    public function getSubheading(): ?string
    {
        $amount = 'Rp ' . number_format($this->record->final_amount, 0, ',', '.');
        return "Customer: {$this->record->user->name} • Amount: {$amount} • Status: {$this->record->status}";
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record->load([
                'user',
                'websiteContent.template',
                'logs' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]),
        ];
    }
}
