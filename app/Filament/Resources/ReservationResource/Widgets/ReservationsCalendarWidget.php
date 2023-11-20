<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Saade\FilamentFullCalendar\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ReservationsCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Reservation::class;

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mountUsing(
                function (Forms\Form $form, array $arguments) {
                    $form->fill([
                        'date' => $arguments['start'] ?? null,
                    ]);
                }
            )
            ->using(function (array $data, string $model): Model {
                dd($data);
            })
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return Actions\ViewAction::make();
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Reservation::query()
            ->get()
            ->map(
                fn (Reservation $reservation) => [
                    'id' => $reservation->id,
                    'title' => $reservation->name,
                    'start' => '2023-11-16 18:30:00',
                    'end' => '2023-11-16 20:30:00',
                    'shouldOpenUrlInNewTab' => false
                ]
            )
            ->all();
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('date')->label("Ημερομηνία")->native(false),
            Grid::make()->schema([
                Forms\Components\TimePicker::make('start_time')->label("ΑΠΟ")->native(false)->seconds(false)->minutesStep(15),
                Forms\Components\TimePicker::make('end_time')->label("ΕΩΣ")->native(false)->seconds(false)->minutesStep(15),
            ]),
            Forms\Components\Select::make('user_id')
                ->label('Πελάτης')
                ->options(User::query()
                    ->whereHas('roles', function (Builder $query) {
                        $query->where('code', 'client');
                    })->pluck('name', 'id'))
                ->searchable()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('telephone')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->required()
                        ->email(),
                ])
                ->createOptionUsing(function ($data) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'telephone' => $data['telephone'],
                        'password' => Hash::make('12345678')
                    ]);

                    $user->roles()->attach(4);
                }),
            Forms\Components\Repeater::make('services')
                ->schema([
                    Forms\Components\Select::make('service_id')
                        ->label('Υπηρεσία')
                        ->options(Service::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('staff_id')
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
        ];
    }
}
