<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Contracts\Tenant;

class MigrateTenantDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenant;

    /**
     * Create a new job instance.
     *
     * @param  TenantContract  $tenant
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Switch to tenant's database connection
        $this->tenant->getTenantKey();

        // Run migrations for tenant
        Artisan::call('migrate', [
            '--database' => 'tenant', // The tenant's database connection name
            '--path' => 'database/migrations', // The path to your migrations directory
        ]);
    }
}
