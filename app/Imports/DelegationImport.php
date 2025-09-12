<?php

namespace App\Imports;

use App\Models\Delegation;
use App\Models\Delegate;
use App\Models\Interview;
use App\Models\DelegateTransport;
use App\Models\DropdownOption;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DelegationImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $row) {
                $delegationData = $this->processDelegationData($row);
                
                $existingDelegation = Delegation::where('code', trim($row['code']))->first();
                
                if ($existingDelegation) {
                    $existingDelegation->update($delegationData);
                    $delegation = $existingDelegation;
                    
                    $this->logActivity(
                        module: 'Delegations',
                        action: 'update-excel',
                        model: $existingDelegation,
                        submodule: 'managing_members',
                        submoduleId: $existingDelegation->id
                    );
                } else {
                    $delegation = Delegation::create($delegationData);
                    
                    $this->logActivity(
                        module: 'Delegations',
                        action: 'create-excel',
                        model: $delegation,
                        submodule: 'managing_members',
                        submoduleId: $delegation->id
                    );
                }
                
                if (!empty($row['delegate_name_en']) || !empty($row['delegate_name_ar'])) {
                    $this->processDelegate($delegation, $row);
                }
                
                if (!empty($row['interview_date_time'])) {
                    $this->processInterview($delegation, $row);
                }
                
                if (!empty($row['arrival_date_time']) || !empty($row['departure_date_time'])) {
                    $this->processTravel($delegation, $row);
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegation Import Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function processDelegationData($row)
    {
        $delegationData = [
            'code' => trim($row['code']) ?? null,
            'invitation_from_id' => null,
            'continent_id' => null,
            'country_id' => null,
            'invitation_status_id' => null,
            'participation_status_id' => null,
            'note1' => trim($row['note1']) ?? null,
            'note2' => trim($row['note2']) ?? null,
        ];
        
        if (!empty($row['invitation_from_id'])) {
            $invitationFrom = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'departments');
            })->where('id', trim($row['invitation_from_id']))->first();
            
            if ($invitationFrom) {
                $delegationData['invitation_from_id'] = $invitationFrom->id;
            }
        }
        
        if (!empty($row['continent_id'])) {
            $continent = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'continents');
            })->where('id', trim($row['continent_id']))->first();
            
            if ($continent) {
                $delegationData['continent_id'] = $continent->id;
            }
        }
        
        if (!empty($row['country_id'])) {
            $country = Country::where('id', trim($row['country_id']))->first();
            
            if ($country) {
                $delegationData['country_id'] = $country->id;
            }
        }
        
        if (!empty($row['invitation_status_id'])) {
            $invitationStatus = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'invitation_status');
            })->where('id', trim($row['invitation_status_id']))->first();
            
            if ($invitationStatus) {
                $delegationData['invitation_status_id'] = $invitationStatus->id;
            }
        }
        
        if (!empty($row['participation_status_id'])) {
            $participationStatus = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'participation_status');
            })->where('id', trim($row['participation_status_id']))->first();
            
            if ($participationStatus) {
                $delegationData['participation_status_id'] = $participationStatus->id;
            }
        }
        
        return $delegationData;
    }
    
    private function processDelegate($delegation, $row)
    {
        $delegateData = [
            'delegation_id' => $delegation->id,
            'code' => trim($row['delegate_code']) ?? null,
            'title_en' => trim($row['delegate_title_en']) ?? null,
            'title_ar' => trim($row['delegate_title_ar']) ?? null,
            'name_en' => trim($row['delegate_name_en']) ?? null,
            'name_ar' => trim($row['delegate_name_ar']) ?? null,
            'gender_id' => null,
            'designation_en' => trim($row['delegate_designation_en']) ?? null,
            'designation_ar' => trim($row['delegate_designation_ar']) ?? null,
            'parent_id' => null,
            'note' => trim($row['delegate_note']) ?? null,
            'relationship_id' => null,
            'internal_ranking_id' => null,
            'accommodation' => !empty($row['delegate_accommodation']) && strtolower($row['delegate_accommodation']) === 'yes' ? 1 : 0,
            'team_head' => !empty($row['delegate_team_head']) && strtolower($row['delegate_team_head']) === 'yes' ? 1 : 0,
            'badge_printed' => !empty($row['delegate_badge_printed']) && strtolower($row['delegate_badge_printed']) === 'yes' ? 1 : 0,
        ];
        
        if (!empty($row['delegate_gender_id'])) {
            $gender = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'gender');
            })->where('id', trim($row['delegate_gender_id']))->first();
            
            if ($gender) {
                $delegateData['gender_id'] = $gender->id;
            }
        }
        
        if (!empty($row['delegate_relationship_id'])) {
            $relationship = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'relationship');
            })->where('id', trim($row['delegate_relationship_id']))->first();
            
            if ($relationship) {
                $delegateData['relationship_id'] = $relationship->id;
            }
        }
        
        if (!empty($row['delegate_internal_ranking_id'])) {
            $ranking = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'internal_ranking');
            })->where('id', trim($row['delegate_internal_ranking_id']))->first();
            
            if ($ranking) {
                $delegateData['internal_ranking_id'] = $ranking->id;
            }
        }
        
        $existingDelegate = null;
        if (!empty($delegateData['code'])) {
            $existingDelegate = Delegate::where('delegation_id', $delegation->id)
                ->where('code', $delegateData['code'])
                ->first();
        }
        
        if ($existingDelegate) {
            $existingDelegate->update($delegateData);
            $delegate = $existingDelegate;
        } else {
            $delegate = Delegate::create($delegateData);
        }
        
        if (!empty($row['delegate_parent_code'])) {
            $parentDelegate = Delegate::where('delegation_id', $delegation->id)
                ->where('code', trim($row['delegate_parent_code']))
                ->first();
            
            if ($parentDelegate) {
                $delegate->parent_id = $parentDelegate->id;
                $delegate->save();
            }
        }
        
        if (!empty($row['arrival_date_time'])) {
            $this->processDelegateTransport($delegate, $row, 'arrival');
        }
        
        if (!empty($row['departure_date_time'])) {
            $this->processDelegateTransport($delegate, $row, 'departure');
        }
        
        return $delegate;
    }
    
    private function processInterview($delegation, $row)
    {
        $interviewData = [
            'delegation_id' => $delegation->id,
            'date_time' => null,
            'interview_with' => null,
            'other_member_id' => null,
            'status_id' => null,
            'comment' => trim($row['interview_comment']) ?? null,
        ];
        
        if (!empty($row['interview_date_time'])) {
            try {
                if (is_string($row['interview_date_time']) && 
                    preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/', $row['interview_date_time'])) {
                    $interviewData['date_time'] = $row['interview_date_time'];
                } 
                elseif (is_numeric($row['interview_date_time'])) {
                    $interviewData['date_time'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['interview_date_time'])->format('Y-m-d H:i:s');
                }
                else {
                    $dateTime = new \DateTime($row['interview_date_time']);
                    $interviewData['date_time'] = $dateTime->format('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                $interviewData['date_time'] = null;
            }
        }
        
        if (!empty($row['interview_status_id'])) {
            $status = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'interview_status');
            })->where('id', trim($row['interview_status_id']))->first();
            
            if ($status) {
                $interviewData['status_id'] = $status->id;
            }
        }
        
        $interview = Interview::create($interviewData);
        
        if (!empty($row['interview_from_delegate_code'])) {
            $fromDelegate = Delegate::where('delegation_id', $delegation->id)
                ->where('code', trim($row['interview_from_delegate_code']))
                ->first();
            
            if ($fromDelegate) {
                $interview->interviewMembers()->create([
                    'member_id' => $fromDelegate->id,
                    'type' => 'from'
                ]);
            }
        }
        
        if (!empty($row['interview_to_delegate_code'])) {
            $toDelegate = Delegate::where('delegation_id', $delegation->id)
                ->where('code', trim($row['interview_to_delegate_code']))
                ->first();
            
            if ($toDelegate) {
                $interview->interviewMembers()->create([
                    'member_id' => $toDelegate->id,
                    'type' => 'to'
                ]);
            }
        }
        
        return $interview;
    }
    
    private function processTravel($delegation, $row)
    {
    }
    
    private function processDelegateTransport($delegate, $row, $type)
    {
        $fieldPrefix = $type === 'arrival' ? 'arrival_' : 'departure_';
        
        $transportData = [
            'delegate_id' => $delegate->id,
            'type' => $type,
            'mode' => trim($row[$fieldPrefix . 'mode']) ?? null,
            'airport_id' => null,
            'flight_no' => trim($row[$fieldPrefix . 'flight_no']) ?? null,
            'flight_name' => trim($row[$fieldPrefix . 'flight_name']) ?? null,
            'date_time' => null,
            'status' => trim($row[$fieldPrefix . 'status']) ?? null,
            'comment' => trim($row[$fieldPrefix . 'comment']) ?? null,
        ];
        
        if (!empty($row[$fieldPrefix . 'date_time'])) {
            try {
                if (is_string($row[$fieldPrefix . 'date_time']) && 
                    preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/', $row[$fieldPrefix . 'date_time'])) {
                    $transportData['date_time'] = $row[$fieldPrefix . 'date_time'];
                } 
                elseif (is_numeric($row[$fieldPrefix . 'date_time'])) {
                    $transportData['date_time'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$fieldPrefix . 'date_time'])->format('Y-m-d H:i:s');
                }
                else {
                    $dateTime = new \DateTime($row[$fieldPrefix . 'date_time']);
                    $transportData['date_time'] = $dateTime->format('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                $transportData['date_time'] = null;
            }
        }
        
        if (!empty($row[$fieldPrefix . 'airport_id']) && $transportData['mode'] === 'flight') {
            $airport = DropdownOption::whereHas('dropdown', function($q) {
                $q->where('code', 'airports');
            })->where('id', trim($row[$fieldPrefix . 'airport_id']))->first();
            
            if ($airport) {
                $transportData['airport_id'] = $airport->id;
            }
        }
        
        $delegate->delegateTransports()->updateOrCreate(
            ['type' => $type],
            $transportData
        );
        
        $this->updateParticipationStatus($delegate);
    }
    
    protected function updateParticipationStatus($delegate)
    {
        if (!$delegate->exists) {
            $delegate->participation_status = 'to_be_arrived';
            $delegate->save();
            return;
        }

        $arrivalTransport = $delegate->delegateTransports()->where('type', 'arrival')->latest('date_time')->first();
        $departureTransport = $delegate->delegateTransports()->where('type', 'departure')->latest('date_time')->first();

        $newStatus = $delegate->participation_status ?? 'to_be_arrived';

        if ($departureTransport && $departureTransport->status) {
            if ($departureTransport->status === 'to_be_departed' || $departureTransport->status === 'departed') {
                $newStatus = $departureTransport->status;
            } elseif ($arrivalTransport && $arrivalTransport->status) {
                $newStatus = $arrivalTransport->status;
            }
        } elseif ($arrivalTransport && $arrivalTransport->status) {
            $newStatus = $arrivalTransport->status;
        } else {
            $newStatus = 'to_be_arrived';
        }

        if ($delegate->participation_status !== $newStatus) {
            $delegate->participation_status = $newStatus;
            $delegate->save();
        }
    }
}