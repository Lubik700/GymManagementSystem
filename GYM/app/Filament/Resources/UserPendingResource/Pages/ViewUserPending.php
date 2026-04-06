<?php

namespace App\Filament\Resources\UserPendingResource\Pages;

use App\Filament\Resources\UserPendingResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;

class ViewUserPending extends ViewRecord
{
    protected static string $resource = UserPendingResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
       Section::make('Profile Picture')
    ->schema([
        ViewEntry::make('profile_picture')
            ->view('filament.profile-picture')
            ->label(''),
    ]),
            Section::make('Personal Information')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('email'),
                    TextEntry::make('contact'),
                    TextEntry::make('dob')->label('Date of Birth')->date(),
                    TextEntry::make('address'),
                    TextEntry::make('gender')->badge(),
                ])->columns(2),

            Section::make('Status')
                ->schema([
                    TextEntry::make('status')->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending'  => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default    => 'gray',
                        }),
                    TextEntry::make('created_at')->label('Registered')->dateTime(),
                ])->columns(2),
        ]);
    }
}