<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Vouchers';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Voucher Information')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                $set('code', strtoupper($state))
                            )
                            ->prefixIcon('heroicon-o-hashtag'),
                        
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-tag'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Discount Configuration')
                    ->icon('heroicon-o-receipt-percent')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('discount_type')
                            ->required()
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Amount (Rp)',
                            ])
                            ->default('percentage')
                            ->live()
                            ->prefixIcon('heroicon-o-calculator'),
                        
                        Forms\Components\TextInput::make('discount_value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn (Forms\Get $get) => 
                                $get('discount_type') === 'percentage' ? '%' : 'Rp'
                            )
                            // ->helperText(fn (Forms\Get $get) => 
                            //     $get('discount_type') === 'percentage' ? 'Enter percentage (0-100)' : 'Enter fixed amount in Rupiah'
                            // )
                            ->prefixIcon('heroicon-o-currency-dollar'),
                        
                        Forms\Components\TextInput::make('min_purchase')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-banknotes'),
                        
                        Forms\Components\TextInput::make('max_discount')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-stop-circle')
                            ->visible(fn (Forms\Get $get) => $get('discount_type') === 'percentage'),
                    ]),

                Section::make('Usage & Validity')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(1)
                            ->prefixIcon('heroicon-o-users'),
                        
                        Forms\Components\TextInput::make('used_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->prefixIcon('heroicon-o-check-circle'),
                        
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->prefixIcon('heroicon-o-play-circle'),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->after('starts_at')
                            ->prefixIcon('heroicon-o-stop-circle'),
                    ]),

                Section::make('Status')
                    ->icon('heroicon-o-cog')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(false)
                    ->sortable()
                    ->icon('heroicon-o-hashtag')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable(false)
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('discount_display')
                    ->label('Discount')
                    ->getStateUsing(function ($record): string {
                        if ($record->discount_type === 'percentage') {
                            return $record->discount_value . '%';
                        }
                        return 'Rp ' . number_format($record->discount_value, 0, ',', '.');
                    })
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('usage_status')
                    ->label('Usage')
                    ->getStateUsing(function ($record): string {
                        if (!$record->usage_limit) {
                            return $record->used_count . ' / Unlimited';
                        }
                        return $record->used_count . ' / ' . $record->usage_limit;
                    })
                    ->icon('heroicon-o-users'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('validity_status')
                    ->label('Validity')
                    ->getStateUsing(function ($record): string {
                        if ($record->isValid()) {
                            return 'Valid';
                        }
                        
                        if (!$record->is_active) return 'Inactive';
                        if ($record->starts_at && $record->starts_at->isFuture()) return 'Not Started';
                        if ($record->expires_at && $record->expires_at->isPast()) return 'Expired';
                        if ($record->usage_limit && $record->used_count >= $record->usage_limit) return 'Used Up';
                        
                        return 'Invalid';
                    })
                    ->badge()
                    ->color(fn ($record): string => $record->isValid() ? 'success' : 'danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth('2xl'),
                Tables\Actions\EditAction::make()
                    ->modalWidth('2xl'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->headerActions([
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->searchable(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $activeCount = static::getModel()::where('is_active', true)->count();
        return $activeCount > 10 ? 'success' : 'primary';
    }
}
