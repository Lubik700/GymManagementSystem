<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Client Information')
                ->schema([
                    TextEntry::make('client.name')->label('Client Name'),
                    TextEntry::make('created_at')->label('Submitted At')->dateTime(),
                ])->columns(2),

            Section::make('Feedback')
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('category')
                        ->formatStateUsing(fn ($state) => match($state) {
                            'gym_facility' => 'Gym Facility & Cleanliness',
                            'equipment'    => 'Equipment Quality',
                            'staff'        => 'Staff Behavior',
                            'workout_area' => 'Workout Area',
                            'classes'      => 'Group Classes',
                            default        => 'Other',
                        }),
                    TextEntry::make('rating')
                        ->formatStateUsing(fn ($state) => str_repeat('⭐', $state) . " ($state/5)"),
                    TextEntry::make('message')->columnSpanFull(),
                    TextEntry::make('suggestion')
                        ->label('Suggestions')
                        ->columnSpanFull()
                        ->placeholder('No suggestions provided'),
                ])->columns(2),
        ]);
    }
}