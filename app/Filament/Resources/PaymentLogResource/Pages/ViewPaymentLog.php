<?php

namespace App\Filament\Resources\PaymentLogResource\Pages;

use App\Filament\Resources\PaymentLogResource;
use App\Models\PaymentLog;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Colors\Color;

class ViewPaymentLog extends ViewRecord
{
    protected static string $resource = PaymentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_payment')
                ->label('View Payment')
                ->icon('heroicon-o-credit-card')
                ->color('info')
                ->url(fn (): string => route('filament.admin.resources.payments.view', ['record' => $this->record->payment_id]))
                ->visible(fn (): bool => $this->record->payment !== null)
                ->openUrlInNewTab(),
            
            Actions\Action::make('view_related_logs')
                ->label('View Related Logs')
                ->icon('heroicon-o-queue-list')
                ->color('gray')
                ->url(fn (): string => PaymentLogResource::getUrl('index', panel: 'admin') . '?tableFilters[payment_id][value]=' . $this->record->payment_id)
                ->visible(fn (): bool => $this->record->payment !== null),
            
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->dispatch('$refresh')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Log Information')
                    ->description('Basic log entry details')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('payment.code')
                            ->label('Payment Code')
                            ->icon('heroicon-o-credit-card')
                            ->color('primary')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage('Payment code copied!')
                            ->placeholder('No payment code'),
                        
                        TextEntry::make('action')
                            ->label('Action')
                            ->badge()
                            ->color(fn ($record): string => match($record->action) {
                                PaymentLog::ACTION_CREATED, PaymentLog::ACTION_INITIATED => 'info',
                                PaymentLog::ACTION_PROCESSING => 'warning',
                                PaymentLog::ACTION_SUCCESS, PaymentLog::ACTION_PAID, PaymentLog::ACTION_ACTIVATION_COMPLETED => 'success',
                                PaymentLog::ACTION_FAILED, PaymentLog::ACTION_CANCELLED, PaymentLog::ACTION_EXPIRED, PaymentLog::ACTION_SNAP_TOKEN_ERROR => 'danger',
                                PaymentLog::ACTION_REFUNDED => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($record): string => $record->formatted_action),
                        
                        TextEntry::make('description')
                            ->label('Description')
                            ->icon('heroicon-o-document-text')
                            ->columnSpanFull()
                            ->placeholder('No description provided')
                            ->prose()
                            ->markdown(),
                        
                        TextEntry::make('created_at')
                            ->label('Timestamp')
                            ->icon('heroicon-o-calendar')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->tooltip(fn ($record): string => $record->time_ago),
                        
                        TextEntry::make('time_ago')
                            ->label('Time Ago')
                            ->icon('heroicon-o-clock')
                            ->formatStateUsing(fn ($record): string => $record->time_ago)
                            ->color('gray'),
                    ]),

                Section::make('Payment Details')
                    ->description('Associated payment information')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('payment.user.name')
                            ->label('Customer')
                            ->icon('heroicon-o-user')
                            ->placeholder('No customer found'),
                        
                        TextEntry::make('payment.final_amount')
                            ->label('Payment Amount')
                            ->icon('heroicon-o-currency-dollar')
                            ->money('IDR')
                            ->placeholder('Amount not available'),
                        
                        TextEntry::make('payment.status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn ($record): string => match($record->payment?->status) {
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed', 'expired', 'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state): string => ucfirst($state ?? 'Unknown'))
                            ->placeholder('Status unknown'),
                        
                        TextEntry::make('payment.created_at')
                            ->label('Payment Created')
                            ->icon('heroicon-o-calendar-days')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder('Date not available'),
                    ])
                    ->visible(fn ($record): bool => $record->payment !== null),

                Section::make('Log Data')
                    ->description('Additional data stored with this log entry')
                    ->icon('heroicon-o-code-bracket')
                    ->collapsed()
                    ->schema([
                        KeyValueEntry::make('data')
                            ->label('Log Data')
                            ->columnSpanFull()
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->placeholder('No additional data stored'),
                    ])
                    ->visible(fn ($record): bool => $record->hasData()),

                Section::make('Related Logs Timeline')
                    ->description('Other logs for the same payment')
                    ->icon('heroicon-o-queue-list')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('related_logs_summary')
                            ->label('Related Activity')
                            ->formatStateUsing(function ($record): string {
                                if (!$record->payment) {
                                    return 'No related logs available (payment not found)';
                                }
                                
                                $relatedLogs = PaymentLog::where('payment_id', $record->payment_id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();
                                
                                if ($relatedLogs->isEmpty()) {
                                    return 'No related logs found';
                                }
                                
                                $timeline = $relatedLogs->map(function ($log) use ($record) {
                                    $current = $log->id === $record->id ? ' **[CURRENT]**' : '';
                                    $timeAgo = $log->created_at->diffForHumans();
                                    
                                    return "• **{$log->formatted_action}** - {$timeAgo}{$current}";
                                })->join("\n");
                                
                                return $timeline;
                            })
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record): bool => $record->payment !== null),

                Section::make('System Information')
                    ->description('Technical information about this log entry')
                    ->icon('heroicon-o-server')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('id')
                            ->label('Log ID')
                            ->icon('heroicon-o-hashtag')
                            ->copyable(),
                        
                        TextEntry::make('payment_id')
                            ->label('Payment ID')
                            ->icon('heroicon-o-link')
                            ->copyable(),
                        
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->icon('heroicon-o-clock')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder('Never updated'),
                        
                        IconEntry::make('has_data')
                            ->label('Has Additional Data')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->getStateUsing(fn ($record): bool => $record->hasData()),
                    ]),
            ]);
    }

    public function getTitle(): string
    {
        return "Payment Log #{$this->record->id}";
    }

    public function getSubheading(): ?string
    {
        return "Action: {$this->record->formatted_action} • {$this->record->time_ago}";
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record->load(['payment.user', 'payment.websiteContent']),
        ];
    }
}
