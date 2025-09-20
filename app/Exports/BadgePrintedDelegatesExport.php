<?php

namespace App\Exports;

use App\Models\Delegate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class BadgePrintedDelegatesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $delegates;

    public function __construct($delegates)
    {
        $this->delegates = $delegates;
    }

    public function collection()
    {
        $orderedDelegates = collect();
        
        $delegatesByDelegation = $this->delegates->groupBy('delegation_id');
        
        foreach ($delegatesByDelegation as $delegationId => $delegates) {
            $teamHead = $delegates->firstWhere('team_head', true);
            
            if ($teamHead) {
                $orderedDelegates->push($teamHead);
            }
            
            $members = $delegates->filter(function ($delegate) use ($teamHead) {
                return !$delegate->team_head || ($teamHead && $delegate->id !== $teamHead->id);
            });
            
            foreach ($members as $member) {
                $orderedDelegates->push($member);
            }
        }
        
        return $orderedDelegates->map(function ($delegate) {
            $delegateName = !empty($delegate->name_en) ? $delegate->name_en : $delegate->name_ar;
            
            return [
                'Delegate Code' => $delegate->code,
                'Delegate Name' => $delegateName,
                'Delegation Code' => $delegate->delegation->code,
                'Country' => $delegate->delegation->country->name ?? '',
                'Continent' => $delegate->delegation->continent->value ?? '',
                'Invitation From' => $delegate->delegation->invitationFrom->value ?? '',
                'Title En' => $delegate->title_en ?? '',
                'Title Ar' => $delegate->title_ar ?? '',
                'Designation' => !empty($delegate->designation_en) ? $delegate->designation_en : $delegate->designation_ar,
                'Badge Printed' => $delegate->badge_printed ? 'Yes' : 'No',
                'User Type' => $delegate->team_head ? 'Team Head' : 'Member',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Delegate Code',
            'Delegate Name',
            'Delegation Code',
            'Country',
            'Continent',
            'Invitation From',
            'Title En',
            'Title Ar',
            'Designation',
            'Badge Printed',
            'User Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], 
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert timestamp row
                $sheet->insertNewRowBefore(1, 1); 
                $sheet->setCellValue('A1', 'Exported on: '. Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:K1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                
                // Style headings
                $sheet->getStyle('A2:K2')->getFont()->setBold(true);
                
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(25);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(15);
                
                // Reorder delegates to match the collection order for highlighting
                $orderedDelegates = collect();
                $delegatesByDelegation = $this->delegates->groupBy('delegation_id');
                
                foreach ($delegatesByDelegation as $delegationId => $delegates) {
                    // Find team head (if exists)
                    $teamHead = $delegates->firstWhere('team_head', true);
                    
                    // Add team head first (if exists)
                    if ($teamHead) {
                        $orderedDelegates->push($teamHead);
                    }
                    
                    // Add other members
                    $members = $delegates->filter(function ($delegate) use ($teamHead) {
                        return !$delegate->team_head || ($teamHead && $delegate->id !== $teamHead->id);
                    });
                    
                    foreach ($members as $member) {
                        $orderedDelegates->push($member);
                    }
                }
                
                // Highlight Team Head rows in red
                $rowIndex = 3; // Start from row 3 (after header and timestamp)
                foreach ($orderedDelegates as $delegate) {
                    if ($delegate->team_head) {
                        // Apply red fill to the entire row
                        $sheet->getStyle('A' . $rowIndex . ':K' . $rowIndex)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FFFF0000'); // Red color
                        
                        // Set font color to white for better contrast
                        $sheet->getStyle('A' . $rowIndex . ':K' . $rowIndex)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FFFFFFFF'); // White color
                    }
                    $rowIndex++;
                }
            },
        ];
    }
}