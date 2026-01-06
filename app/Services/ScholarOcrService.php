<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\User;
use App\Models\Scholar;
use Illuminate\Support\Facades\Auth;

class ScholarOcrService
{
    private $extractedData = [];  // Property to store the raw scanned data

    public function processFileWithOcr($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $extractedData = [];

        if ($extension === 'pdf') {
            // Handle PDF: Extract text using PDF parser (basic; enhance if needed for images/tables)
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();
            // Clean text (for OCR-like issues)
            $text = preg_replace('/\s+/', ' ', trim($text));
            $lines = explode("\n", $text);
            $extractedData = $this->parseLinesDynamic($lines);  // Fallback for non-table PDFs
        } elseif (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            // Handle Excel/CSV: Unchanged
            $data = Excel::toArray([], $file)[0];
            $headers = array_shift($data); // First row as headers
            $extractedData = $this->parseRowsDynamic($data, $headers);
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'])) {
            // Handle images with improved OCR for tables
            $ocr = new TesseractOCR($file->getPathname());
            $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
            $ocr->psm(6);  // UPDATED: PSM 6 for tables (better detection)
            $ocr->lang('eng');  // English; change to 'fil' if Filipino
            $text = $ocr->run();
            
            // UPDATED: Better clean text (remove more artifacts, handle OCR errors)
            $text = preg_replace('/\n+/', ' ', $text);  // Flatten lines
            $text = preg_replace('/\s+/', ' ', trim($text));  // Normalize spaces
            $text = preg_replace('/[^\w\s@.\-]/', '', $text);  // Remove junk, keep emails/dots
            $text = preg_replace('/\b[a-zA-Z]\b/', '', $text);  // Remove single letters (artifacts like Cc, D)
            
            // Parse as table
            $tableData = $this->parseAsTable($text);
            $extractedData = $this->parseRowsDynamic($tableData['rows'], $tableData['headers']);
        }

        $this->extractedData = $extractedData;  // Store the raw scanned data

        // Match and prepare results for confirmation (unchanged)
        $results = [];
        foreach ($extractedData as $row) {
            $user = $this->findMatchingUser($row);
            $isEnrolled = false;
            $reason = 'Not matched';
            $isScholar = false;

            if ($user) {
                $isScholar = Scholar::where('student_id', $user->id)->exists();  // Check if already a scholar
                $defaultBatch = \App\Models\ScholarshipBatch::first();
                if ($defaultBatch) {
                    $semesterId = $defaultBatch->semester_id;
                    $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
                        ->where('semester_id', $semesterId)
                        ->where('enrollment_status', 'enrolled')
                        ->exists();
                    $reason = $isEnrolled ? 'Ready to add' : 'User registered but not enrolled';
                } else {
                    $reason = 'No default batch';
                }
            }

            $results[] = [
                'data' => $row,
                'user' => $user,
                'is_enrolled' => $isEnrolled,
                'is_scholar' => $isScholar,
                'reason' => $reason,
                'selected' => false,
            ];
        }

        return $results;
    }

    // UPDATED: Dynamic table parsing for images (more robust for messy OCR)
    private function parseAsTable($text)
    {
        // Split into parts
        $parts = explode(' ', $text);
        if (count($parts) < 10) return ['headers' => [], 'rows' => []];  // Need at least headers + some data
        
        // UPDATED: Detect headers more flexibly (look for common ones like 'firstname', 'lastname')
        $headers = [];
        $dataStart = 0;
        foreach ($parts as $index => $part) {
            $part = strtolower($part);
            if (in_array($part, ['firstname', 'lastname', 'bisu_email', 'contact_no', 'student_id', 'user_id', 'middle', 'course', 'yearlevel'])) {
                $headers[] = $part;
                if (count($headers) >= 9) {  // Stop at 9 headers
                    $dataStart = $index + 1;
                    break;
                }
            }
        }
        
        // If headers not detected, fallback to first 9
        if (count($headers) < 9) {
            $headers = array_slice($parts, 0, 9);
            $dataStart = 9;
        }
        
        // Chunk remaining data into rows of header count
        $dataParts = array_slice($parts, $dataStart);
        $rows = array_chunk($dataParts, count($headers));
        
        return ['headers' => $headers, 'rows' => $rows];
    }

