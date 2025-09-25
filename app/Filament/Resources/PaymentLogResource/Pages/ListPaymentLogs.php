<?php

namespace App\Filament\Resources\PaymentLogResource\Pages;

use App\Filament\Resources\PaymentLogResource;
use App\Models\PaymentLog;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ListPaymentLogs extends ListRecords
{
    protected static string $resource = PaymentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Logs')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->dispatch('$refresh')),
            
            Actions\Action::make('export')
                ->label('Export Logs')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    Notification::make()
                        ->info()
                        ->title('Export Feature')
                        ->body('Available soon')
                        ->send();
                }),

        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Logs')
                ->icon('heroicon-o-queue-list')
                ->badge(PaymentLog::count()),
            
            'recent' => Tab::make('Recent (7 days)')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->recent(7))
                ->badge(PaymentLog::recent(7)->count()),
            
            'success' => Tab::make('Success')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('action', [
                    PaymentLog::ACTION_SUCCESS,
                    PaymentLog::ACTION_PAID,
                    PaymentLog::ACTION_ACTIVATION_COMPLETED
                ]))
                ->badge(PaymentLog::whereIn('action', [
                    PaymentLog::ACTION_SUCCESS,
                    PaymentLog::ACTION_PAID,
                    PaymentLog::ACTION_ACTIVATION_COMPLETED
                ])->count())
                ->badgeColor('success'),
            
            'failed' => Tab::make('Failed')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('action', [
                    PaymentLog::ACTION_FAILED,
                    PaymentLog::ACTION_CANCELLED,
                    PaymentLog::ACTION_EXPIRED,
                    PaymentLog::ACTION_SNAP_TOKEN_ERROR
                ]))
                ->badge(PaymentLog::whereIn('action', [
                    PaymentLog::ACTION_FAILED,
                    PaymentLog::ACTION_CANCELLED,
                    PaymentLog::ACTION_EXPIRED,
                    PaymentLog::ACTION_SNAP_TOKEN_ERROR
                ])->count())
                ->badgeColor('danger'),
            
            'processing' => Tab::make('Processing')
                ->icon('heroicon-o-cog')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('action', [
                    PaymentLog::ACTION_PROCESSING,
                    PaymentLog::ACTION_ACTIVATION_QUEUED,
                    PaymentLog::ACTION_SNAP_TOKEN_GENERATED
                ]))
                ->badge(PaymentLog::whereIn('action', [
                    PaymentLog::ACTION_PROCESSING,
                    PaymentLog::ACTION_ACTIVATION_QUEUED,
                    PaymentLog::ACTION_SNAP_TOKEN_GENERATED
                ])->count())
                ->badgeColor('warning'),
        ];
    }

    public function getTitle(): string
    {
        return 'Payment Activity Logs';
    }

    public function getSubheading(): ?string
    {
        return 'Track all payment-related activities and transactions in real-time';
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
