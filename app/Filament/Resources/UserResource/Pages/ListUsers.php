<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create User')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalWidth('5xl')
                ->createAnother(false),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users')
                ->icon('heroicon-o-users')
                ->badge(User::count()),
            
            'active' => Tab::make('Active')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))
                ->badge(User::where('is_active', true)->count())
                ->badgeColor('success'),
            
            'inactive' => Tab::make('Inactive')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge(User::where('is_active', false)->count())
                ->badgeColor('danger'),
            
            'verified' => Tab::make('Verified')
                ->icon('heroicon-o-shield-check')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('email_verified_at'))
                ->badge(User::whereNotNull('email_verified_at')->count())
                ->badgeColor('info'),
            
            'unverified' => Tab::make('Unverified')
                ->icon('heroicon-o-shield-exclamation')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('email_verified_at'))
                ->badge(User::whereNull('email_verified_at')->count())
                ->badgeColor('warning'),

        ];
    }

    public function getTitle(): string
    {
        return 'Users Management';
    }

    public function getSubheading(): ?string
    {
        return 'Manage user accounts and their permissions';
    }
}
