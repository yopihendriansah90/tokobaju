<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Website')->schema([
                    Forms\Components\TextInput::make('site_name')
                        ->label('Nama Website')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('site_title')
                        ->label('Judul Halaman (Title)')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),

                Forms\Components\Section::make('Branding')->schema([
                    SpatieMediaLibraryFileUpload::make('logo')
                        ->label('Logo Utama')
                        ->collection('logo')
                        ->image()
                        ->imagePreviewHeight('120')
                        ->helperText('Upload logo dalam format PNG/ SVG dengan background transparan bila ada.')
                        ->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('favicon')
                        ->label('Favicon')
                        ->collection('favicon')
                        ->image()
                        ->imageCropAspectRatio('1:1')
                        ->imagePreviewHeight('64')
                        ->helperText('Ikon kecil untuk tab browser, sebaiknya kotak (1:1).')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->label('Logo')
                    ->width(48)
                    ->height(48),
                Tables\Columns\TextColumn::make('site_name')
                    ->label('Nama Website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('site_title')
                    ->label('Judul Halaman')
                    ->limit(40),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
