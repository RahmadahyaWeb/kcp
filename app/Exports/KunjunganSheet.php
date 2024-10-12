<?php

namespace App\Exports;

use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KunjunganSheet implements WithTitle, WithEvents, WithColumnFormatting
{
    protected $sales;
    protected $fromDate;
    protected $toDate;

    public function __construct($sales, $fromDate, $toDate)
    {
        $this->sales = $sales;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set header
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');

                $sheet->setCellValue('A1', 'Tgl. Kunjungan');
                $sheet->setCellValue('B1', 'Hari');

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

                $dateBeginLoop = new DateTime($this->fromDate);
                $dateEndLoop = new DateTime($this->toDate);

                $dates = [];
                while ($dateBeginLoop <= $dateEndLoop) {
                    $dates[] = $dateBeginLoop->format('Y-m-d');
                    $dateBeginLoop->modify('+1 day');
                }

                $daysMap = [
                    'Mon' => 'Senin',
                    'Tue' => 'Selasa',
                    'Wed' => 'Rabu',
                    'Thu' => 'Kamis',
                    'Fri' => 'Jumat',
                    'Sat' => 'Sabtu',
                    'Sun' => 'Minggu'
                ];

                // Apply style to header row 1
                $cellRange = 'A1:K2';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);

                // Mulai dari kolom C (kolom 3)
                $startColumn = 3; // C
                $rowIndex = 1;

                foreach ($this->sales as $user_sales) {
                    $endColumn = $startColumn + 4; // C-G, H-L, dst.
                    $sheet->mergeCellsByColumnAndRow($startColumn, $rowIndex, $endColumn, $rowIndex);
                    $sheet->setCellValueByColumnAndRow($startColumn, $rowIndex, $user_sales);
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumn);

                    $sheet->getStyleByColumnAndRow($startColumn, $rowIndex)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $sheet->getStyleByColumnAndRow($startColumn, $rowIndex)
                        ->getFont()
                        ->setBold(true);

                    $sheet->setCellValueByColumnAndRow($startColumn, $rowIndex + 1, 'Kunjungan');

                    $sheet->mergeCellsByColumnAndRow($startColumn + 1, $rowIndex + 1, $startColumn + 2, $rowIndex + 1);
                    $sheet->setCellValueByColumnAndRow($startColumn + 1, $rowIndex + 1, 'Cek In Pertama');

                    $sumIfFormula = '=SUMIF(' . $user_sales . '!$B$3:$B$6897, KUNJUNGAN!$A{row}, ' . $user_sales . '!$L$3:$L$6897)';
                    $cekInPertama = '=IFERROR(VLOOKUP(A{row},' . $user_sales . '!$B$3:$V$8799,4,FALSE),0)';

                    foreach ($dates as $index => $date) {
                        // TGL KUNJUNGAN
                        $tgl_kunjungan = Carbon::parse($date);
                        $excelDate = Date::dateTimeToExcel($tgl_kunjungan);
                        $sheet->setCellValue("A" . ($index + 3), $excelDate);
                        $sheet->getStyle("A" . ($index + 3))->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                        // HARI
                        $dayInEnglish = $tgl_kunjungan->format('D');
                        $dayInIndonesian = $daysMap[$dayInEnglish] ?? $dayInEnglish;
                        $sheet->setCellValue("B" . ($index + 3), $dayInIndonesian);

                        // KUNJUNGAN
                        $rowNumber = $index + 3;
                        $sheet->setCellValue($columnLetter . $rowNumber, str_replace('{row}', $rowNumber, $sumIfFormula));

                        // CEK IN PERTAMA
                        $nextColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnLetter) + 1);
                        $sheet->setCellValue($nextColumnLetter . $rowNumber, str_replace('{row}', $rowNumber, $cekInPertama));
                    }

                    $startColumn += 5; // C, H, M, dst.
                }

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }

    public function title(): string
    {
        return 'Kunjungan';
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
