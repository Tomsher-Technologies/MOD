<?php

namespace App\Imports;

use App\Models\Delegation;
use App\Models\DelegationAttachment;
use App\Models\DropdownOption;
use App\Services\ImportLogService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;

class DelegationAttachmentImport implements ToCollection, WithHeadingRow
{
    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'delegation_attachments.xlsx')
    {
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('attachments');
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $rowNumber = 1;

            foreach ($rows as $row) {
                $rowNumber++;

                try {
                    $importCode = trim($row['import_code'] ?? trim($row['delegation_code'] ?? ''));

                    if (empty($importCode)) {
                        $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'Missing delegation_code and import_code', $row->toArray());
                        continue;
                    }

                    $delegation = Delegation::where('code', $importCode)
                        ->orWhere('import_code', $importCode)
                        ->first();

                    if (!$delegation) {
                        $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'Delegation not found with code or import code: ' . $importCode, $row->toArray());
                        continue;
                    }

                    $title = null;
                    if (!empty($row['title_code'])) {
                        $title = DropdownOption::where('code', trim($row['title_code']))
                            ->whereHas('dropdown', function ($query) {
                                $query->where('code', 'attachment_title');
                            })->first();

                        if (!$title) {
                            $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'Invalid title_code: ' . $row['title_code'], $row->toArray());
                            continue;
                        }
                    }

                    $sourcePath = trim($row['file_path'] ?? '');
                    if (empty($sourcePath)) {
                        $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'Missing file_path', $row->toArray());
                        continue;
                    }

                    if (!file_exists($sourcePath)) {
                        $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'File not found at path: ' . $sourcePath, $row->toArray());
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
                        try {
                            if (is_numeric($row['document_date'])) {
                                $documentDate = PhpOfficeDate::excelToDateTimeObject($row['document_date'])->format('Y-m-d');
                            } else {
                                $documentDate = date('Y-m-d', strtotime($row['document_date']));
                            }
                        } catch (\Exception $e) {
                            $this->importLogService->logError('attachments', $this->fileName, $rowNumber, 'Invalid document_date format: ' . $e->getMessage(), $row->toArray());
                            continue;
                        }
                    }

                    $attachment = DelegationAttachment::create([
                        'delegation_id' => $delegation->id,
                        'file_name' => $row['attachment_file_name'] ?? $fileName,
                        'title_id' => $title ? $title->id : null,
                        'file_path' => $destinationPath,
                        'document_date' => $documentDate,
                    ]);

                    $this->importLogService->logSuccess('attachments', $this->fileName, $rowNumber, $row->toArray());
                } catch (\Exception $e) {
                    Log::error('Attachment Import Error (Row ' . $rowNumber . '): ' . $e->getMessage());
                    $this->importLogService->logError('attachments', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attachment Import Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
