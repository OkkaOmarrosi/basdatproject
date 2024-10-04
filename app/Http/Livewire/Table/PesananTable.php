<?php

namespace App\Http\Livewire\Table;

use App\Models\MobilRekanan;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PesananTable extends DataTableComponent
{
    // protected $model = Pesanan::class;

    // public ?string $defaultSortColumn = 'id';
    // public string $defaultSortDirection = 'desc';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->searchFilterLazy = true;
    }

    public function builder(): Builder
    {
        $query = Pesanan::query();

        $query->leftJoin('mobil_rekanan', 'pesanan.mobil_rekan', '=', 'mobil_rekanan.id');
        $externalTable = DB::raw(DB::connection('mysql2')->getDatabaseName() . '.mobil as external_mobil');
        $query->leftJoin($externalTable, 'pesanan.idmobil', '=', 'external_mobil.idmobil');

        $query->select('pesanan.*', DB::raw('CASE WHEN pesanan.idmobil IS NULL THEN mobil_rekanan.nopol ELSE external_mobil.nopol END as nopol'));

        $query->when($this->table['search'] ?? "", function ($query, $value) {
            return $query->where(function ($query) use ($value) {
                $query->where('mobil_rekanan.nopol', 'like', '%' . $value . '%')
                    ->orWhere('external_mobil.nopol', 'like', '%' . $value . '%')
                    ->orWhere('pesanan.nama_pemesan', 'like', '%' . $value . '%')
                    ->orWhere('pesanan.nomor_pemesan', 'like', '%' . $value . '%')
                    ->orWhere('pesanan.status', 'like', '%' . $value . '%')
                    ->orWhere('pesanan.layanan_id', 'like', '%' . $value . '%')
                    ->orWhere('pesanan.kode', 'like', '%' . $value . '%');
            });
        });

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("Kode", "kode"),
            Column::make("Nopol", "id")
                ->format([$this, 'getNopol']),
            Column::make("Nama pemesan", "nama_pemesan"),
            Column::make("Nomor pemesan", "nomor_pemesan"),
            Column::make("Type Layanan", "layanan_id")
                ->format([$this, 'generateTypeLayanan']),
            Column::make("Status", "status")
                ->format([$this, 'generateStatusLayanan']),
            Column::make("Action", 'id')
                ->format([$this, 'generateActionHtml']),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('layanan_id')
                ->options([
                    '' => 'All',
                    1 => 'Banyak Tujuan',
                    2 => 'DropOff',
                    3 => 'Lepas Kunci',
                    4 => 'Bulanan',
                ])->filter(function ($value, $query) {
                    return $value->where('layanan_id', $query);
                }),
            SelectFilter::make('status')
                ->options([
                    '' => 'All',
                    'Draft' => 'Draft',
                    'Booking' => 'Booking',
                    'Terjadwal' => 'Terjadwal',
                    'Proses' => 'Proses',
                    'Close' => 'Close',
                    'Expired' => 'Expired',
                    'Off' => 'Off',
                    'Cancel' => 'Cancel',
                ])->filter(function ($value, $query) {
                    return $value->where('status', $query);
                }),
        ];
    }

    public function getNopol($value, $column, $row)
    {
        $data = Pesanan::find($column->id);

        if ($data->idmobil == null) {
            if ($data->mobil_rekan != null) {
                $mobil = MobilRekanan::find($data->mobil_rekan);
                return $mobil->nopol ?? '-';
            } else {
                return '-';
            }
        } else {
            $mobil = DB::connection('mysql2')->table('mobil')->where('idmobil', $data->idmobil)->first();
            return $mobil->nopol ?? '-';
        }
    }


    public function generateActionHtml($value, $column, $row)
    {
        $html = '';
        $html .= '<a class="mx-4" href="' . route('operational.detail-order', ['id' => $column->id]) . '"><i class="fa fa-folder" aria-hidden="true"></i></a>';
        return Str::of($html)->toHtmlString();
    }

    public function generateTypeLayanan($value, $column, $row)
    {
        // dd($column);
        $html = '';

        if ($column->layanan_id == 1)
            $html .= '<span style="background-color: red;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Banyak Tujuan</span>';
        else if ($column->layanan_id == 2)
            $html .= '<span style="background-color: green;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">DropOff</span>';
        else if ($column->layanan_id == 3)
            $html .= '<span style="background-color: blue;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Lepas Kunci</span>';
        else if ($column->layanan_id == 4)
            $html .= '<span style="background-color: yellow;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Bulanan</span>';

        return Str::of($html)->toHtmlString();
    }

    public function generateStatusLayanan($value, $column, $row)
    {
        $html = '';
        if (strtoupper($column->status) == strtoupper('Draft'))
            $html .= '<span style="background-color: rgb(255, 189, 0);color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Draft</span>';
        else if (strtoupper($column->status) == strtoupper('Terjadwal'))
            $html .= '<span style="background-color: green;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Terjadwal</span>';
        else if (strtoupper($column->status) == strtoupper('Proses'))
            $html .= '<span style="background-color: blue;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Proses</span>';
        else if (strtoupper($column->status) == strtoupper('Close'))
            $html .= '<span style="background-color: brown;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Close</span>';
        else if (strtoupper($column->status) == strtoupper('Expired'))
            $html .= '<span style="background-color: black;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Expired</span>';
        else if (strtoupper($column->status) == strtoupper('Off'))
            $html .= '<span style="background-color: grey;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Off</span>';
        else if (strtoupper($column->status) == strtoupper('Cancel'))
            $html .= '<span style="background-color: red;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Cancel</span>';

        return Str::of($html)->toHtmlString();
    }
}
