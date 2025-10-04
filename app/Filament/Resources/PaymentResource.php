<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Payment Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-hashtag'),
                        
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),
                        
                        Forms\Components\Select::make('website_content_id')
                            ->relationship('websiteContent', 'website_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Website Name')
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ]),

                Section::make('Payment Details')
                    ->icon('heroicon-o-calculator')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-banknotes'),
                        
                        Forms\Components\TextInput::make('fee')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-receipt-percent'),
                        
                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-tag'),
                        
                        Forms\Components\TextInput::make('voucher_code')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-ticket'),
                        
                        Forms\Components\TextInput::make('voucher_discount')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-gift'),
                        
                        Forms\Components\TextInput::make('final_amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-currency-dollar'),
                    ]),

                Section::make('Status & Timeline')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-flag'),
                        
                        Forms\Components\DateTimePicker::make('expired_at'),
                        
                        Forms\Components\DateTimePicker::make('paid_at'),

                        Forms\Components\DateTimePicker::make('transaction_time'),
                    ]),

                Section::make('Payment Gateway Response')
                    ->icon('heroicon-o-server')
                    ->schema([
                        Forms\Components\KeyValue::make('midtrans_data')
                            ->columnSpanFull()
                            ->label('Midtrans Data')
                            ->reorderable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-hashtag')
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),
                
                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => ['failed', 'expired', 'cancelled'],
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'paid',
                        'heroicon-o-x-circle' => ['failed', 'expired', 'cancelled'],
                    ]),
                
                Tables\Columns\TextColumn::make('voucher_code')
                    ->searchable()
                    ->icon('heroicon-o-ticket')
                    ->placeholder('No voucher'),
                
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->placeholder('Not paid'),
                
                Tables\Columns\TextColumn::make('expired_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->color(fn ($record) => $record->expired_at && $record->expired_at->isPast() ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('amount_to')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['amount_from'], fn ($query) => $query->where('final_amount', '>=', $data['amount_from']))
                            ->when($data['amount_to'], fn ($query) => $query->where('final_amount', '<=', $data['amount_to']));
                    }),
                Tables\Filters\Filter::make('date_range')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (Payment $record): string => route('invoice.show', $record->code))
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
