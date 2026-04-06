<?php

namespace App\Filament\Resources;

use Filament\Schemas\Components\Section;
use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Models\Subscription;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

// ✅ Add these instead
use Filament\Actions\Action;
use Carbon\Carbon;
// ✅ Replace with these
use Filament\Actions\Action as TableAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationLabel(): string
    {
        return 'Clients';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Members';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

   public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Personal Information')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('contact')
                    ->required(),
                Forms\Components\DatePicker::make('dob')
                    ->label('Date of Birth'),
                Forms\Components\Textarea::make('address'),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male'   => 'Male',
                        'female' => 'Female',
                        'other'  => 'Other',
                    ]),
            ])->columns(2),

        Section::make('Membership Status')
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'client'   => 'Client',
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required(),
            ]),
    ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->query(Client::query()->whereIn('status', ['client', 'active'])) // ✅ Only inactive clients
        ->columns([
            Tables\Columns\TextColumn::make('profile_picture')
    ->label('Photo')
    ->formatStateUsing(fn ($state) => $state ? '✓ Uploaded' : 'No photo'),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('contact'),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'client'   => 'warning',
                    'active'   => 'success',
                    'inactive' => 'danger',
                    default    => 'gray',
                }),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Joined')
                ->date()
                ->sortable(),
        ])
        ->actions([
            // Renew subscription action
            TableAction::make('renew_subscription')
    ->label('Renew Subscription')
    ->icon('heroicon-o-arrow-path')
    ->color('primary')
    ->form([
        Forms\Components\Select::make('plan_id')
            ->label('Select Plan')
            ->options(
                \App\Models\Plan::where('is_active', true)
                    ->get()
                    ->mapWithKeys(fn ($plan) => [
                        $plan->id => "{$plan->name} - NPR {$plan->price} ({$plan->duration})"
                    ])
            )
            ->required()
            ->searchable(),
        Forms\Components\DatePicker::make('start_date')
            ->label('Start Date')
            ->required()
            ->default(now()),
    ])
    ->action(function (Client $record, array $data): void {
        $plan = \App\Models\Plan::find($data['plan_id']);

        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = match ($plan->duration) {
            '1 Month'  => $startDate->copy()->addMonth(),
            '2 Months' => $startDate->copy()->addMonths(2),
            '3 Months' => $startDate->copy()->addMonths(3),
            '6 Months' => $startDate->copy()->addMonths(6),
            '1 Year'   => $startDate->copy()->addYear(),
            '2 Years'  => $startDate->copy()->addYears(2),
            default    => $startDate->copy()->addMonth(),
        };

        Subscription::create([
            'client_id'  => $record->id,
            'plan_name'  => $plan->name,
            'duration'   => $plan->duration,
            'amount'     => $plan->price,
            'start_date' => $data['start_date'],
            'end_date'   => $endDate->toDateString(),
            'status'     => 'active',
        ]);

        $record->update(['status' => 'active']);

        Notification::make()
            ->title('Subscription renewed with ' . $plan->name . ' plan!')
            ->success()
            ->send();
    }),
            EditAction::make(),
            ViewAction::make(),
        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'view'  => Pages\ViewClient::route('/{record}'),
            'edit'  => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}