<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\ServiceGroup;
use App\Models\User;
use App\Services\ReservationAvailabilityCalculator;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Saade\FilamentFullCalendar\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReservationsCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Reservation::class;

    private int $storeId = 1;

    protected function getFormModel(): Model|string|null
    {
        return $this->event ?? Reservation::class;
    }

    public function resolveEventRecord(array $data): Reservation
    {
        return Reservation::find($data['id']);
    }

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mountUsing(
                function (Forms\Form $form, array $arguments) {
                    if (!array_key_exists('start', $arguments)) return;

                    $selectedDate = (new Carbon($arguments['start']))->format('Y-m-d');
                    $storeIsOpen = ReservationAvailabilityCalculator::isStoreAvailableOnDate($this->storeId, $selectedDate);

                    if (!$storeIsOpen) {
                         Notification::make()
                            ->warning()
                            ->title('Το κατάστημα δεν είναι ανοιχτό την επιλεγμένη ημερομηνία')
                            ->body('Διαλέξτε άλλη ημερομηνία για το ραντεβού.')
                            ->send();
                    }

                    $form->fill([
                        'date' => $arguments['start'] ?? null,
                    ]);
                }
            )
            ->using(function (array $data, string $model): Model {
                $startDate = Carbon::parse($data['date'] . ' ' . $data['start_date'])->format('Y-m-d H:i:s');
                $endDate = Carbon::parse($data['date'] . ' ' . $data['end_date'])->format('Y-m-d H:i:s');

                $user = User::find($data['user_id']);

                if (!$user) {
                    Notification::make()
                        ->warning()
                        ->title('Customer not found')
                        ->body('Please select a customer from the dropdown')
                        ->send();
                    return null;
                }

                $reservation = Reservation::create([
                    "store_id" => 1,
                    "user_id" => $user->id,
                    "name" => $user->name,
                    "telephone" => $user->telephone,
                    "start_date" => $startDate,
                    "end_date" => $endDate
                ]);

                return $reservation;
            })
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
            ->mountUsing(
                function (Forms\Form $form) {
                    $form->fill([
                        'date' => $form->model->start_date ?? null,
                        'start_date' => Carbon::parse($form->model->start_date ?? null)->format('H:i'),
                        'end_date' => Carbon::parse($form->model->end_date ?? null)->format('H:i'),
                        'user_id' => $form->model->user_id
                    ]);
                }
            )
            ->using(function (Model $record, array $data): Model {
                $startDate = Carbon::parse($data['date'] . ' ' . $data['start_date'])->format('Y-m-d H:i:s');
                $endDate = Carbon::parse($data['date'] . ' ' . $data['end_date'])->format('Y-m-d H:i:s');

                $user = User::find($data['user_id']);

                if (!$user) {
                    Notification::make()
                        ->warning()
                        ->title('Customer not found')
                        ->body('Please select a customer from the dropdown')
                        ->send();
                    return $record;
                }

                $record->update([
                    "store_id" =>$this->storeId,
                    "user_id" => $user->id,
                    "name" => $user->name,
                    "telephone" => $user->telephone,
                    "start_date" => $startDate,
                    "end_date" => $endDate
                ]);

                return $record;
            }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return Actions\ViewAction::make()
                ->mountUsing(
                    function (Forms\Form $form) {
                        $form->fill([
                            'date' => $form->model->start_date ?? null,
                            'start_date' => Carbon::parse($form->model->start_date ?? null)->format('H:i'),
                            'end_date' => Carbon::parse($form->model->end_date ?? null)->format('H:i'),
                            'user_id' => $form->model->user_id
                        ]);
                    }
                );
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Reservation::query()
            ->get()
            ->map(
                fn (Reservation $reservation) => [
                    'id' => $reservation->id,
                    'title' => $reservation->name,
                    'start' => $reservation->start_date,
                    'end' => $reservation->end_date,
                    'shouldOpenUrlInNewTab' => false
                ]
            )
            ->all();
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('date')->label("Ημερομηνία")->native(false)->disabledDates(ReservationAvailabilityCalculator::getUnavailableDatesForStore($this->storeId))->hidden(fn (string $operation): bool => $operation === 'edit'),
            Grid::make()->schema([
                Forms\Components\Select::make('start_date')
                ->label('ΑΠΟ')
                ->options(function (Get $get) {
                    $availableHours = ReservationAvailabilityCalculator::getAvailableHoursForStore($this->storeId, (new Carbon($get('date')))->format('Y-m-d'));
                    return array_combine($availableHours, $availableHours);
                })
                ->searchable(),
                Forms\Components\Select::make('end_date')
                ->label('ΕΩΣ')
                ->options(function (Get $get) {
                    $availableHours = ReservationAvailabilityCalculator::getAvailableHoursForStore($this->storeId, (new Carbon($get('date')))->format('Y-m-d'));
                    return array_combine($availableHours, $availableHours);
                })
                ->searchable()
            ]),
            Forms\Components\Select::make('user_id')
                ->label('Πελάτης')
                ->relationship(name: 'user', titleAttribute: 'name')
                ->searchable(['name', 'telephone'])
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('telephone')
                        ->required()
                        ->maxLength(255),
                ])
                ->createOptionUsing(function ($data) {
                    $user = User::create([
                        'name' => $data['name'],
                        'telephone' => $data['telephone']
                    ]);

                    $user->roles()->attach(4);

                    return $user;
                })
                ->required(),
            Forms\Components\Repeater::make('services')
                ->relationship()
                ->schema(function (Get $get) {
                    $date = $get('date');
                    return [
                        Forms\Components\Select::make('service_id')
                            ->label('Υπηρεσία')
                            ->options(Service::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('staff_id')
                            ->label('Εργαζόμενος')
                            ->options(function (Get $get) use ($date) {
                                $serviceId = $get('service_id');
                                return User::query()
                                ->whereHas('roles', function (Builder $query) {
                                    $query->where('code', 'staff');
                                })
                                ->whereHas('services', function (Builder $query) use ($serviceId) {
                                    $query->where('services.id', $serviceId);
                                })
                                ->whereDoesntHave('workLeaves', function (Builder $query) use ($date) {
                                    $query->whereDate('date', $date);
                                })
                                ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                    ];
                })
                ->label('Υπηρεσίες')
                ->required()
                ->reorderableWithDragAndDrop(false)
                ->addActionLabel('ΠΡΟΣΘΗΚΗ')
                ->columns(2)
                ->orderColumn('sort_index')
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    $service = Service::find($data["service_id"]);
                    $data["service_name"] = $service->name;
                    $data["price"] = $service->default_price;

                    return $data;
                })
        ];
    }
}
