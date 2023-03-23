<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Impl\LeaveRequestRepositoryImpl;
use App\Repositories\Impl\LeaveRequestHistoryRepositoryImpl;

class UpdateLeaveQuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateLeaveQuotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Leave Quotas';

    /**
     * Execute the console command.
     */
    public function handle(LeaveRequestRepositoryImpl $leaveRequestRepo, LeaveRequestHistoryRepositoryImpl $LeaveRequestHistoryRepo): void
    {
        $leaveRequestRepo->updateQuotas();
        $LeaveRequestHistoryRepo->insertHistoryLeaveRequest();
    }
}
