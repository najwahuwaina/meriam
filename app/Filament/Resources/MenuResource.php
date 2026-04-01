<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    protected static ?string $navigationLabel = 'Menu';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_menu')
    ->label('Nama Menu')
    ->required(),

Radio::make('jenis_menu')
    ->label('Jenis Menu')
    ->options([
        'Menu_Utama' => 'Menu Utama',
        'Menu_Tambahan' => 'Menu Tambahan',
        'Minuman' => 'Minuman',
    ])
    ->required(),

TextInput::make('harga')
    ->label('Harga')
    ->numeric()
    ->required(),

Toggle::make('is_admin')
    ->label('Admin?')
    ->inline(false)
    ->required(),

RichEditor::make('content')
    ->label('Deskripsi / Content')
    ->columnSpan(2)
    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_menu')
            ->label('Nama Menu')
            ->searchable()
            ->sortable(),

BadgeColumn::make('jenis_menu')
    ->label('Jenis Menu')
    ->colors([
        'Menu_Utama' => 'success',
        'Menu_Tambahan' => 'warning',
        'Minuman' => 'info',
    ])
    ->sortable(),

TextColumn::make('harga')
    ->label('Harga')
    ->money('IDR')
    ->sortable(),

IconColumn::make('is_admin')
    ->label('Admin?')
    ->boolean(),

TextColumn::make('content')
    ->label('Deskripsi')
    ->limit(50)
    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
