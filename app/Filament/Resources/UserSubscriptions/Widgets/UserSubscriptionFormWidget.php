<?php

namespace App\Filament\Resources\UserSubscriptions\Widgets;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServicePlan;
use App\Models\User;
use App\Models\UserSubscription;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Widgets\Widget;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSubscriptionFormWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    protected string $view = 'filament.resources.user-subscriptions.widgets.user-subscription-form-widget';
    public ?int $subscriptionId = null;
    public ?array $data = [];

    protected $listeners = [
        'subscriptionSelected' => 'setSelectedSubscription',
        'subscription-created' => 'refreshSubscription',
        'subscription-updated' => 'refreshSubscription',
        'subscription-deleted' => 'refreshDubscription',
    ];

    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function setSelectedSubscription($payload = null): void
    {
        $this->subscriptionId = $payload['id'] ?? null;

        if ($this->subscriptionId) {
            $subscription = UserSubscription::with('payments')->find($this->subscriptionId);
            if ($subscription) {
                $this->form->fill(array_merge(
                    $subscription->toArray(),
                    ['payments' => $subscription->payments->toArray()]
                ));
            }
        } else {
            $this->form->fill([]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subscription_code')
                ->readOnly()
                ->inlineLabel()
                ->visible(fn($get) => $get('id') !== null),
                Select::make('customer_id')
                    ->label('Customer')
                    ->searchable()
                    ->options(Customer::query()->pluck('name','id'))
                    ->required(),

                Group::make()
                    ->schema([
                        Select::make('service_id')
                            ->label('Service')
                            ->options(Service::where('is_active', true)->pluck('name','id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => $set('service_plan_id', null)),

                        Select::make('service_plan_id')
                            ->label('Plan')
                            ->options(fn ($get) =>
                                $get('service_id')
                                    ? ServicePlan::where('service_id', $get('service_id'))
                                        ->where('is_active', true)
                                        ->pluck('name','id')
                                    : []
                            )
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->disabled(fn($get) => !$get('service_id')),
                    ])->columns(2),

                Group::make()
                    ->schema([
                        DatePicker::make('start_date')->label('Subscription Start'),
                        DatePicker::make('end_date')->label('Ends at'),
                    ])->columns(2),

                Group::make()
                    ->schema([
                        DatePicker::make('trial_ends_at'),
                        DatePicker::make('cancelled_at'),
                    ])->columns(2),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                        'pending' => 'Pending',
                        'trial' => 'Trial',
                    ])
                    ->disabled()
                    ->helperText('Status diatur otomatis oleh sistem.'),

                Fieldset::make('Payment Histories')
                    ->schema([
                        Repeater::make('payments')
                            ->hiddenLabel()
                            ->addActionLabel('Add Payment')
                            ->schema([
                                TextInput::make('amount')->numeric()->required(),
                                Select::make('status')->options([
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                ])->required(),
                                DatePicker::make('paid_at'),
                            ])
                            ->default(fn($get) => $get('payments') ?? [])
                            ->columnSpan('full'),
                    ])
                    ->visible(fn($get) => $get('id') !== null),
            ])
            ->model(UserSubscription::class)
            ->statePath('data');
    }

    public function save(): void
    {
        if ($this->subscriptionId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    protected function create(): void
    {
        $state = $this->form->getState();

        try {
            DB::transaction(function () use ($state) {
                $payments = $state['payments'] ?? [];
                unset($state['payments']);

                $subscription = UserSubscription::create($state);

                foreach ($payments as $payment) {
                    $subscription->payments()->create($payment);
                }

                $this->subscriptionId = $subscription->id;
                $this->setSelectedSubscription(['id' => $subscription->id]);
            });

            Notification::make()
                ->title('Create Subscription')
                ->body('A subscription successfully created.')
                ->success()
                ->send();

            $this->dispatch('subscription-created');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating Subscription')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function update(): void
    {
        $state = $this->form->getState();

        try {
            DB::transaction(function () use ($state) {
                $payments = $state['payments'] ?? [];
                unset($state['payments']);

                $subscription = UserSubscription::findOrFail($this->subscriptionId);
                $subscription->update($state);

                // update payments manual
                $subscription->payments()->delete();
                foreach ($payments as $payment) {
                    $subscription->payments()->create($payment);
                }

                $this->setSelectedSubscription(['id' => $subscription->id]);
            });

            Notification::make()
                ->title('Subscription Updated')
                ->body('Subscription successfully updated.')
                ->success()
                ->send();

            $this->dispatch('subscription-updated');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Updating Subscription')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function delete(): void
    {
        try {
            $subscription = UserSubscription::findOrFail($this->subscriptionId);
            $subscription->delete();

            $this->subscriptionId = null;
            $this->form->fill([]);

            Notification::make()
                ->title('Subscription Deleted')
                ->body('Subscription successfully deleted.')
                ->success()
                ->send();

            $this->dispatch('subscription-deleted');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Deleting Subscription')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
