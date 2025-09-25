<?php

namespace App\Filament\Resources\WebsiteContentResource\Pages;

use App\Filament\Resources\WebsiteContentResource;
use App\Models\WebsiteContent;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\IconEntry;

class ViewWebsiteContent extends ViewRecord
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
            
            Actions\Action::make('preview')
                ->label('Preview Website')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (): string => $this->record->getPublicUrl())
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->record->canPreview()),
            
            Actions\Action::make('activate')
                ->label('Activate Website')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update([
                        'status' => 'active',
                        'activated_at' => now()
                    ]);
                    $this->notify('success', 'Website has been activated');
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'paid'),
            
            Actions\Action::make('suspend')
                ->label('Suspend Website')
                ->icon('heroicon-o-pause-circle')
                ->color('warning')
                ->action(function () {
                    $this->record->update(['status' => 'suspended']);
                    $this->notify('warning', 'Website has been suspended');
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'active'),
            
            Actions\Action::make('reactivate')
                ->label('Reactivate Website')
                ->icon('heroicon-o-play-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'active']);
                    $this->notify('success', 'Website has been reactivated');
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'suspended'),
            
            Actions\Action::make('extend_expiry')
                ->label('Extend Expiry')
                ->icon('heroicon-o-calendar-plus')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\DateTimePicker::make('new_expiry')
                        ->label('New Expiry Date')
                        ->required()
                        ->default($this->record->expires_at?->addMonths(6) ?? now()->addYear())
                        ->after('today'),
                ])
                ->action(function (array $data) {
                    $this->record->update(['expires_at' => $data['new_expiry']]);
                    $this->notify('success', 'Website expiry date has been extended');
                }),
            
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Website Content')
                ->modalDescription('Are you sure you want to delete this website? This action cannot be undone and will remove all associated data.')
                ->before(function () {
                    // Check if there are active payments
                    $activePayments = $this->record->payments()->whereIn('status', ['pending', 'paid'])->count();
                    if ($activePayments > 0) {
                        $this->notify('danger', 'Cannot delete website with active payments');
                        $this->halt();
                    }
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Website Overview')
                    ->description('Basic website information and ownership')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Website Owner')
                            ->icon('heroicon-o-user')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary')
                            ->url(fn ($record) => route('filament.admin.resources.users.view', $record->user))
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('template.name')
                            ->label('Template Used')
                            ->icon('heroicon-o-document-duplicate')
                            ->badge()
                            ->color('info')
                            ->url(fn ($record) => route('filament.admin.resources.templates.view', $record->template))
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('status')
                            ->label('Current Status')
                            ->badge()
                            ->color(fn (string $state): string => match($state) {
                                'draft' => 'gray',
                                'previewed' => 'warning',
                                'paid' => 'info',
                                'active' => 'success',
                                'suspended' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match($state) {
                                'draft' => 'heroicon-o-pencil',
                                'previewed' => 'heroicon-o-eye',
                                'paid' => 'heroicon-o-credit-card',
                                'active' => 'heroicon-o-check-circle',
                                'suspended' => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        
                        IconEntry::make('is_expired')
                            ->label('Expired Status')
                            ->getStateUsing(fn ($record): bool => $record->isExpired())
                            ->boolean()
                            ->trueIcon('heroicon-o-exclamation-triangle')
                            ->falseIcon('heroicon-o-check-circle')
                            ->trueColor('danger')
                            ->falseColor('success'),
                    ]),

                Section::make('Domain Configuration')
                    ->description('Website URLs and domain settings')
                    ->icon('heroicon-o-globe-alt')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('subdomain')
                            ->label('Subdomain')
                            ->icon('heroicon-o-link')
                            ->copyable()
                            ->copyMessage('Subdomain copied!')
                            ->placeholder('Not set')
                            ->formatStateUsing(fn (?string $state): string => 
                                $state ? $state . '.' . config('app.domain') : 'Not set'
                            ),
                        
                        TextEntry::make('custom_domain')
                            ->label('Custom Domain')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->copyable()
                            ->copyMessage('Domain copied!')
                            ->placeholder('Not set')
                            ->url(fn (?string $state): ?string => $state ? "https://{$state}" : null)
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('public_url')
                            ->label('Public URL')
                            ->icon('heroicon-o-link')
                            ->getStateUsing(fn ($record): string => $record->getPublicUrl())
                            ->copyable()
                            ->copyMessage('URL copied!')
                            ->url(fn ($record): string => $record->getPublicUrl())
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
                    ]),

                Section::make('Timeline & Dates')
                    ->description('Important dates and timeline information')
                    ->icon('heroicon-o-calendar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->icon('heroicon-o-plus-circle')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->color('info'),
                        
                        TextEntry::make('activated_at')
                            ->label('Activated At')
                            ->icon('heroicon-o-check-circle')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder('Not activated yet')
                            ->color('success'),
                        
                        TextEntry::make('expires_at')
                            ->label('Expires At')
                            ->icon('heroicon-o-calendar-x')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->color(fn ($record): string => $record->isExpired() ? 'danger' : 'warning')
                            ->placeholder('No expiry set'),
                        
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->icon('heroicon-o-clock')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->color('gray'),
                    ]),

                Section::make('Payment Information')
                    ->description('Payment history and revenue data')
                    ->icon('heroicon-o-currency-dollar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('payments_count')
                            ->label('Total Payments')
                            ->icon('heroicon-o-credit-card')
                            ->getStateUsing(fn ($record): int => $record->payments()->count())
                            ->badge()
                            ->color('primary'),
                        
                        TextEntry::make('successful_payments')
                            ->label('Successful Payments')
                            ->icon('heroicon-o-check-circle')
                            ->getStateUsing(fn ($record): int => $record->payments()->where('status', 'paid')->count())
                            ->badge()
                            ->color('success'),
                        
                        TextEntry::make('total_revenue')
                            ->label('Total Revenue')
                            ->icon('heroicon-o-banknotes')
                            ->getStateUsing(function ($record): string {
                                $revenue = $record->payments()->where('status', 'paid')->sum('final_amount');
                                return 'Rp ' . number_format($revenue, 0, ',', '.');
                            })
                            ->badge()
                            ->color('success'),
                    ]),

                Section::make('Website Content Data')
                    ->description('Dynamic content stored for this website')
                    ->icon('heroicon-o-code-bracket')
                    ->collapsed()
                    ->schema([
                        KeyValueEntry::make('content_data')
                            ->label('Content Fields')
                            ->columnSpanFull()
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->placeholder('No content data stored'),
                    ]),

                Section::make('Template Information')
                    ->description('Details about the template used')
                    ->icon('heroicon-o-document-duplicate')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('template.category')
                            ->label('Template Category')
                            ->icon('heroicon-o-tag')
                            ->badge()
                            ->color('blue')
                            ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'Unknown'),
                        
                        TextEntry::make('template.price')
                            ->label('Template Price')
                            ->icon('heroicon-o-currency-dollar')
                            ->money('IDR')
                            ->badge()
                            ->color(fn ($record): string => 
                                $record->template && $record->template->price > 0 ? 'success' : 'warning'
                            ),
                        
                        TextEntry::make('template_config')
                            ->label('Template Configuration')
                            ->icon('heroicon-o-cog')
                            ->getStateUsing(function ($record): string {
                                if (!$record->template) return 'Template not found';
                                
                                $config = $record->template->getConfigData();
                                $fieldsCount = isset($config['fields']) ? count($config['fields']) : 0;
                                return "{$fieldsCount} configurable fields";
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('System Information')
                    ->description('Technical information and identifiers')
                    ->icon('heroicon-o-server')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('id')
                            ->label('Website ID')
                            ->icon('heroicon-o-hashtag')
                            ->copyable(),
                        
                        TextEntry::make('user_id')
                            ->label('User ID')
                            ->icon('heroicon-o-user')
                            ->copyable(),
                        
                        TextEntry::make('template_slug')
                            ->label('Template Slug')
                            ->icon('heroicon-o-link')
                            ->copyable(),
                        
                        TextEntry::make('can_preview')
                            ->label('Can Preview')
                            ->getStateUsing(fn ($record): string => $record->canPreview() ? 'Yes' : 'No')
                            ->badge()
                            ->color(fn ($record): string => $record->canPreview() ? 'success' : 'danger'),
                    ]),
            ]);
    }

    public function getTitle(): string
    {
        return "Website: {$this->record->user->name}";
    }

    public function getSubheading(): ?string
    {
        return "Template: {$this->record->template->name} • Status: {$this->record->status}";
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record->load([
                'user', 
                'template', 
                'payments' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]),
        ];
    }
}
