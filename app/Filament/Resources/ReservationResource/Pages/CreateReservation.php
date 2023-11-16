<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
use App\Services\ReservationAvailabilityCalculator;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    use CreateRecord\Concerns\HasWizard;

    public function getTitle() : string|Htmlable
    {
        return "Κλείσε Ραντεβού";
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Κατάστημα')
                ->icon('heroicon-o-building-storefront')
                ->schema([
                    Select::make('store_id')
                        ->label('Κατάστημα')
                        ->options(Store::where('active', 1)->get()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ]),
            Step::make('Στοιχεία Πελάτη')
                ->icon('heroicon-o-user-group')
                ->schema([
                    Select::make('user_id')
                        ->label('Πελάτης')
                        ->options(User::query()
                            ->whereHas('roles', function (Builder $query) {
                                $query->where('code', 'client');
                            })->pluck('name', 'id'))
                        ->searchable(),
                    TextInput::make('name')
                        // ->required()
                        ->maxLength(255),
                    TextInput::make('telephone')
                        // ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                ]),
            Step::make('Υπηρεσίες')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Repeater::make('services')
                        ->schema([
                            Select::make('service_id')
                                ->label('Υπηρεσία')
                                ->options(Service::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('staff_id')
                                ->label('Εργαζόμενος')
                                ->options(function (Get $get) {
                                    $serviceId = $get('service_id');
                                    return User::query()
                                    ->whereHas('roles', function (Builder $query) {
                                        $query->where('code', 'staff');
                                    })
                                    ->whereHas('services', function (Builder $query) use ($serviceId) {
                                        $query->where('services.id', $serviceId);
                                    })
                                    ->pluck('name', 'id');
                                })
                                ->searchable()
                                ->required(),
                        ])
                        ->label('Υπηρεσίες')
                        ->required()
                        ->reorderableWithDragAndDrop(false)
                        ->addActionLabel('ΠΡΟΣΘΗΚΗ')
                        ->columns(2)
                ]),
            Step::make('Επιλογή Ημερομηνίας & Ώρας')
                ->icon('heroicon-o-calendar-days')
                ->schema([
                    DatePicker::make('date')
                        ->label('Ημερομηνία')
                        ->native(false)
                        ->required()
                        ->disabledDates(fn (Get $get) => ReservationAvailabilityCalculator::getUnavailableDatesForStore($get('store_id'))),
                    TimePicker::make('start')->label('Ώρα από')->native(false)->seconds(false)->minutesStep(15),
                    TimePicker::make('end')->label('Ώρα έως')->native(false)->seconds(false)->minutesStep(15),
                ]),
        ];
    }
}
