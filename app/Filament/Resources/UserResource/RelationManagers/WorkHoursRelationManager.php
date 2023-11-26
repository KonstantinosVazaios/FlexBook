<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkHoursRelationManager extends RelationManager
{
    protected static string $relationship = 'workHours';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->hasRoles('staff');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('store_id')
                    ->label('Store')
                    ->options(Store::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('day')
                    ->options([
                        '1' => 'Monday',
                        '2' => 'Tuesday',
                        '3' => 'Wednesday',
                        '4' => 'Thursday',
                        '5' => 'Friday',
                        '6' => 'Saturday',
                        '7' => 'Sunday',
                    ])->required(),
                Forms\Components\TimePicker::make('start')->native(false)->seconds(false)->minutesStep(15),
                Forms\Components\TimePicker::make('end')->native(false)->seconds(false)->minutesStep(15),
                Forms\Components\Toggle::make('off_work')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day')
            ->modelLabel("Ωραρίου")
            ->pluralModelLabel("Ωράρια Εργασίας")
            ->columns([
                Tables\Columns\TextColumn::make('storeName')->label('Store'),
                Tables\Columns\TextColumn::make('dayLabel')->label('Day'),
                Tables\Columns\IconColumn::make('off_work')->boolean(),
                Tables\Columns\TextColumn::make('start'),
                Tables\Columns\TextColumn::make('end'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
