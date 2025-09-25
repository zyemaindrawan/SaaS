<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Models\Template;
use App\Models\WebsiteContent;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;

class ViewTemplate extends ViewRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
            
            Actions\Action::make('preview')
                ->label('Preview Template')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (): string => route('templates.show', $this->record->slug))
                ->openUrlInNewTab(),
            
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Delete Template')
                ->modalDescription('Are you sure you want to delete this template? This action cannot be undone and will affect all websites using this template.')
                ->action(function () {
                    $websiteCount = $this->record->websiteContents()->count();
                    if ($websiteCount > 0) {
                        $this->notify('danger', "Cannot delete template. {$websiteCount} websites are using this template.");
                        return;
                    }
                    
                    $this->record->delete();
                    $this->redirect(TemplateResource::getUrl('index'));
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Template Overview')
                    ->description('Basic template information and status')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('preview_image')
                            ->label('Preview Image')
                            ->disk('public')
                            ->height(200)
                            ->width(300)
                            ->defaultImageUrl(fn ($record) => $record->getPreviewUrl())
                            ->columnSpanFull(),
                        
                        TextEntry::make('name')
                            ->label('Template Name')
                            ->icon('heroicon-o-identification')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary'),
                        
                        TextEntry::make('slug')
                            ->label('Template Slug')
                            ->icon('heroicon-o-link')
                            ->copyable()
                            ->copyMessage('Slug copied!')
                            ->badge()
                            ->color('gray'),
                        
                        TextEntry::make('category')
                            ->label('Category')
                            ->icon('heroicon-o-tag')
                            ->badge()
                            ->color(fn (string $state): string => match($state) {
                                'business' => 'blue',
                                'portfolio' => 'purple',
                                'blog' => 'green',
                                'ecommerce' => 'orange',
                                'landing' => 'pink',
                                'corporate' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        
                        TextEntry::make('price')
                            ->label('Price')
                            ->icon('heroicon-o-currency-dollar')
                            ->money('IDR')
                            ->badge()
                            ->color(fn ($record): string => $record->price > 0 ? 'success' : 'warning')
                            ->formatStateUsing(fn ($record): string => $record->price > 0 ? 'Rp ' . number_format($record->price, 0, ',', '.') : 'Free'),
                        
                        TextEntry::make('directory_path')
                            ->label('Directory Path')
                            ->icon('heroicon-o-folder')
                            ->getStateUsing(fn ($record): string => "resources/views/templates/{$record->slug}")
                            ->copyable()
                            ->copyMessage('Path copied!'),
                        
                        TextEntry::make('description')
                            ->label('Description')
                            ->icon('heroicon-o-document-text')
                            ->prose()
                            ->markdown()
                            ->placeholder('No description provided'),
                    ]),

                Section::make('Usage Statistics')
                    ->description('Template usage and performance metrics')
                    ->icon('heroicon-o-chart-bar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('websites_count')
                            ->label('Total Websites')
                            ->icon('heroicon-o-globe-alt')
                            ->getStateUsing(fn ($record): int => $record->websiteContents()->count())
                            ->badge()
                            ->color('primary'),
                        
                        TextEntry::make('active_websites_count')
                            ->label('Active Websites')
                            ->icon('heroicon-o-check-circle')
                            ->getStateUsing(fn ($record): int => $record->websiteContents()->where('status', 'active')->count())
                            ->badge()
                            ->color('success'),
                        
                        TextEntry::make('revenue')
                            ->label('Total Revenue')
                            ->icon('heroicon-o-banknotes')
                            ->getStateUsing(function ($record): string {
                                $totalRevenue = $record->websiteContents()
                                    ->whereHas('payments', function ($query) {
                                        $query->where('status', 'paid');
                                    })
                                    ->count() * $record->price;
                                return 'Rp ' . number_format($totalRevenue, 0, ',', '.');
                            })
                            ->badge()
                            ->color('success'),
                    ]),

                Section::make('Template Configuration')
                    ->description('Template configuration and field definitions')
                    ->icon('heroicon-o-cog')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('config_status')
                            ->label('Configuration File')
                            ->icon('heroicon-o-document')
                            ->getStateUsing(function ($record): string {
                                $configPath = resource_path("views/templates/{$record->slug}/config.json");
                                return file_exists($configPath) ? 'Found' : 'Missing';
                            })
                            ->badge()
                            ->color(function ($record): string {
                                $configPath = resource_path("views/templates/{$record->slug}/config.json");
                                return file_exists($configPath) ? 'success' : 'danger';
                            }),
                        
                        TextEntry::make('config_version')
                            ->label('Template Version')
                            ->icon('heroicon-o-tag')
                            ->getStateUsing(function ($record): string {
                                $config = $record->getConfigData();
                                return $config['version'] ?? 'Not specified';
                            })
                            ->badge()
                            ->color('info'),
                        
                        TextEntry::make('field_count')
                            ->label('Configurable Fields')
                            ->icon('heroicon-o-list-bullet')
                            ->getStateUsing(function ($record): string {
                                $config = $record->getConfigData();
                                if (!isset($config['fields']) || !is_array($config['fields'])) {
                                    return '0 fields';
                                }
                                
                                $count = count($config['fields']);
                                return "{$count} field" . ($count !== 1 ? 's' : '');
                            })
                            ->badge()
                            ->color('primary'),
                        
                        TextEntry::make('config_author')
                            ->label('Template Author')
                            ->icon('heroicon-o-user')
                            ->getStateUsing(function ($record): string {
                                $config = $record->getConfigData();
                                return $config['author'] ?? 'Not specified';
                            }),
                        
                        TextEntry::make('config_fields_detail')
                            ->label('Field Configuration')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->getStateUsing(function ($record): string {
                                $config = $record->getConfigData();
                                
                                if (!isset($config['fields']) || !is_array($config['fields'])) {
                                    return 'No fields configured';
                                }
                                
                                return collect($config['fields'])
                                    ->map(function ($field) {
                                        $name = $field['label'] ?? $field['key'] ?? 'Unknown';
                                        $type = ucfirst($field['type'] ?? 'text');
                                        $required = isset($field['required']) && $field['required'] ? ' ⚠️' : '';
                                        
                                        return "• **{$name}** ({$type}){$required}";
                                    })
                                    ->implode("\n");
                            })
                            ->markdown()
                            ->columnSpanFull(),
                        
                        TextEntry::make('config_technical')
                            ->label('Technical Details')
                            ->icon('heroicon-o-code-bracket')
                            ->getStateUsing(function ($record): string {
                                $configPath = resource_path("views/templates/{$record->slug}/config.json");
                                
                                if (!file_exists($configPath)) {
                                    return 'Configuration file not found';
                                }
                                
                                $fileSize = filesize($configPath);
                                $lastModified = date('M j, Y H:i', filemtime($configPath));
                                
                                return "**File Size:** {$fileSize} bytes\n**Last Modified:** {$lastModified}";
                            })
                            ->markdown()
                            ->columnSpanFull(),
                    ]),


                Section::make('File Information')
                    ->description('Template files and directory structure')
                    ->icon('heroicon-o-folder')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('template_files')
                            ->label('Template Files')
                            ->getStateUsing(function ($record): string {
                                $templatePath = resource_path("views/templates/{$record->slug}");
                                
                                if (!is_dir($templatePath)) {
                                    return 'Template directory not found';
                                }
                                
                                $files = collect(\File::files($templatePath))
                                    ->map(fn ($file) => $file->getFilename())
                                    ->sort()
                                    ->values()
                                    ->implode(', ');
                                
                                return $files ?: 'No files found';
                            })
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Section::make('Recent Activity')
                    ->description('Latest websites created with this template')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('recent_websites')
                            ->label('Recent Websites')
                            ->getStateUsing(function ($record): string {
                                $recentWebsites = $record->websiteContents()
                                    ->with('user')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                                
                                if ($recentWebsites->isEmpty()) {
                                    return 'No websites created yet';
                                }
                                
                                return $recentWebsites->map(function ($website) {
                                    $timeAgo = $website->created_at->diffForHumans();
                                    return "• **{$website->user->name}** - {$timeAgo} ({$website->status})";
                                })->implode("\n");
                            })
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

            ]);
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    public function getSubheading(): ?string
    {
        return "Template Slug: {$this->record->slug} • Category: {$this->record->category}";
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record->load(['websiteContents.user', 'websiteContents.payments']),
        ];
    }
}
