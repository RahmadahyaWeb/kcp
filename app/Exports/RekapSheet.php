<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapSheet implements WithTitle, WithEvents, WithColumnFormatting
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
                $this->setHeader($sheet);
                $this->populateData($sheet);
                $this->autoSizeColumns($sheet);

                // FREEZE PANE
                $event->sheet->getDelegate()->freezePane('D1');
            }
        ];
    }

    private function setHeader($sheet)
    {
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('A1', 'Alur Kerja Penggunaan DKS');
        $sheet->setCellValue('B1', 'Pelanggaran');
        $sheet->setCellValue('C1', 'Punishment');

        // ALUR PENGGUNAAN DKS
        $sheet->mergeCells('A8:A9');

        $sheet->setCellValue('A3', 'Setiap masuk toko harus check in');
        $sheet->setCellValue('A4', 'Setiap keluar/pulang dari toko harus check in');
        $sheet->setCellValue('A5', 'Check in untuk toko yang pertama di kunjungi setiap hari maksimal jam 09.30');
        $sheet->setCellValue('A6', 'Durasi perjalanan dari toko ke toko berikutnya maksimal 40 menit');
        $sheet->setCellValue('A7', 'Durasi lama berkunjung di toko minimal 30 menit');
        $sheet->setCellValue('A8', 'Lama istirahat 1 jam 15 menit (selain hari jumat) harus memberikan "IST" di system');
        $sheet->setCellValue('A10', 'Lama istirahat 1 jam 45 menit (khusus hari jumat) harus memberikan "IST" di system');

        // PELANGGARAN
        $sheet->mergeCells('B3:B4');

        $sheet->setCellValue('B3', 'Lupa check in atau check out');
        $sheet->setCellValue('B5', 'Check in pertama melebihi 09.30');
        $sheet->setCellValue('B6', 'Durasi perjalanan dari toko ke toko berikutnya');
        $sheet->setCellValue('B7', 'Lama berkunjung di toko tidak sampai 30 menit');
        $sheet->setCellValue('B8', 'Istirahat melebihi 1 jam 15 menit (selain hari jumat)');
        $sheet->setCellValue('B9', 'Istirahat melebihi 1 jam 45 menit (khusus hari jumat)');
        $sheet->setCellValue('B10', 'Tidak memberikan keterangan saat mau istirahat');

        // PUNISHMENT
        $sheet->mergeCells('C3:C4');
        $sheet->mergeCells('C8:C9');

        $sheet->setCellValue('C3', 'Rp. 10.000 / kejadian');
        $sheet->setCellValue('C5', 'Rp. 25.000 / kejadian');
        $sheet->setCellValue('C6', 'Rp. 25.000 / kejadian');
        $sheet->setCellValue('C7', 'Rp. 15.000 / kejadian');
        $sheet->setCellValue('C8', 'Rp. 10.000 / kejadian');
        $sheet->setCellValue('C10', 'Rp. 5.000 / kejadian');

        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'font' => ['bold' => true],
        ];

        $styleArrayAlurPenggunaanDks = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getDelegate()->getStyle('A1:C2')->applyFromArray($styleArray);
        $sheet->getDelegate()->getStyle('A3:C10')->applyFromArray($styleArrayAlurPenggunaanDks);
    }

    private function populateData($sheet)
    {
        $startColumn = 4;
        foreach ($this->sales as $user_sales) {
            $user_sales_upper = strtoupper($user_sales);
            
            $this->setSalesHeaders($sheet, $startColumn, $user_sales_upper);
            $this->fillData($sheet, $startColumn, $user_sales);
            $startColumn += 3;
        }
    }


    private function setSalesHeaders($sheet, $startColumn, $user_sales)
    {
        $endColumn = $startColumn + 2;
        $sheet->mergeCellsByColumnAndRow($startColumn, 1, $endColumn, 1);
        $sheet->setCellValueByColumnAndRow($startColumn, 1, $user_sales);
        $this->styleSalesHeader($sheet, $startColumn);

        $sheet->setCellValueByColumnAndRow($startColumn, 2, 'BANYAK');
        $sheet->setCellValueByColumnAndRow($startColumn + 1, 2, 'REV');
        $sheet->setCellValueByColumnAndRow($startColumn + 2, 2, 'BAYAR');
    }

    private function styleSalesHeader($sheet, $startColumn)
    {
        $sheet->getStyleByColumnAndRow($startColumn, 1)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyleByColumnAndRow($startColumn, 1)
            ->getFont()
            ->setBold(true);
    }

    private function fillData($sheet, $startColumn, $user_sales) {}

    private function autoSizeColumns($sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        foreach (range(1, $highestColumnIndex) as $columnIndex) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }
    }

    public function title(): string
    {
        return 'Rekap';
    }

    public function columnFormats(): array
    {
        return [];
    }
}
