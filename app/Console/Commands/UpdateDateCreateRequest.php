<?php

namespace App\Console\Commands;

use App\Repositories\Impl\RequestStartRepositoryImpl;
use Illuminate\Console\Command;

class UpdateDateCreateRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateDateCreateRequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Date Create Request';

    /**
     * Execute the console command.
     */
    public function handle(RequestStartRepositoryImpl $requestStartRepo): void
    {
        $requestStartRepo->updateDateStart();
    }
}
