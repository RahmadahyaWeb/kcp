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

    public function __construct($user_sales)
    {
        $this->user_sales = $user_sales;
    }

    // Start data from row 3
    public function startCell(): string
    {
        return 'A3';  // Data will start from row 3
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
                $sheet->mergeCells('G1:H1');

                // Set headers on row 1 and 2
                $sheet->setCellValue('A1', "Sales");
                $sheet->setCellValue('B1', "Tgl.Kunjungan");
                $sheet->setCellValue('C1', "Toko");
                $sheet->setCellValue('D1', "Check In");
                $sheet->setCellValue('E1', "Check Out");
                $sheet->setCellValue('F1', "Keterangan");
                $sheet->setCellValue('G1', "Durasi Kunjungan");
                $sheet->setCellValue('G2', "Lama");
                $sheet->setCellValue('H2', "Punishment");

                // Style the headers (row 1)
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Horizontal center
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Vertical center
                    ],
                    'font' => [
                        'bold' => true, // Make text bold
                    ],
                ];

                // Apply style to header row 1
                $cellRange = 'A1:H2';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);

                // Style the second header row (row 2)
                $sheet->getRowDimension(2)->setRowHeight(20); // Optional: Set a height for the second row

                // Enable auto width for all columns
                foreach (range('A', 'H') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
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
        return DB::table('trns_dks')->select(
            'trns_dks.user_sales',
            'master_toko.nama_toko',
            'trns_dks.waktu_kunjungan AS waktu_cek_in',
            'out_data.waktu_kunjungan AS waktu_cek_out',
            'trns_dks.tgl_kunjungan',
            'out_data.keterangan',
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
            ->orderBy('trns_dks.created_at', 'desc')
            ->orderBy('trns_dks.user_sales', 'desc')
            ->get();
    }

    public function map($row): array
    {
        $waktu_cek_in = \Carbon\Carbon::parse($row->waktu_cek_in)->format('H:i:s');
        $waktu_cek_out = \Carbon\Carbon::parse($row->waktu_cek_out)->format('H:i:s');

        $lama_kunjungan = null;
        $punishment = false;

        if ($row->lama_kunjungan !== null) {
            $hours = floor($row->lama_kunjungan / 60);
            $minutes = $row->lama_kunjungan % 60;
            $lama_kunjungan = sprintf('%02d:%02d:00', $hours, $minutes); // Assuming no seconds for lama_kunjungan

            if ($row->lama_kunjungan < 30) {
                $punishment = true;
            }
        }

        $tgl_kunjungan = Carbon::parse($row->tgl_kunjungan);

        $excelDate = Date::dateTimeToExcel($tgl_kunjungan);

        // PUNISHMENT DURASI LAMA KUNJUNGAN
        if ($punishment) {
            $punishment = 1;
        } else {
            $punishment = '-';
        }

        return [
            $row->user_sales,
            $excelDate,
            $row->nama_toko, // Toko
            $waktu_cek_in,   // Check In
            $waktu_cek_out,  // Check Out
            $row->keterangan, // Keterangan
            $lama_kunjungan, // Durasi Kunjungan (Lama)
            $punishment, 
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
