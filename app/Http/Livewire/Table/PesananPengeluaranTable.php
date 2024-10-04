<?php

namespace App\Http\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PesananPengeluaran;
use Illuminate\Support\Facades\DB;

class PesananPengeluaranTable extends DataTableComponent
{
    protected $model = PesananPengeluaran::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSortDesc('created_at');
        // $this->setSearchDebounce(1000);
    }

    public function columns(): array
    {
        return [
            Column::make("Kode Pesanan", "header.kode")
                ->sortable()
                ->searchable(),
            Column::make("Keterangan", "iditem_order_pengeluaran")
                ->format([$this, 'generateItemOrderPengeluaran'])
                ->sortable(),
                // ->searchable(),
            Column::make("Jumlah", "jumlah")
                ->sortable()
                ->searchable(),
            Column::make("Pembuat", "created_by.name")
                ->searchable()
                ->sortable(),
            Column::make("Dibuat", "created_at")
                ->searchable()
                ->sortable(),
        ];
    }

    public function generateItemOrderPengeluaran($value)
    {
        $data = DB::connection('mysql2')->table('item_order_pengeluaran')->where('iditem_order_pengeluaran', $value)->first();
        return $data->nama;
    }
}
