<?php

namespace App\Http\Livewire\Table;

use App\Models\MobilRekanan;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Models\Thrubus\Rekanan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RekananTable extends DataTableComponent
{
    // protected $model = Rekanan::class;

    public ?string $defaultSortColumn = 'idrekanan_mitra';
    public string $defaultSortDirection = 'desc';

    public function builder(): Builder
    {
        return Rekanan::query()
            ->where('mitra_idmitra', auth()->user()->mitra_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('idrekanan_mitra');
    }

    public function columns(): array
    {
        return [
            // Column::make("Id", "id")
            //     ->sortable(),
            Column::make("Nama Rekanan", "nama_rekanan")
                ->sortable()
                ->searchable(),
            Column::make("Alamat Rekanan", "alamat_rekanan")
                ->sortable()
                ->searchable(),
            Column::make("Nomor rekanan", "no_hp_rekanan")
                ->sortable()
                ->searchable(),
            Column::make("Action", 'idrekanan_mitra')
                ->format([$this, 'generateActionHtml']),
        ];
    }

    protected $listeners = ['deleteRekan'];

    public function deleteRekan($rekanId)
    {
        try {
            DB::beginTransaction();
            MobilRekanan::where('idrekanan_mitra', $rekanId)->delete();
            Rekanan::where('idrekanan_mitra', $rekanId)->delete();
            DB::commit();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $this->emit('refreshDataTable');
    }


    public function generateActionHtml($value, $column, $row)
    {
        $html = '';
        $html .= '<a class="mx-0" href="' . route('operational.detail-rekanan', ['id' => $column->idrekanan_mitra]) . '"><i class="fa fa-folder" aria-hidden="true"></i></a>';
        $html .= '<a class="mx-3" href="' . route('operational.edit-rekanan', ['id' => $column->idrekanan_mitra]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $html .= '<a href="_blank" class="mx-0 delete-rekan" data-rekan-id="' . $column->idrekanan_mitra . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
        return Str::of($html)->toHtmlString();
    }

    public function query()
    {
        return Rekanan::where('mitra_idmitra', null)->first();
    }
}
