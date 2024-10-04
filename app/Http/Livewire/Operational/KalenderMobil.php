<?php

namespace App\Http\Livewire\Operational;

use App\Models\MobilRekanan;
use App\Models\Pesanan;
use App\Models\Thrubus\Mobil;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class KalenderMobil extends Component
{

    public $data;
    public $years = [];
    public $calendar = [];

    public $months = [];

    public $formYear = null;
    public $formMonth = null;

    public function mount()
    {
        $this->getData();
        $this->generateDate(date('Y'));
        $this->getCalendar(date('Y'), date('m'));
    }

    public function updated($val)
    {
        if ($val == 'formYear' || $val == 'formMonth') {
            // dd($this->formYear);
            $this->generateDate($this->formYear);
            $this->getCalendar($this->formYear, $this->formMonth);
        }
    }

    public function getData()
    {
        $this->data = Mobil::with('tipe_mobil')->where('mitra_idmitra', auth()->user()->mitra_id)
            // ->where('idmobil', 1)
            ->get();

        $startYear = 2000;
        $endYear = date('Y') + 10;
        $years = range($startYear, $endYear);

        $this->years = $years;
        $this->formYear = date('Y');
        $this->formMonth = date('m');
        $this->generateDate($this->formYear);
    }

    public function hai($id)
    {
        $mobil = Mobil::with('tipe_mobil')->where('idmobil', $id)->first();

        if ($mobil == null) {
            return $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Warning', 'message' => 'Mobil tidak ada di data kami']);
        }

        return to_route('operational.create-order', [
            'idmobil' => $id,
            'idtipe_mobil' => $mobil->tipe_mobil_idtipe_mobil
            // 'total_km' => $responseEstimasi['data']['total_km']
        ]);
    }

    public function generateDate($year)
    {
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        $check = DB::table('calendars')->whereYear('full_date', $year)->first();
        if ($check == null) {
            $dates = [];
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dates[] = ['date' => $date->format('Y-m-d'), 'created_at' => now(), 'updated_at' => now()];
            }

            foreach ($dates as $chunk) {
                DB::table('calendars')->insert([
                    'full_date' => $chunk['date'],
                    'day' => Carbon::parse($chunk['date'])->format('d'),
                    'month' => Carbon::parse($chunk['date'])->format('m'),
                    'year' => Carbon::parse($chunk['date'])->format('Y'),
                    'format_date' => Carbon::parse($chunk['date'])->isoFormat('D MMMM Y'),
                    'hari' => Carbon::parse($chunk['date'])->isoFormat('dddd'),
                    'bulan' => Carbon::parse($chunk['date'])->isoFormat('MMMM'),
                ]);
            }
        }
    }

    public function getCalendar($year, $month)
    {
        if ($month != null) {
            $data = DB::table('calendars')->whereYear('full_date', $year)->whereMonth('full_date', $month)->get();
            // $data_array = DB::table('calendars')->whereYear('full_date', $year)->whereMonth('full_date', $month)->pluck('full_date')->toArray();
        } else {
            $data = DB::table('calendars')->whereYear('full_date', $year)->get();
            // $data_array = DB::table('calendars')->whereYear('full_date', $year)->pluck('full_date')->toArray();
        }

        $this->months = DB::table('calendars')->whereYear('full_date', $year)->select('month', 'bulan')->distinct()->get();

        foreach ($this->data as $val) {
            $all_history = DB::table('pesanan_mobil_histories')
                ->leftJoin('pesanan', 'pesanan.id', 'pesanan_mobil_histories.pesanan_id')
                ->where('mobil_id', $val->idmobil)
                ->whereYear('tgl_mulai', $year)
                ->whereMonth('tgl_mulai', $month)
                ->select('kode', 'tgl_mulai', 'tgl_selesai', 'status')
                ->get();

            foreach ($all_history as $history) {
                $history->dateArray = [];
                $dateArray = [];
                $startDate = new DateTime($history->tgl_mulai);
                $endDate = new DateTime($history->tgl_selesai);
                while ($startDate <= $endDate) {
                    $dateArray[] = $startDate->format("Y-m-d");
                    $startDate->modify("+1 day");
                }
                $history->dateArray = $dateArray;
            }

            // Clone the $data array for each $val
            $clonedData = json_decode(json_encode($data)); // Deep clone the $data array for each $val

            foreach ($clonedData as $v) {
                $v->pesananList = []; // Initialize a new array to hold all matching pesanan
                foreach ($all_history as $history) {
                    if (in_array($v->full_date, $history->dateArray)) {
                        $v->status = 'booked';
                        $v->pesananList[] = $history; // Add the matched pesanan to the list
                    }
                }
                if (empty($v->pesananList)) {
                    $v->status = 'available';
                    $v->pesananList = null; // Set to null if no pesanan is matched
                }
            }

            $val->calendar = $clonedData; // Assign the cloned data to $val->calendar

        }
    }




    public function createPesanan($day, $month, $year, $idmobi)
    {
        $idtipe_mobil = Mobil::where('idmobil', $idmobi)->first()->tipe_mobil_idtipe_mobil;
        $date = Carbon::create($year, $month, $day)->format('Y-m-d');
        redirect()->route('operational.create-order', ['idmobil' => $idmobi, 'date_mulai' => $date, 'idtipe_mobil' => $idtipe_mobil]);
        // dd($day, $month, $year, $idmobi, $date);
    }

    public function render()
    {
        return view('livewire.operational.kalender-mobil');
    }
}
