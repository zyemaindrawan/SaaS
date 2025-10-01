<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Templates';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Template Information')
                    ->icon('heroicon-o-document')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-identification')
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', \Str::slug($state));
                                    $set('config_path', 'resources/views/templates/' . \Str::slug($state) . '/config.json');
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->regex('/^[a-z0-9-_]+$/')
                            ->prefixIcon('heroicon-o-link')
                            ->disabled(),

                        Forms\Components\TextInput::make('config_path')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('resources/views/templates/corporate/config.json')
                            ->prefixIcon('heroicon-o-folder'),

                        Forms\Components\Select::make('category')
                            ->options([
                                'business' => 'Business',
                                'portfolio' => 'Portfolio',
                                'blog' => 'Blog',
                                'ecommerce' => 'E-commerce',
                                'landing' => 'Landing Page',
                                'corporate' => 'Corporate',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-tag'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('preview_image')
                            ->image()
                            ->directory('template-previews')
                            ->disk('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Section::make('Pricing & Status')
                    ->icon('heroicon-o-currency-dollar')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-banknotes'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->prefixIcon('heroicon-o-bars-3'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('preview_image')
                    ->disk('public')
                    ->height(60)
                    ->width(100),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-document'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-link')
                    ->copyable(),
                
                // Tables\Columns\TextColumn::make('config_path')
                //     ->searchable()
                //     ->sortable()
                //     ->icon('heroicon-o-folder')
                //     ->copyable(),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'business',
                        'success' => 'portfolio',
                        'warning' => 'blog',
                        'danger' => 'ecommerce',
                        'info' => 'landing',
                        'gray' => 'corporate',
                    ]),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar'),
                
                Tables\Columns\TextColumn::make('websites_count')
                    ->label('Used')
                    ->counts('websiteContents')
                    ->icon('heroicon-o-globe-alt')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'business' => 'Business',
                        'portfolio' => 'Portfolio',
                        'blog' => 'Blog',
                        'ecommerce' => 'E-commerce',
                        'landing' => 'Landing Page',
                        'corporate' => 'Corporate',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_to')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['price_from'], fn ($query) => $query->where('price', '>=', $data['price_from']))
                            ->when($data['price_to'], fn ($query) => $query->where('price', '<=', $data['price_to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Template $record): string => route('templates.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([])
            ->defaultSort('sort_order');
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'view' => Pages\ViewTemplate::route('/{record}'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
