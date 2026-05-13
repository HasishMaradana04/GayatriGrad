<?php

namespace App\Filament\Resources\MentorshipProgramResource\Pages;

use App\Filament\Resources\MentorshipProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMentorshipPrograms extends ListRecords
{
    protected static string $resource = MentorshipProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
