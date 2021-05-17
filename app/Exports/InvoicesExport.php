<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;

class InvoicesExport implements FromArray, WithHeadings, WithColumnFormatting, WithProperties
{

    private array $data;
    private array $headings;
    private array $columnFormats;

    public function __construct(array $data, array $headings, array $columnFormat)
    {
        $this->data = $data;
        $this->headings = $headings;
        $this->columnFormats = $columnFormat;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function columnFormats(): array
    {
        return $this->columnFormats;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'ICEGPS',
            'lastModifiedBy' => 'ICEGPS',
            'title'          => 'Title-ICEGPS',
            'description'    => '工作报表',
            'subject'        => 'Invoices',
            'keywords'       => 'work,export,spreadsheet',
            'category'       => 'work',
            'manager'        => 'ICEGPS',
            'company'        => 'ICEGPS',
        ];
    }
}
