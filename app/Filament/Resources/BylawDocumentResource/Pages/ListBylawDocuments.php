<?php

namespace App\Filament\Resources\BylawDocumentResource\Pages;

use App\Filament\Resources\BylawDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBylawDocuments extends ListRecords
{
    protected static string $resource = BylawDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
