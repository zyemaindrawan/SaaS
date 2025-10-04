<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteContentResource\Pages;
use App\Models\WebsiteContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class WebsiteContentResource extends Resource
{
    protected static ?string $model = WebsiteContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Website Contents';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Website Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),
                        
                        Forms\Components\Select::make('template_slug')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-document-duplicate'),
                    ]),

                Section::make('Domain Configuration')
                    ->icon('heroicon-o-globe-alt')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('subdomain')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        
                        Forms\Components\TextInput::make('custom_domain')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-globe-europe-africa'),
                    ]),

                Section::make('Status & Timeline')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'previewed' => 'Previewed',
                                'paid' => 'Paid',
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-flag'),
                        
                        Forms\Components\DateTimePicker::make('activated_at'),
                        
                        Forms\Components\DateTimePicker::make('expires_at'),
                    ]),

                Section::make('Content Data')
                    ->icon('heroicon-o-code-bracket')
                    ->schema([
                        Forms\Components\Textarea::make('content_data')
                            ->label('Content Data (JSON)')
                            ->columnSpanFull()
                            ->rows(18)
                            ->afterStateHydrated(fn (Forms\Components\Textarea $component, $state) => $component->state(json_encode($state ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)))
                            ->dehydrateStateUsing(fn (?string $state) => json_decode($state ?: '[]', true) ?? [])
                            ->required()
                            ->rule('json'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-document-duplicate'),
                
                Tables\Columns\TextColumn::make('subdomain')
                    ->searchable()
                    ->icon('heroicon-o-link')
                    ->placeholder('Not set')
                    ->copyable()
                    ->label('Domain'),
                
                // Tables\Columns\TextColumn::make('custom_domain')
                //     ->searchable()
                //     ->icon('heroicon-o-globe-europe-africa')
                //     ->placeholder('Not set')
                //     ->copyable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'previewed',
                        'info' => 'paid',
                        'success' => 'active',
                        'danger' => 'suspended',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-eye' => 'previewed',
                        'heroicon-o-credit-card' => 'paid',
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-x-circle' => 'suspended',
                    ]),
                
                Tables\Columns\TextColumn::make('activated_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->placeholder('Not activated'),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'expired' => 'Expired',
                    ]),
                Tables\Filters\SelectFilter::make('template')
                    ->relationship('template', 'name'),
                // Tables\Filters\Filter::make('expired')
                //     ->label('Expired')
                //     ->query(fn ($query) => $query->where('expires_at', '<', now())),
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (WebsiteContent $record): string => $record->getPublicUrl())
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteContents::route('/'),
            'create' => Pages\CreateWebsiteContent::route('/create'),
            'view' => Pages\ViewWebsiteContent::route('/{record}'),
            'edit' => Pages\EditWebsiteContent::route('/{record}/edit'),
        ];
    }
}
