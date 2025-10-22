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

class NonBadgePrintedDelegatesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
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
            $countryName = !empty($delegate->delegation->country->name) ? $delegate->delegation->country->getNameEn() : $delegate->delegation->country->getNameAr();
            $continentName = !empty($delegate->delegation->continent->getValueEn()) ? $delegate->delegation->continent->getValueEn() : $delegate->delegation->country->getNameAr();

            $invitationFromValue = !empty($delegate->delegation->invitationFrom->getValueEn()) ? $delegate->delegation->invitationFrom->getValueEn() : $delegate->delegation->invitationFrom->getNameAr();

            return [
                'Delegate Code' => $delegate->code,
                'Title' => $delegate->getTranslation('title', 'en'),
                'Delegate Name' => $delegate->getTranslation('name', 'en'),
                'Delegation Code' => $delegate->delegation->code,
                'Country' => $countryName ?? '',
                'Continent' => $continentName ?? '',
                'Invitation From' => $invitationFromValue ?? '',
                'Designation' => $delegate->getTranslation('designation', 'en'),
                'User Type' => $delegate->team_head ? 'Team Head' : 'Member',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Delegate Code',
            'Title',
            'Delegate Name',
            'Delegation Code',
            'Country',
            'Continent',
            'Invitation From',
            'Designation',
            'User Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Exported on: ' . Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:I1');
                $sheet->getStyle('A1')->getFont()->setBold(true);

                $sheet->getStyle('A2:I2')->getFont()->setBold(true);

                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(25);
                $sheet->getColumnDimension('I')->setWidth(15);

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

                $rowIndex = 3;
                foreach ($orderedDelegates as $delegate) {
                    if ($delegate->team_head) {
                        $sheet->getStyle('A' . $rowIndex . ':I' . $rowIndex)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FFBDBD');

                        $sheet->getStyle('A' . $rowIndex . ':I' . $rowIndex)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FFFFFFFF');
                    }
                    $rowIndex++;
                }
            },
        ];
    }
}
