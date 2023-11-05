<?php

namespace App\Filament\Resources\StoreResource\RelationManagers;

use App\Models\Role;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminsRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $pluralModelLabel = 'Admins';
    protected bool $allowsDuplicates = true;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('role_id', Role::where('code', 'admin')->first()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->allowDuplicates(true)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->whereHas('roles', function (Builder $query) {
                        $query->where('code', 'admin');
                    }))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['role_id'] = Role::where('code', 'admin')->first()->id;

                        return $data;
                    })
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->heading("Admins");
    }
}
