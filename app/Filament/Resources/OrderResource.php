<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Customer (Registered)')
                            ->nullable()
                            ->searchable(),
                        TextInput::make('customer_name')
                            ->label('Customer Name (Guest)')
                            ->maxLength(255),
                        TextInput::make('customer_email')
                            ->label('Customer Email (Guest)')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('customer_phone')
                            ->label('Customer Phone (Guest)')
                            ->maxLength(255),
                        Textarea::make('shipping_address')
                            ->label('Shipping Address')
                            ->columnSpanFull()
                            ->rows(3),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('IDR')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
                ])->columnSpanFull(),

                Forms\Components\Section::make('Pembayaran')->schema([
                    Select::make('payment_method')
                        ->options([
                            'bank_transfer' => 'Transfer Bank',
                        ])
                        ->required(),
                    ToggleButtons::make('payment_status')
                        ->options([
                            'awaiting_payment' => 'Menunggu Pembayaran',
                            'awaiting_confirmation' => 'Menunggu Konfirmasi',
                            'paid' => 'Lunas',
                            'rejected' => 'Ditolak',
                        ])
                        ->required()
                        ->inline(),
                    DateTimePicker::make('payment_confirmed_at')
                        ->label('Tanggal Konfirmasi'),
                    TextInput::make('payment_reference')
                        ->label('Referensi')
                        ->disabled(),
                    Textarea::make('payment_notes')
                        ->label('Catatan')
                        ->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('payment_proof')
                        ->label('Bukti Transfer')
                        ->collection('payment_proof')
                        ->downloadable()
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Order Items')->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->required()
                                ->disableOptionWhen('stock', 0),
                            TextInput::make('quantity')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->rules(['integer', 'min:1']), // Ensure positive integer
                            TextInput::make('price')
                                ->numeric()
                                ->prefix('IDR')
                                ->required()
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->columns(3)
                        // ->orderable()
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer (Registered)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Customer (Guest)')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'awaiting_payment' => 'gray',
                        'awaiting_confirmation' => 'warning',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('payment_reference')
                    ->label('Referensi')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('payment_proof')
                    ->label('Bukti')
                    ->collection('payment_proof'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'awaiting_payment' => 'Menunggu Pembayaran',
                        'awaiting_confirmation' => 'Menunggu Konfirmasi',
                        'paid' => 'Lunas',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
