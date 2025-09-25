<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentLog;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Payment')
                ->icon('heroicon-o-plus-circle')
                ->color('success'),
            
            Actions\Action::make('refresh_pending')
                ->label('Refresh Pending')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    $pendingCount = Payment::where('status', 'pending')
                        ->where('expired_at', '<', now())
                        ->count();
                    
                    Payment::where('status', 'pending')
                        ->where('expired_at', '<', now())
                        ->update(['status' => 'expired']);
                    
                    Notification::make()
                        ->success()
                        ->title('Payments Updated')
                        ->body("Updated {$pendingCount} expired pending payments")
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Refresh Pending Payments')
                ->modalDescription('This will mark expired pending payments as expired.')
                ->modalSubmitActionLabel('Refresh'),
            
            Actions\Action::make('export_payments')
                ->label('Export Payments')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
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
            'all' => Tab::make('All Payments')
                ->icon('heroicon-o-credit-card')
                ->badge(Payment::count()),
            
            'pending' => Tab::make('Pending')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(Payment::where('status', 'pending')->count())
                ->badgeColor('warning'),
            
            'paid' => Tab::make('Paid')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid'))
                ->badge(Payment::where('status', 'paid')->count())
                ->badgeColor('success'),
            
            'expired' => Tab::make('Expired')
                ->icon('heroicon-o-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'expired'))
                ->badge(Payment::where('status', 'expired')->count())
                ->badgeColor('danger'),
            
            'today' => Tab::make('Today')
                ->icon('heroicon-o-calendar-days')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', today()))
                ->badge(Payment::whereDate('created_at', today())->count())
                ->badgeColor('blue'),

        ];
    }

    public function getTitle(): string
    {
        return 'Payment Transactions';
    }

    public function getSubheading(): ?string
    {
        return 'Manage payment transactions and process refunds';
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
