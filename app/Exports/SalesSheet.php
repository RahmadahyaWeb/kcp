<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SalesSheet implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithMapping, WithColumnFormatting, WithTitle
{
    protected $user_sales;
    protected $fromDate;
    protected $toDate;

    public function __construct($user_sales, $fromDate, $toDate)
    {
        $this->user_sales = $user_sales;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge cells for headers
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:I1');
                $sheet->mergeCells('J1:K1');
                $sheet->mergeCells('L1:M1');

                // Set headers on row 1 and 2
                $sheet->setCellValue('A1', "Sales");
                $sheet->setCellValue('B1', "Tgl.Kunjungan");
                $sheet->setCellValue('C1', "Kode Toko");
                $sheet->setCellValue('D1', "Toko");
                $sheet->setCellValue('E1', "Check In");
                $sheet->setCellValue('F1', "Check Out");
                $sheet->setCellValue('G1', "Keterangan");
                $sheet->setCellValue('H1', "Durasi Kunjungan");
                $sheet->setCellValue('H2', "Lama");
                $sheet->setCellValue('I2', "Punishment");
                $sheet->setCellValue('J1', "Durasi Perjalanan");
                $sheet->setCellValue('J2', "Lama");
                $sheet->setCellValue('K2', "Punishment");
                $sheet->setCellValue('L1', "Cek In / Cek Out");
                $sheet->setCellValue('L2', "Kunjungan");
                $sheet->setCellValue('M2', "Punishment");

                // Style the headers (row 1)
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];

                // Apply style to header row 1
                $cellRange = 'A1:M2';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);

                // Style the second header row (row 2)
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Enable auto width for all columns
                foreach (range('A', 'M') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                // FOOTER
                $lastRow = count($this->collection()) + 3;
                $footerRow = $lastRow + 1;
                $footerRow2 = $lastRow + 2;

                $sheet->setCellValue("A{$footerRow}", "Banyak Punishment");
                $sheet->setCellValue("I{$footerRow}", "=COUNTA(I3:I{$lastRow})");
                $sheet->setCellValue("K{$footerRow}", "=COUNTA(K3:K{$lastRow})");

                $sheet->getStyle("A{$footerRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle("B{$footerRow}:K{$footerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                $event->sheet->getDelegate()->freezePane('H1'); 
            },
        ];
    }


    public function headings(): array
    {
        return [
            "", // Row 1: Empty cell
            "", // Row 1: Empty cell
            "", // Row 1: Empty cell
            "", // Row 1: Empty cell
            "", // Row 1: Empty cell
            "", // Row 1: Empty cell
            "", // Row 2: Durasi Kunjungan
            "", // Row 2: Punishment (empty for now)
        ];
    }

    public function collection()
    {
        $items = DB::table('trns_dks')->select(
            'trns_dks.id',
            'trns_dks.user_sales',
            'master_toko.nama_toko',
            'trns_dks.waktu_kunjungan AS waktu_cek_in',
            'out_data.waktu_kunjungan AS waktu_cek_out',
            'trns_dks.tgl_kunjungan',
            'out_data.keterangan',
            'trns_dks.kd_toko',
            DB::raw('CASE 
                        WHEN out_data.waktu_kunjungan IS NOT NULL 
                        THEN TIMESTAMPDIFF(MINUTE, trns_dks.waktu_kunjungan, out_data.waktu_kunjungan) 
                        ELSE NULL 
                    END AS lama_kunjungan')
        )
            ->leftJoin('trns_dks AS out_data', function ($join) {
                $join->on('trns_dks.user_sales', '=', 'out_data.user_sales')
                    ->whereColumn('trns_dks.kd_toko', 'out_data.kd_toko')
                    ->whereColumn('trns_dks.tgl_kunjungan', 'out_data.tgl_kunjungan')
                    ->where('out_data.type', '=', 'out');
            })
            ->leftJoin('master_toko', 'trns_dks.kd_toko', '=', 'master_toko.kd_toko')
            ->where('trns_dks.type', 'in')
            ->where('trns_dks.user_sales', $this->user_sales)
            ->whereBetween('trns_dks.tgl_kunjungan', [$this->fromDate, $this->toDate])
            ->orderBy('trns_dks.created_at', 'asc')
            ->orderBy('trns_dks.user_sales', 'desc')
            ->get();

        return $items;
    }

    public function map($row): array
    {
        $waktu_cek_in = \Carbon\Carbon::parse($row->waktu_cek_in)->format('H:i:s');
        $waktu_cek_out = \Carbon\Carbon::parse($row->waktu_cek_out)->format('H:i:s');
        $tgl_kunjungan = Carbon::parse($row->tgl_kunjungan);
        $excelDate = Date::dateTimeToExcel($tgl_kunjungan);
        $keterangan = strtolower($row->keterangan);

        $lama_kunjungan = null;
        $punishment_lama_kunjungan = 0;
        $punishment_durasi_lama_perjalanan = 0;
        $lama_perjalanan = '';

        if ($row->lama_kunjungan !== null) {
            $hours = floor($row->lama_kunjungan / 60);
            $minutes = $row->lama_kunjungan % 60;
            $lama_kunjungan = sprintf('%02d:%02d:00', $hours, $minutes);

            /**
             * PUNISHMENT DURASI LAMA KUNJUNGAN
             * MINIMAL KUNJUNGAN ADALAH 30 MENIT
             */

            if ($row->lama_kunjungan < 30) {
                $punishment_lama_kunjungan = 1;
            } else {
                $punishment_lama_kunjungan = 0;
            }
        }

        // LAMA DURASI PERJALANAN
        $cekInSelanjutnya = DB::table('trns_dks')
            ->select(['*'])
            ->where('user_sales', $row->user_sales)
            ->whereDate('tgl_kunjungan', $row->tgl_kunjungan)
            ->where('type', 'in')
            ->where('id', '>', $row->id)
            ->first();

        if ($cekInSelanjutnya) {
            $cek_out = Carbon::parse($row->waktu_cek_out);
            $cek_in  = Carbon::parse($cekInSelanjutnya->waktu_kunjungan);

            $selisih = $cek_out->diff($cek_in);
            $lama_perjalanan = sprintf('%02d:%02d:%02d', $selisih->h, $selisih->i, $selisih->s);
        } else {
            $lama_perjalanan = '00:00:00';
        }

        /**
         * PUNISHMENT DURASI LAMA PERJALANAN
         * MAKSIMAL DURASI LAMA PERJALANAN ADALAH 4O MENIT
         * ISTIRAHAT JUMAT 1 JAM 45 MENIT + 40 MENIT
         * ISTIRAHAT SELAIN JUMAT 1 JAM 15 MENIT + 40 MENIT
         */

        // Menghitung durasi dalam menit
        list($hours, $minutes, $seconds) = explode(':', $lama_perjalanan);
        $lama_perjalanan_dalam_menit = ($hours * 60) + $minutes;

        $max_durasi_lama_perjalanan = 40;
        $isFriday = Carbon::parse($row->tgl_kunjungan)->isFriday();
        $waktu_istirahat = $isFriday ? 105 : 75;
        $max_durasi_lama_perjalanan_plus_waktu_istirahat = $waktu_istirahat + $max_durasi_lama_perjalanan;

        if ($keterangan == 'ist') {
            $punishment_durasi_lama_perjalanan = ($lama_perjalanan_dalam_menit > $max_durasi_lama_perjalanan_plus_waktu_istirahat) ? 1 : 0;
        } else {
            $punishment_durasi_lama_perjalanan = ($lama_perjalanan_dalam_menit > $max_durasi_lama_perjalanan) ? 1 : 0;
        }

        // KUNJUNGAN
        if (($row->lama_kunjungan % 60) > 0) {
            $kunjungan = 1;
        } else {
            $kunjungan = 0;
        }

        return [
            $row->user_sales,
            $excelDate,
            $row->kd_toko,
            $row->nama_toko,
            $waktu_cek_in,
            $waktu_cek_out,
            $row->keterangan,
            $lama_kunjungan,
            $punishment_lama_kunjungan,
            $lama_perjalanan,
            $punishment_durasi_lama_perjalanan,
            $kunjungan,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function title(): string
    {
        return $this->user_sales;
    }
}
