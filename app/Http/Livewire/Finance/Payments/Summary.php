<?php

namespace App\Http\Livewire\Finance\Payments;

use Livewire\Component;
use App\Models\Section;
use App\Models\Term;
use App\Models\Fee;
use App\Models\Payment;

class Summary extends Component
{
    public Section $section;
    public $feeType;
    public $terms;
    public $schoolFeeItems = [];

    public function mount(Section $section, $feeType = 'all')
    {
        $this->section = $section;
        $this->feeType = in_array($feeType, ['school', 'pta', 'sisco']) ? $feeType : 'all';
        $this->terms = Term::orderBy('id')->get();
    }

    public function getSummaryDataProperty()
    {
        $feeType = $this->feeType;
        $sectionClasses = $this->section->sectionClasses;
        $feeNameMap = config('fees.payments');
        $summary = [];

        foreach ($sectionClasses as $sectionClass) {
            $activeStudentCount = $sectionClass->sectionClassStudents->where('status', 'Active')->count();
            $feeClasses = $sectionClass->sectionClassFees;

            if ($feeType !== 'all') {
                $feeClasses = $feeClasses->filter(fn ($item) => $item->fee->name === $feeNameMap[$feeType]);
            }

            $feeClassIds = $feeClasses->pluck('id');
            $classSummary = [
                'id' => $sectionClass->id,
                'name' => $sectionClass->name,
                'active_students' => $activeStudentCount,
                'terms' => [],
                'school_breakdown' => [],
            ];

            foreach ($this->terms as $term) {
                $expected = 0;

                foreach ($feeClasses as $feeClass) {
                    foreach ($feeClass->sectionClassFeeItems as $feeItem) {
                        if ($feeItem->term_id == $term->id) {
                            $expected += ($feeItem->amount ?: 0) * max($activeStudentCount, 1);
                        }
                    }
                }

                $collected = Payment::whereIn('section_class_fee_id', $feeClassIds)
                    ->where('term_id', $term->id)
                    ->sum('amount');

                $classSummary['terms'][] = [
                    'term' => $term->name,
                    'expected' => $expected,
                    'collected' => $collected,
                    'pending' => max($expected - $collected, 0),
                ];
            }

            $schoolFeeClass = $sectionClass->sectionClassFees->firstWhere('fee.name', $feeNameMap['school']);
            if ($schoolFeeClass) {
                $schoolBreakdown = [];
                foreach ($schoolFeeClass->sectionClassFeeItems as $feeItem) {
                    $schoolBreakdown[$feeItem->term->name][] = [
                        'description' => $feeItem->description,
                        'amount' => $feeItem->amount,
                    ];
                }
                $classSummary['school_breakdown'] = $schoolBreakdown;
            }

            $summary[] = $classSummary;
        }

        return $summary;
    }

    public function render()
    {
        return view('livewire.finance.payments.summary', [
            'summaryData' => $this->summaryData,
            'feeTypeLabel' => $this->feeType === 'all' ? 'All Fees' : config('fees.payments.' . $this->feeType),
        ]);
    }
}