    private function parseLinesDynamic($lines)
    {
        $data = [];
        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));
            if (count($parts) >= 4) {
                $data[] = [
                    'first_name' => $parts[0] ?? '',
                    'middlename' => $parts[1] ?? '',
                    'last_name' => $parts[2] ?? '',
                    'student_id' => $parts[3] ?? '',
                    'course' => $parts[4] ?? '',
                    'year_level' => $parts[5] ?? '',
                    'batch_no' => $parts[6] ?? '',
                    'enrollment_status' => strtolower($parts[7] ?? '') === 'graduated' ? 'graduated' : 'enrolled',
                ];
            }
        }
        return $data;
    }

    private function parseRowsDynamic(array $rows, array $headers): array
    {
        $data = [];
        $headerMap = $this->mapHeaders($headers);

        foreach ($rows as $row) {
            $rowData = [
                'first_name' => isset($headerMap['first_name']) ? $row[$headerMap['first_name']] ?? '' : '',
                'middlename' => isset($headerMap['middlename']) ? $row[$headerMap['middlename']] ?? '' : '',
                'last_name' => isset($headerMap['last_name']) ? $row[$headerMap['last_name']] ?? '' : '',
                'course' => isset($headerMap['course']) ? $row[$headerMap['course']] ?? '' : '',
                'year_level' => isset($headerMap['year_level']) ? $row[$headerMap['year_level']] ?? '' : '',
            ];
            
            // Include ALL columns from the row (for dynamic display)
            foreach ($headers as $index => $header) {
                $cleanHeader = strtolower(str_replace(' ', '_', $header));
                if (!isset($rowData[$cleanHeader])) {
                    $rowData[$cleanHeader] = $row[$index] ?? '';
                }
            }
            
            $data[] = $rowData;
        }
        return $data;
    }

    private function mapHeaders(array $headers): array
    {
        $headerMap = [];
        $possibleHeaders = [
            'first_name' => ['first name', 'firstname', 'first'],
            'middlename' => ['middle name', 'middlename', 'middle'],
            'last_name' => ['last name', 'lastname', 'last'],
            'course' => ['course', 'course name', 'program'],
            'year_level' => ['year level', 'year_level', 'level'],
        ];

        foreach ($headers as $index => $header) {
            $header = strtolower(trim($header));
            foreach ($possibleHeaders as $key => $variations) {
                if (in_array($header, $variations)) {
                    $headerMap[$key] = $index;
                    break;
                }
            }
        }

        return $headerMap;
    }

    private function findMatchingUser($row)
    {
        // Search by email and names
        return User::where('bisu_email', $row['student_id'] ?? '')
              ->orWhere(function ($query) use ($row) {
                  $query->where('firstname', 'LIKE', "%{$row['first_name']}%")
                        ->where('lastname', 'LIKE', "%{$row['last_name']}%");
                  if (!empty($row['middlename'])) {
                      $query->where('middlename', 'LIKE', "%{$row['middlename']}%");
                  }
              })->first();
    }

    public function addSelectedScholars($selectedIds, $results)
    {
        $added = [];
        foreach ($selectedIds as $index) {
            $result = $results[$index] ?? null;
            if ($result && $result['user'] && $result['is_enrolled'] && !$result['is_scholar']) {
                $defaultBatch = \App\Models\ScholarshipBatch::first();
                Scholar::create([
                    'student_id' => $result['user']->id,
                    'batch_id' => $defaultBatch->id ?? 1,
                    'updated_by' => Auth::id(),
                    'date_added' => now()->toDateString(),
                    'status' => 'active',
                    'enrollment_status' => $result['data']['enrollment_status'] ?? 'enrolled',
                ]);
                $added[] = $result['user']->firstname . ' ' . $result['user']->lastname;
            }
        }
        return $added;
    }

    public function getExtractedData()
    {
        return $this->extractedData;
    }
}