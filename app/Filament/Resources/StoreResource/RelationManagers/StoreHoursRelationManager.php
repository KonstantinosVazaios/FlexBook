<?php

namespace App\Filament\Resources\StoreResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreHoursRelationManager extends RelationManager
{
    protected static string $relationship = 'storeHours';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->options([
                        '1' => 'Monday',
                        '2' => 'Tuesday',
                        '3' => 'Wednesday',
                        '4' => 'Thursday',
                        '5' => 'Friday',
                        '6' => 'Saturday',
                        '0' => 'Sunday',
                    ])->required(),
                Forms\Components\TimePicker::make('open')->native(false)->seconds(false)->minutesStep(15)->required(),
                Forms\Components\TimePicker::make('close')->native(false)->seconds(false)->minutesStep(15)->required(),
                Forms\Components\Toggle::make('is_open')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dayLabel')
            ->columns([
                Tables\Columns\TextColumn::make('dayLabel'),
                Tables\Columns\IconColumn::make('is_open')->boolean(),
                Tables\Columns\TextColumn::make('open'),
                Tables\Columns\TextColumn::make('close'),
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
