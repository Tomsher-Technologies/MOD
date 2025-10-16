<?php

namespace App\Exports;

use App\Models\Escort;
use App\Models\DropdownOption;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EscortExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $spokenLanguagesOptions;

    public function __construct()
    {
        $this->spokenLanguagesOptions = DropdownOption::whereHas('dropdown', function ($q) {
            $q->where('code', 'spoken_languages');
        })->pluck('value', 'id')->toArray();
    }

    public function collection()
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        
        return Escort::with(['delegations', 'gender', 'nationality', 'unit', 'internalRanking'])
            ->where('event_id', $currentEventId)
            ->where('status', 1)
            ->get();
    }

    public function headings(): array
    {
        return [
            __db('code'),
            __db('military_number'),
            __db('name_en'),
            __db('name_ar'),
            __db('title_en'),
            __db('title_ar'),
            __db('gender'),
            __db('nationality'),
            __db('unit'),
            __db('spoken_languages'),
            __db('rank'),
            __db('phone_number'),
            __db('email'),
            __db('date_of_birth'),
            __db('delegation_code'),
        ];
    }

    public function map($escort): array
    {
        $activeDelegation = $escort->delegations->where('pivot.status', 1)->first();
        
        $spokenLanguages = '';
        if (!empty($escort->spoken_languages) && is_array($escort->spoken_languages)) {
            $languageNames = [];
            foreach ($escort->spoken_languages as $langId) {
                if (isset($this->spokenLanguagesOptions[$langId])) {
                    $languageNames[] = $this->spokenLanguagesOptions[$langId];
                }
            }
            $spokenLanguages = implode(', ', $languageNames);
        }
        
        return [
            $escort->code,
            $escort->military_number,
            $escort->name_en,
            $escort->name_ar,
            $escort->title_en,
            $escort->title_ar,
            $escort->gender ? $escort->gender->value : '',
            $escort->nationality ? $escort->nationality->value : '',
            $escort->unit ? $escort->unit->value : '',
            $spokenLanguages,
            $escort->internalRanking ? $escort->internalRanking->value : '',
            $escort->phone_number,
            $escort->email,
            $escort->date_of_birth,
            $activeDelegation ? $activeDelegation->code : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
