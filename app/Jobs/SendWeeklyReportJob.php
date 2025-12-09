<?php

namespace App\Jobs;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWeeklyReportJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $completed = $this->user->tasks()->where('status', 'completed')->count();

        $pdf = Pdf::loadView('reports.weekly', [
            'user' => $this->user,
            'completed' => $completed
        ])->output();

        Mail::raw('Your weekly report is attached.', function ($message) use ($pdf) {
            $message->to($this->user->email)
                    ->subject('Weekly Report')
                    ->attachData($pdf, 'weekly-report.pdf');
        });

    }
}
