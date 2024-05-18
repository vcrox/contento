<?php

namespace App\Exports;

use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PermissionExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithColumnFormatting, WithMapping, WithTitle
{
    protected $q;
    protected $sortBy = 'id';
    protected $sortAsc = true;
    protected $permissionService;
    public function title(): string
    {
        return 'Permissions';
    }
    function __construct($q, $sortBy, $sortAsc, PermissionService $permissionService)
    {
        $this->q = $q;
        $this->sortBy = $sortBy;
        $this->sortAsc = $sortAsc;
        $this->permissionService = $permissionService;
    }

    public function collection()
    {
        $permissions = $this->permissionService->ExportList($this->q, $this->sortBy, $this->sortAsc);
        return $permissions;
    }
    public function headings(): array
    {
        return [
            'Id',
            'Nombre',
            'Creado',
            'Actualizado'
        ];
    }
    public function map($permissions): array
    {
        return [
            $permissions->id,
            $permissions->name,
            $permissions->created_at == null ? "" : Date::datetimeToExcel($permissions->created_at),
            $permissions->created_at == null ? "" : Date::datetimeToExcel($permissions->updated_at),
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => 'dd/mm/yyyy hh:MM:ss',
            'D' => 'dd/mm/yyyy hh:MM:ss',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:D1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ffffff');
                $event->sheet->getDelegate()->getStyle('A1:D1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('075985');
                $event->sheet->getDelegate()->getStyle('A1:D1')
                    ->getFont()
                    ->setBold(true);
                $event->sheet->getDelegate()->getStyle('B:E')
                    ->getFont()
                    ->setSize(12);
                $event->sheet->getDelegate()->getStyle('A1:D1')
                    ->getFont()
                    ->setSize(13);
                $event->sheet->getDelegate()->getStyle('A1:D1')
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->setAutoFilter('A1:D1');
            }
        ];
    }
}
