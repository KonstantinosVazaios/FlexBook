<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
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
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Admins' => Tab::make("Διαχειριστές")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function (Builder $query) {
                    $query->where('code', 'admin');
                })),
            'Staff' => Tab::make("Εργαζόμενοι")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function (Builder $query) {
                    $query->where('code', 'staff');
                })),
            'Clients' => Tab::make("Πελάτες")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function (Builder $query) {
                    $query->where('code', 'client');
                })),
            'No Role' => Tab::make("Χωρίς Ρόλο")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('roles')),
        ];
    }
}
