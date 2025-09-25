<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Template')
                ->icon('heroicon-o-plus-circle')
                ->color('success'),
            
            Actions\Action::make('sync_templates')
                ->label('Sync Templates')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->action(function () {
                    // Logic to sync templates from filesystem
                    $this->syncTemplatesFromFilesystem();
                    $this->notify('success', 'Templates synchronized successfully');
                })
                ->requiresConfirmation()
                ->modalHeading('Sync Templates from Filesystem')
                ->modalDescription('This will scan the templates directory and update the database with any new templates found.')
                ->modalSubmitActionLabel('Sync Templates'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Templates')
                ->icon('heroicon-o-document-duplicate')
                ->badge(Template::count()),
            
            'active' => Tab::make('Active')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->active())
                ->badge(Template::active()->count())
                ->badgeColor('success'),
            
            'inactive' => Tab::make('Inactive')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge(Template::where('is_active', false)->count())
                ->badgeColor('danger'),
            
            'business' => Tab::make('Business')
                ->icon('heroicon-o-building-office')
                ->modifyQueryUsing(fn (Builder $query) => $query->byCategory('business'))
                ->badge(Template::byCategory('business')->count())
                ->badgeColor('blue'),
            
            'portfolio' => Tab::make('Portfolio')
                ->icon('heroicon-o-briefcase')
                ->modifyQueryUsing(fn (Builder $query) => $query->byCategory('portfolio'))
                ->badge(Template::byCategory('portfolio')->count())
                ->badgeColor('purple'),
            
            'ecommerce' => Tab::make('E-commerce')
                ->icon('heroicon-o-shopping-bag')
                ->modifyQueryUsing(fn (Builder $query) => $query->byCategory('ecommerce'))
                ->badge(Template::byCategory('ecommerce')->count())
                ->badgeColor('green'),
            
            'free' => Tab::make('Free')
                ->icon('heroicon-o-gift')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('price', 0))
                ->badge(Template::where('price', 0)->count())
                ->badgeColor('yellow'),
            
            'premium' => Tab::make('Premium')
                ->icon('heroicon-o-star')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('price', '>', 0))
                ->badge(Template::where('price', '>', 0)->count())
                ->badgeColor('amber'),
        ];
    }

    public function getTitle(): string
    {
        return 'Website Templates';
    }

    public function getSubheading(): ?string
    {
        return 'Manage website templates and their configurations';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TemplateResource\Widgets\TemplateStatsWidget::class,
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [12, 24, 48, 96];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'sort_order';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    private function syncTemplatesFromFilesystem(): void
    {
        $templatesPath = resource_path('views/templates');
        
        if (!is_dir($templatesPath)) {
            return;
        }

        $directories = array_filter(glob($templatesPath . '/*'), 'is_dir');
        
        foreach ($directories as $directory) {
            $slug = basename($directory);
            $configPath = $directory . '/config.json';
            
            if (!file_exists($configPath)) {
                continue;
            }
            
            $config = json_decode(file_get_contents($configPath), true);
            
            Template::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $config['name'] ?? ucfirst(str_replace(['-', '_'], ' ', $slug)),
                    'description' => $config['description'] ?? null,
                    'category' => $config['category'] ?? 'business',
                    'price' => $config['price'] ?? 0,
                    'sort_order' => $config['sort_order'] ?? 0,
                    'is_active' => $config['is_active'] ?? false,
                ]
            );
        }
    }
}
