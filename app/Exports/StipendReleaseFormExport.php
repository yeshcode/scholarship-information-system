<?php

namespace App\Exports;

use App\Models\Scholar;
use App\Models\StipendsRelease;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StipendReleaseFormExport implements FromCollection, WithHeadings, WithMapping
{
    private int $releaseId;
    private array $columns;
    private ?StipendsRelease $release = null;

    public function __construct(int $releaseId, array $columns)
    {
        $this->releaseId = $releaseId;
        $this->columns = $columns;
        $this->release = StipendsRelease::find($releaseId);
    }

    public function headings(): array
    {
        return array_map(fn($c) => $c['label'] ?? '', $this->columns);
    }

    public function collection(): Collection
    {
        if (!$this->release) return collect([]);

        return Scholar::query()
            ->with(['user.college','user.course','user.yearLevel'])
            ->where('batch_id', $this->release->batch_id)
            ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
            ->select('scholars.*')
            ->orderBy('users.lastname')
            ->orderBy('users.firstname')
            ->get();
    }

    public function map($scholar): array
    {
        $u = $scholar->user;

        $mapValue = function (string $key) use ($u) {
            return match ($key) {
                'firstname'  => $u?->firstname ?? '',
                'middlename' => $u?->middlename ?? '',
                'lastname'   => $u?->lastname ?? '',
                'year_level' => $u?->yearLevel?->year_level ?? $u?->year_level ?? '',
                'course'     => $u?->course?->course_name ?? '',
                'college'    => $u?->college?->college_name ?? '',
                'signature'  => '', // blank for signing
                default      => '',
            };
        };

        $row = [];
        foreach ($this->columns as $c) {
            $row[] = $mapValue($c['key'] ?? '');
        }
        return $row;
    }
}