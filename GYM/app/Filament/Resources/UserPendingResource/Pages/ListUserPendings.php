<?php

namespace App\Filament\Resources\UserPendingResource\Pages;

use App\Filament\Resources\UserPendingResource;
use Filament\Resources\Pages\ListRecords;

class ListUserPendings extends ListRecords
{
    protected static string $resource = UserPendingResource::class;
}