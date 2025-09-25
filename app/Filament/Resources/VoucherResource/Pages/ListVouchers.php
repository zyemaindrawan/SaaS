<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use App\Models\Voucher;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Voucher')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalWidth('5xl')
                ->createAnother(false),
            
            Actions\Action::make('generate_voucher')
                ->label('Quick Generate')
                ->icon('heroicon-o-sparkles')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g., New Year Special 2025'),
                    
                    \Filament\Forms\Components\Select::make('discount_type')
                        ->required()
                        ->options([
                            'percentage' => 'Percentage Discount',
                            'fixed' => 'Fixed Amount Discount',
                        ])
                        ->default('percentage'),
                    
                    \Filament\Forms\Components\TextInput::make('discount_value')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    
                    \Filament\Forms\Components\TextInput::make('usage_limit')
                        ->numeric()
                        ->minValue(1)
                        ->default(100),
                ])
                ->action(function (array $data) {
                    // Generate unique voucher code
                    do {
                        $code = 'VCR-' . strtoupper(Str::random(6));
                    } while (Voucher::where('code', $code)->exists());
                    
                    Voucher::create([
                        'code' => $code,
                        'name' => $data['name'],
                        'discount_type' => $data['discount_type'],
                        'discount_value' => $data['discount_value'],
                        'usage_limit' => $data['usage_limit'],
                        'used_count' => 0,
                        'is_active' => true,
                    ]);
                    
                    Notification::make()
                        ->success()
                        ->title('Voucher Generated')
                        ->body("Voucher '{$code}' has been created successfully!")
                        ->send();
                })
                ->modalWidth('2xl'),

        ];
    }

    public function getTitle(): string
    {
        return 'Voucher Management';
    }

    public function getSubheading(): ?string
    {
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('is_active', true)->count();
        $validVouchers = Voucher::active()->count();
        
        return "Total: {$totalVouchers} vouchers • Active: {$activeVouchers} • Valid: {$validVouchers}";
    }

    public function getTableSearch(): ?string
    {
        return null;
    }

    public function isTableSearchable(): bool
    {
        return false;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50];
    }
}
