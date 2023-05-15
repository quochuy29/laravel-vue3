<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\Impl\RequestRepositoryImpl;

class HandleRequestAfterMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:HandleRequestAfterMonth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle Request After Month';

    /**
     * Execute the console command.
     */
    public function handle(RequestRepositoryImpl $requestRepo): void
    {
        $month = Carbon::now()->format('Y-m');
        $requestRepo->handleRequestAfterMonth($month);
    }
}
