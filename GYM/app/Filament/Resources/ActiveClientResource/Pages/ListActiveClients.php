<?php

namespace App\Filament\Resources\ActiveClientResource\Pages;

use App\Filament\Resources\ActiveClientResource;
use Filament\Resources\Pages\ListRecords;

class ListActiveClients extends ListRecords
{
    protected static string $resource = ActiveClientResource::class;
}