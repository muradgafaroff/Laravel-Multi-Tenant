<?php

namespace Modules\Reports\Services;

use Modules\Reports\Repositories\ReportRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportService implements ReportServiceInterface
{
    protected $repo;

    public function __construct(ReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function generateWeeklyReport()
    {
        return $this->repo->getWeeklyCompletedTasks();
    }

    public function downloadWeeklyReport()
    {
        $data = $this->repo->getWeeklyCompletedTasks();
        $pdf = Pdf::loadView('reports::weekly', compact('data'));

        return $pdf->download('weekly_report.pdf');
    }
}
