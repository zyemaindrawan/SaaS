<?php

namespace App\Filament\Resources\WebsiteContentResource\Pages;

use App\Filament\Resources\WebsiteContentResource;
use App\Models\WebsiteContent;
use App\Models\Template;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ListWebsiteContents extends ListRecords
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Website')
                ->icon('heroicon-o-plus-circle')
                ->color('success'),

            Actions\Action::make('export')
                ->label('Export Data')
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
            'all' => Tab::make('All Websites')
                ->icon('heroicon-o-globe-alt')
                ->badge(WebsiteContent::count()),
            
            'draft' => Tab::make('Draft')
                ->icon('heroicon-o-pencil')
                ->modifyQueryUsing(fn (Builder $query) => $query->byStatus('draft'))
                ->badge(WebsiteContent::byStatus('draft')->count())
                ->badgeColor('gray'),
            
            'active' => Tab::make('Active')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->active())
                ->badge(WebsiteContent::active()->count())
                ->badgeColor('success'),
            
            'suspended' => Tab::make('Suspended')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->byStatus('suspended'))
                ->badge(WebsiteContent::byStatus('suspended')->count())
                ->badgeColor('danger'),
            
            'expired' => Tab::make('Expired')
                ->icon('heroicon-o-exclamation-triangle') // Fixed: Changed from 'heroicon-o-clock-x' to valid icon
                ->modifyQueryUsing(fn (Builder $query) => $query->where('expires_at', '<', now()))
                ->badge(WebsiteContent::where('expires_at', '<', now())->count())
                ->badgeColor('warning'),

        ];
    }

    public function getTitle(): string
    {
        return 'Website Contents';
    }

    public function getSubheading(): ?string
    {
        return 'Manage user-generated websites and their content';
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
