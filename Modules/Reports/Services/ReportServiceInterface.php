<?php

namespace Modules\Reports\Services;

interface ReportServiceInterface
{
    public function generateWeeklyReport();
    public function downloadWeeklyReport();
}
