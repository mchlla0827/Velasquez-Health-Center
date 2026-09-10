<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ExcelExportService
 * ====================
 * Builds a real, properly-formatted .xlsx file for any report - title
 * block, styled headers, borders, freeze panes, autofilter, sensible
 * column widths, and optional status-column color coding. Every
 * report controller method just supplies its own columns/rows; this
 * service handles the professional formatting consistently.
 */
class ExcelExportService
{
    /**
     * Status label -> fill color, used when a "status" column is
     * present (inventory/stock-out style reports).
     */
    protected const STATUS_COLORS = [
        'sufficient stock' => 'C6EFCE', // light green
        'normal' => 'C6EFCE',
        'active' => 'C6EFCE',
        'resolved' => 'C6EFCE',
        'approved' => 'C6EFCE',

        'low stock' => 'FFEB9C', // light amber
        'pending' => 'FFEB9C',
        'pending physician' => 'FFEB9C',
        'near expiry' => 'FFEB9C',
        'ongoing' => 'FFEB9C',

        'out of stock' => 'FFC7CE', // light red
        'stock out' => 'FFC7CE',
        'expired' => 'FFC7CE',
        'rejected' => 'FFC7CE',
        'inactive' => 'FFC7CE',
    ];

    protected const STATUS_TEXT_COLORS = [
        'sufficient stock' => '006100',
        'normal' => '006100',
        'active' => '006100',
        'resolved' => '006100',
        'approved' => '006100',

        'low stock' => '9C6500',
        'pending' => '9C6500',
        'pending physician' => '9C6500',
        'near expiry' => '9C6500',
        'ongoing' => '9C6500',

        'out of stock' => '9C0006',
        'stock out' => '9C0006',
        'expired' => '9C0006',
        'rejected' => '9C0006',
        'inactive' => '9C0006',
    ];

    /**
     * @param string $reportTitle   e.g. "Patient Demographic Report"
     * @param array  $columns       column headers, in order, e.g. ['Patient ID', 'Name', 'Age', ...]
     * @param iterable $rows        each row is an array/object matching $columns order
     * @param string|null $statusColumn  header name of a status column to color-code, if any
     * @param string $filename      download filename, e.g. "patient-demographic-report"
     */
    public function download(
        string $reportTitle,
        array $columns,
        iterable $rows,
        ?string $statusColumn = null,
        string $filename = 'report'
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');

        $lastCol = $this->columnLetter(count($columns));

        // ===== TITLE BLOCK =====
        $sheet->setCellValue('A1', 'Velasquez Health Center');
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', $reportTitle);
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color('FF2563EB')
        );
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Generated: ' . now()->format('F d, Y | h:i A'));
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color('FF6B7280')
        );
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== COLUMN HEADERS (row 5) =====
        $headerRow = 5;
        foreach ($columns as $i => $col) {
            $cell = $this->columnLetter($i + 1) . $headerRow;
            $sheet->setCellValue($cell, $col);
        }
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF')
        );
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1E3A8A');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // ===== DATA ROWS =====
        $statusColIndex = $statusColumn ? array_search($statusColumn, $columns) : false;
        $r = $headerRow + 1;
        $rowCount = 0;

        foreach ($rows as $row) {
            $rowArray = is_array($row) ? $row : (array) $row;
            $values = array_values($rowArray);

            foreach ($values as $i => $value) {
                $cell = $this->columnLetter($i + 1) . $r;
                // Always write as an explicit string so Excel never tries to
                // "helpfully" reinterpret text as a date/number/formula and
                // corrupt it - this was the root cause of scrambled values.
                $sheet->setCellValueExplicit(
                    $cell,
                    $value === null ? '' : (string) $value,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
            }

            if ($statusColIndex !== false && isset($values[$statusColIndex])) {
                $statusKey = strtolower(trim((string) $values[$statusColIndex]));
                if (isset(self::STATUS_COLORS[$statusKey])) {
                    $statusCell = $this->columnLetter($statusColIndex + 1) . $r;
                    $sheet->getStyle($statusCell)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB(self::STATUS_COLORS[$statusKey]);
                    $sheet->getStyle($statusCell)->getFont()
                        ->setBold(true)
                        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF' . self::STATUS_TEXT_COLORS[$statusKey]));
                    $sheet->getStyle($statusCell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            }

            $r++;
            $rowCount++;
        }

        $lastDataRow = $r - 1;

        // ===== BORDERS for the whole table (headers + data) =====
        if ($lastDataRow >= $headerRow) {
            $sheet->getStyle("A{$headerRow}:{$lastCol}{$lastDataRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFD1D5DB'));
        }

        // Zebra-striping for readability (skip status-colored cells - they
        // already carry their own background)
        for ($row = $headerRow + 1; $row <= $lastDataRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8FAFC');
            }
        }

        // ===== COLUMN WIDTHS (auto, with sensible bounds) =====
        foreach ($columns as $i => $col) {
            $colLetter = $this->columnLetter($i + 1);
            $sheet->getColumnDimension($colLetter)->setAutoSize(false);
            $sheet->getColumnDimension($colLetter)->setWidth(max(25, min(45, strlen($col) + 6)));
        }

        // ===== FREEZE HEADER ROW + ENABLE FILTERS =====
        $sheet->freezePane('A' . ($headerRow + 1));
        if ($lastDataRow >= $headerRow) {
            $sheet->setAutoFilter("A{$headerRow}:{$lastCol}{$lastDataRow}");
        }

        // Empty-data message
        if ($rowCount === 0) {
            $sheet->setCellValue('A' . ($headerRow + 1), 'No records available for the selected period.');
            $sheet->mergeCells('A' . ($headerRow + 1) . ":{$lastCol}" . ($headerRow + 1));
            $sheet->getStyle('A' . ($headerRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . ($headerRow + 1))->getFont()->setItalic(true)->setColor(
                new \PhpOffice\PhpSpreadsheet\Style\Color('FF6B7280')
            );
        }

        $safeFilename = $filename . '-' . now()->format('Y-m-d') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$safeFilename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Convert a 1-based column index to a spreadsheet column letter (1 -> A, 27 -> AA, etc).
     */
    protected function columnLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intdiv($index - $mod, 26);
        }
        return $letter;
    }
}