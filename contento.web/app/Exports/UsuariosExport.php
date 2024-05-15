<?php

namespace App\Exports;

use App\Models\User;
use App\Services\UsuarioService;
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
class UsuariosExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithColumnFormatting, WithMapping,WithTitle
{
    protected $q;
    protected $sortBy = 'id';
    protected $sortAsc = true;
    protected $usuarioService;
    public function title(): string
    {
        return 'Usuarios';
    }
    function __construct($q, $sortBy, $sortAsc,UsuarioService $usuarioService)
    {
        $this->q = $q;
        $this->sortBy = $sortBy;
        $this->sortAsc = $sortAsc;
        $this->usuarioService=$usuarioService;
    }

    public function collection()
    {
        $usuarios = $this->usuarioService->ExportList($this->q,$this->sortBy,$this->sortAsc);
        return $usuarios;
    }
    public function headings(): array
    {
        return [
            'Id',
            'Nombre',
            'Email',
            // 'Cliente',
            'Rol',
            'Creado',
            'Actualizado'
        ];
    }
    public function map($usuarios): array
    {
        return [
            $usuarios->id,
            $usuarios->name,
            $usuarios->email,
            //$usuarios->cliente->razon_social ?? "",
            $usuarios->roles->first()->name,
            $usuarios->created_at == null ? "" : Date::datetimeToExcel($usuarios->created_at),
            $usuarios->created_at == null ? "" : Date::datetimeToExcel($usuarios->updated_at),
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            // 'E' => NumberFormat::FORMAT_TEXT,
            'E' => 'dd/mm/yyyy hh:MM:ss',
            'F' => 'dd/mm/yyyy hh:MM:ss',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ffffff');
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('075985');
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFont()
                    ->setBold(true);
                $event->sheet->getDelegate()->getStyle('B:H')
                    ->getFont()
                    ->setSize(12);
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFont()
                    ->setSize(13);
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->setAutoFilter('A1:G1');
            }
        ];
    }
}
