<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentLogResource\Pages;
use App\Models\PaymentLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class PaymentLogResource extends Resource
{
    protected static ?string $model = PaymentLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Payment Logs';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Log Information')
                    ->description('Payment activity log details')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('payment_id')
                            ->relationship('payment', 'code')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-credit-card'),
                        
                        Forms\Components\Select::make('action')
                            ->options(PaymentLog::getActions()) // Use the method instead of constant
                            ->required()
                            ->prefixIcon('heroicon-o-bolt'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        
                        Forms\Components\KeyValue::make('data')
                            ->columnSpanFull()
                            ->reorderable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment.code')
                    ->label('Payment Code')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-credit-card'),
                
                Tables\Columns\BadgeColumn::make('action')
                    ->colors([
                        'info' => [PaymentLog::ACTION_CREATED, PaymentLog::ACTION_INITIATED],
                        'warning' => [PaymentLog::ACTION_PROCESSING, PaymentLog::ACTION_ACTIVATION_QUEUED, PaymentLog::ACTION_SNAP_TOKEN_GENERATED],
                        'success' => [PaymentLog::ACTION_SUCCESS, PaymentLog::ACTION_PAID, PaymentLog::ACTION_ACTIVATION_COMPLETED],
                        'danger' => [PaymentLog::ACTION_FAILED, PaymentLog::ACTION_CANCELLED, PaymentLog::ACTION_EXPIRED, PaymentLog::ACTION_SNAP_TOKEN_ERROR],
                    ])
                    ->icon(fn (string $state): string => match ($state) {
                        PaymentLog::ACTION_CREATED => 'heroicon-o-plus-circle',
                        PaymentLog::ACTION_INITIATED => 'heroicon-o-play-circle',
                        PaymentLog::ACTION_PROCESSING => 'heroicon-o-clock',
                        PaymentLog::ACTION_SUCCESS, PaymentLog::ACTION_PAID => 'heroicon-o-check-circle',
                        PaymentLog::ACTION_FAILED, PaymentLog::ACTION_CANCELLED => 'heroicon-o-x-circle',
                        PaymentLog::ACTION_EXPIRED => 'heroicon-o-exclamation-triangle',
                        PaymentLog::ACTION_ACTIVATION_COMPLETED => 'heroicon-o-rocket-launch',
                        PaymentLog::ACTION_SNAP_TOKEN_GENERATED => 'heroicon-o-key',
                        PaymentLog::ACTION_SNAP_TOKEN_ERROR => 'heroicon-o-exclamation-circle',
                        default => 'heroicon-o-information-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options(PaymentLog::getActions()), // Use the method instead of constant
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]) // No bulk actions as requested
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentLogs::route('/'),
            'view' => Pages\ViewPaymentLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable create since logs are auto-generated
    }
}
