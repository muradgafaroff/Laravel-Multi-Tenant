<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Reports\Services\ReportServiceInterface;

class ReportsController extends Controller
{
   protected $service;

    public function __construct(ReportServiceInterface $service)
    {
        $this->service = $service;
    }

    public function weeklyReport()
    {
        return response()->json([
            'data' => $this->service->generateWeeklyReport()
        ]);
    }

    public function downloadWeekly()
    {
        return $this->service->downloadWeeklyReport();
    }
}
