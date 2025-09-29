<?php

namespace App\Imports;

use App\Models\Delegation;
use App\Models\DelegationAttachment;
use App\Models\DropdownOption;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;

class DelegationAttachmentImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['delegation_code'])) {
                continue;
            }

            try {
                $delegation = Delegation::where('code', $row['delegation_code'])->first();
                
                if (!$delegation) {
                    Log::warning("Delegation not found with code: " . $row['delegation_code']);
                    continue;
                }

                $title = DropdownOption::where('code', $row['title_code'])
                    ->whereHas('dropdown', function ($query) {
                        $query->where('code', 'attachment_title');
                    })->first();
                
                if (!$title) {
                    Log::warning("Invalid title_code: " . $row['title_code']);
                }

                $sourcePath = $row['file_path'];
                if (!file_exists($sourcePath)) {
                    Log::warning("File not found at path: " . $sourcePath);
                    continue;
                }

                $delegationDir = 'delegation-attachments/' . $delegation->code;
                if (!Storage::disk('public')->exists($delegationDir)) {
                    Storage::disk('public')->makeDirectory($delegationDir);
                }

                $fileName = $row['attachment_file_name'] ?? basename($sourcePath);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '_' . uniqid() . '.' . $extension;
                
                $destinationPath = $delegationDir . '/' . $uniqueFileName;
                Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));

                $documentDate = null;
                if (!empty($row['document_date'])) {
                    if (is_numeric($row['document_date'])) {
                        $documentDate = PhpOfficeDate::excelToDateTimeObject($row['document_date'])->format('Y-m-d');
                    } else {
                        $documentDate = date('Y-m-d', strtotime($row['document_date']));
                    }
                }

                DelegationAttachment::create([
                    'delegation_id' => $delegation->id,
                    'file_name' => $row['attachment_file_name'] ?? $fileName,
                    'title_id' => $row['title_id'],
                    'file_path' => $destinationPath,
                    'document_date' => $documentDate,
                ]);
            } catch (\Exception $e) {
                Log::error("Error importing attachment for delegation " . ($row['delegation_code'] ?? 'unknown') . ": " . $e->getMessage());
                continue;
            }
        }
    }

   
    public function batchSize(): int
    {
        return 100;
    }

   
    public function chunkSize(): int
    {
        return 100;
    }
}