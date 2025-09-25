<?php

namespace App\Filament\Resources\TemplateResource\Widgets;

use App\Models\Template;
use App\Models\WebsiteContent;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TemplateStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTemplates = Template::count();
        $activeTemplates = Template::active()->count();
        $totalWebsites = WebsiteContent::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('final_amount');

        return [
            Stat::make('Total Templates', number_format($totalTemplates))
                ->description('All templates in system')
                ->descriptionIcon('heroicon-o-document-duplicate')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Active Templates', number_format($activeTemplates))
                ->description('Currently available templates')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([15, 4, 10, 2, 12, 4, 12]),
            
            Stat::make('Total Websites', number_format($totalWebsites))
                ->description('Websites created')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('info')
                ->chart([3, 8, 5, 10, 6, 14, 7]),
            
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('From paid templates')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([2, 6, 8, 12, 15, 18, 20]),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
