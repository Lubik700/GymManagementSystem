<?php

namespace App\Filament\Resources;

use Filament\Schemas\Components\Section;
use App\Filament\Resources\UserPendingResource\Pages;
use App\Models\Client;
use App\Models\UserPending;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\Subscription;

// ✅ Add these instead
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

// ✅ Replace with this — use Tables namespace for all

use Carbon\Carbon;
// ✅ Replace with these
use Filament\Actions\Action as TableAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;

class UserPendingResource extends Resource
{
    protected static ?string $model = UserPending::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-user-plus';
    }

    public static function getNavigationLabel(): string
    {
        return 'New Registrations';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Members';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Profile Picture')        // ✅ just Section
            ->schema([
                Forms\Components\Placeholder::make('profile_picture')
                    ->label('Profile Picture')
                    ->content(function ($record) {
                        if (!$record || !$record->profile_picture) {
                            return new \Illuminate\Support\HtmlString(
                                '<div style="width:150px;height:150px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#9ca3af;">No Photo</div>'
                            );
                        }
                        $url = asset('storage/' . $record->profile_picture);
                        return new \Illuminate\Support\HtmlString(
                            '<img src="' . $url . '" style="width:150px;height:150px;border-radius:50%;object-fit:cover;">'
                        );
                    }),
            ]),

        Section::make('Personal Information')   // ✅ just Section
            ->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('contact')->disabled(),
                Forms\Components\DatePicker::make('dob')->label('Date of Birth')->disabled(),
                Forms\Components\Textarea::make('address')->disabled(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male'   => 'Male',
                        'female' => 'Female',
                        'other'  => 'Other',
                    ])->disabled(),
            ])->columns(2),

        Section::make('Status')                 // ✅ just Section
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])->required(),
            ]),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
        ->query(UserPending::query()->where('status', 'pending'))
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
                Tables\Columns\TextColumn::make('gender')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
       TableAction::make('approve')
    ->label('Approve & Activate')
    ->icon('heroicon-o-check-circle')
    ->color('success')
    ->requiresConfirmation(false) // ✅ No separate confirm dialog, form is enough
    ->form([
    Section::make('Select Membership Plan')
        ->schema([
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
        ])->columns(2),
])
->action(function (UserPending $record, array $data): void {
    // Get selected plan
    $plan = \App\Models\Plan::find($data['plan_id']);

    // Calculate end date from plan duration
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

    // Create client
    $client = Client::firstOrCreate(
        ['email' => $record->email],
        [
            'user_pending_id' => $record->id,
            'name'            => $record->name,
            'email'           => $record->email,
            'contact'         => $record->contact,
            'dob'             => $record->dob,
            'address'         => $record->address,
            'gender'          => $record->gender,
            'password'        => $record->password,
            'profile_picture' => $record->profile_picture,
            'status'          => 'active',
        ]
    );

    // Create subscription from plan
    Subscription::create([
        'client_id'  => $client->id,
        'plan_name'  => $plan->name,
        'duration'   => $plan->duration,
        'amount'     => $plan->price,
        'start_date' => $data['start_date'],
        'end_date'   => $endDate->toDateString(),
        'status'     => 'active',
    ]);

    $record->update(['status' => 'approved']);

    Notification::make()
        ->title('Client activated with ' . $plan->name . ' plan!')
        ->success()
        ->send();
}),

    TableAction::make('reject')
        ->label('Reject')
        ->icon('heroicon-o-x-circle')
        ->color('danger')
        ->requiresConfirmation()
        ->visible(fn (UserPending $record): bool => $record->status === 'pending')
        ->action(function (UserPending $record): void {
            $record->update(['status' => 'rejected']);

            Notification::make()
                ->title('Registration rejected.')
                ->danger()
                ->send();
        }),

    ViewAction::make(),
]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserPendings::route('/'),
            'view'  => Pages\ViewUserPending::route('/{record}'),
        ];
    }
}