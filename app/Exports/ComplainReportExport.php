<?php

namespace App\Exports;

use App\Models\Complain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ComplainReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $complains;
    protected $summaryStats;

    public function __construct($complains, $summaryStats = [])
    {
        $this->complains = $complains;
        $this->summaryStats = $summaryStats;
    }

    public function collection()
    {
        return $this->complains;
    }

    public function headings(): array
    {
        return [
            'SL',
            'Date',
            'Type',
            'Complain/Manual Type',
            'Category',
            'Subject',
            'Buyer Name',
            'Country',
            'PS',
            'PO',
            'CAP',
            'Quantity',
            'Amount',
            'Total',
            'Status'
        ];
    }

    public function map($complain): array
    {
        // Calculate total amount
        $qty = (float) ($complain->quantity ?? 0);
        $amount = (float) ($complain->amount ?? 0);
        $total = $qty * $amount;

        // Get type badge text
        $typeText = $complain->type == 'complain' ? 'COMPLAIN' : 'MANUAL';

        // Get complain type name
        $complainTypeName = $complain->complainType->name ?? '—';

        // Get category name based on type
        if ($complain->type === 'complain') {
            // For complain type, get category from category relationship
            $categoryName = $complain->category->name ?? '—';
        } else {
            // For manual type, get category from manual_category field
            $categoryName = $complain->manual_category ?? '—';
        }

        // Get buyer info
        $buyerName = $complain->buyer?->company_name ?? '—';
        $buyerCountry = $complain->buyer?->country ?? '—';

        // Format status
        $status = $complain->type === 'complain' ? ucfirst(str_replace('_', ' ', $complain->status)) : '—';

        return [
            $complain->id,
            $complain->date ? \Carbon\Carbon::parse($complain->date)->format('d-M-Y') : 'N/A',
            $typeText,
            $complainTypeName,
            $categoryName,
            $complain->subject ?? '—',
            $buyerName,
            $buyerCountry,
            $complain->ps ?? '—',
            $complain->po ?? '—',
            $complain->cap ?? '—',
            $qty > 0 ? $qty : '—',
            $amount > 0 ? $amount : '—',
            $total > 0 ? $total : '—',
            $status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Total rows including header
        $totalRows = $this->complains->count() + 1;

        // Header style - কলাম সংখ্যা আপডেট করা হল (A-O, আগে ছিল A-N)
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9ECEF']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Format columns
        // Date column format
        $sheet->getStyle('B2:B' . $totalRows)->getNumberFormat()
            ->setFormatCode('dd-mmm-yyyy');

        // Number formatting for amount columns
        $sheet->getStyle('L2:L' . $totalRows)->getNumberFormat() // Quantity (was K)
            ->setFormatCode('#,##0');
        $sheet->getStyle('M2:N' . $totalRows)->getNumberFormat() // Amount and Total (was L-M)
            ->setFormatCode('#,##0.00');

        // Alignment settings
        // Center align for specific columns
        $centerColumns = ['A', 'L', 'M', 'N', 'O']; // SL, Qty, Amount, Total, Status (updated)
        foreach ($centerColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $totalRows)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Left align for text columns
        $leftColumns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K']; // Date, Type, C/M Type, Category, Subject, Buyer, Country, PS, PO, CAP (updated)
        foreach ($leftColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $totalRows)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Borders for all data
        $sheet->getStyle('A1:O' . $totalRows)->applyFromArray([ // Updated to O
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row heights for data rows
        for ($row = 2; $row <= $totalRows; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // SL
            'B' => 12,  // Date
            'C' => 10,  // Type (COMPLAIN/MANUAL)
            'D' => 20,  // Complain/Manual Type
            'E' => 20,  // Category
            'F' => 25,  // Subject (new column)
            'G' => 25,  // Buyer Name
            'H' => 15,  // Country
            'I' => 15,  // PS
            'J' => 15,  // PO
            'K' => 15,  // CAP
            'L' => 10,  // Quantity
            'M' => 12,  // Amount
            'N' => 12,  // Total
            'O' => 12,  // Status
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $totalRows = $this->complains->count() + 1;

                // Freeze header row
                $sheet->freezePane('A2');

                // Add auto-filter (updated to O)
                $sheet->setAutoFilter('A1:O' . $totalRows);

                // Add summary stats if available
                if (!empty($this->summaryStats)) {
                    $summaryRow = $totalRows + 2;

                    // Add summary title
                    $sheet->setCellValue('A' . $summaryRow, 'SUMMARY STATISTICS:');
                    $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true);

                    // Total Records
                    $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Records:');
                    $sheet->setCellValue('B' . ($summaryRow + 1), $this->summaryStats['total'] ?? 0);

                    // Complains Count
                    $sheet->setCellValue('A' . ($summaryRow + 2), 'Total Complains:');
                    $sheet->setCellValue('B' . ($summaryRow + 2), $this->summaryStats['complains_count'] ?? 0);

                    // Manuals Count
                    $sheet->setCellValue('A' . ($summaryRow + 3), 'Total Manuals:');
                    $sheet->setCellValue('B' . ($summaryRow + 3), $this->summaryStats['manuals_count'] ?? 0);

                    // Total Quantity
                    $sheet->setCellValue('A' . ($summaryRow + 4), 'Total Quantity:');
                    $sheet->setCellValue('B' . ($summaryRow + 4), number_format($this->summaryStats['total_quantity'] ?? 0, 0));

                    // Total Amount
                    $sheet->setCellValue('A' . ($summaryRow + 5), 'Total Amount:');
                    $sheet->setCellValue('B' . ($summaryRow + 5), number_format($this->summaryStats['total_calculated_amount'] ?? 0, 2));

                    // Status breakdown
                    $sheet->setCellValue('A' . ($summaryRow + 7), 'STATUS BREAKDOWN:');
                    $sheet->getStyle('A' . ($summaryRow + 7))->getFont()->setBold(true);

                    $sheet->setCellValue('A' . ($summaryRow + 8), 'Pending:');
                    $sheet->setCellValue('B' . ($summaryRow + 8), $this->summaryStats['pending'] ?? 0);
                    $sheet->setCellValue('C' . ($summaryRow + 8), ($this->summaryStats['pending_percentage'] ?? 0) . '%');

                    $sheet->setCellValue('A' . ($summaryRow + 9), 'In Progress:');
                    $sheet->setCellValue('B' . ($summaryRow + 9), $this->summaryStats['in_progress'] ?? 0);
                    $sheet->setCellValue('C' . ($summaryRow + 9), ($this->summaryStats['in_progress_percentage'] ?? 0) . '%');

                    $sheet->setCellValue('A' . ($summaryRow + 10), 'Resolved:');
                    $sheet->setCellValue('B' . ($summaryRow + 10), $this->summaryStats['resolved'] ?? 0);
                    $sheet->setCellValue('C' . ($summaryRow + 10), ($this->summaryStats['resolved_percentage'] ?? 0) . '%');

                    $sheet->setCellValue('A' . ($summaryRow + 11), 'Closed:');
                    $sheet->setCellValue('B' . ($summaryRow + 11), $this->summaryStats['closed'] ?? 0);

                    // Style the summary section
                    $sheet->getStyle('A' . $summaryRow . ':C' . ($summaryRow + 11))
                        ->getFont()->setSize(10);

                    $sheet->getStyle('A' . $summaryRow . ':A' . ($summaryRow + 11))
                        ->getFont()->setBold(true);
                }
            },
        ];
    }
}
