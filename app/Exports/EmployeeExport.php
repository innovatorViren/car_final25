<?php

namespace App\Exports;

use App\Models\{Employee};
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class EmployeeExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $employeeData = Employee::leftJoin('employee_addresses', 'employees.id', '=', 'employee_addresses.employee_id')
            ->leftJoin('employee_documents', 'employees.id', '=', 'employee_documents.employee_id')
            ->leftjoin('departments', 'departments.id', '=', 'employees.department_id')
            ->leftjoin('designations', 'designations.id', '=', 'employees.designation_id')
            ->select([
                'employees.id as id', 'employees.first_name as first_name',
                'employees.last_name as last_name',
                'departments.name as department_id',
                'designations.name as designation_id',
                'employees.rejoin_date as rejoin_date',
                'employees.recruit_again',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as emp_full_name"),
                DB::raw("DATE_FORMAT(employees.join_date, '%d-%m-%Y') as join_date"),
                DB::raw("DATE_FORMAT(employees.left_date, '%d-%m-%Y') as left_date"),
                'employees.employee_code as employee_code',
                'employee_addresses.mobile1 as mobile1',
                'employee_addresses.permanent_address as permanent_address',
                'employee_documents.aadhar_card_no as aadhar_card_no',
                'employee_documents.pan_card_no as pan_card_no',
                DB::raw("(CASE WHEN employees.is_active = 'Yes' THEN 'Active' ELSE 'Inactive' END) as is_active")
            ])
            ->orderByDesc('employees.id')
            ->get();

        $this->data['employeeData'] = $employeeData;

        return view('employee.excel-export', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A7:H7'; // All headers

                $styleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => '12',
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],

                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'd9e1f2',
                        ],
                    ],
                ];

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getRowDimension('7')->setRowHeight(20);
            },
        ];
    }
}
