<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MentorshipProgramResource\Pages;
use App\Models\MentorshipProgram;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MentorshipProgramResource extends AuthorizedResource
{
    protected static ?string $model = MentorshipProgram::class;

    protected static ?string $permissionModule = 'mentorship';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Alumni & Career';

    public static function getNavigationLabel(): string
    {
        return 'Mentorship Programs';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('mentor_name')->required()->maxLength(255),
            TextInput::make('mentor_designation')->maxLength(255),
            TextInput::make('organization')->maxLength(255),
            TextInput::make('area_of_expertise')->maxLength(255),
            TextInput::make('contact_email')->email()->maxLength(255),
            Select::make('availability')
                ->required()
                ->options([
                    'open' => 'Open',
                    'closed' => 'Closed',
                ])
                ->default('open'),
            Toggle::make('is_active')->default(true),
            RichEditor::make('description')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('mentor_name')->searchable(),
                TextColumn::make('area_of_expertise')->searchable(),
                TextColumn::make('availability')->badge(),
                TextColumn::make('is_active')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
            ])
            ->filters([
                SelectFilter::make('availability')->options([
                    'open' => 'Open',
                    'closed' => 'Closed',
                ]),
                TernaryFilter::make('is_active'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMentorshipPrograms::route('/'),
            'create' => Pages\CreateMentorshipProgram::route('/create'),
            'edit' => Pages\EditMentorshipProgram::route('/{record}/edit'),
        ];
    }
}
