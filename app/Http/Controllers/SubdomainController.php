<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function storeSubdomain(Request $request)
    {
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        $hosts = explode('.', $httpHost);
        $subdomain = explode(':', $httpHost);
        $id = !empty($hosts) ? array_shift($hosts) : '';
        $sub = str_replace($id , $request->subdomain, $subdomain[0]);
        
        if (!empty($id)) {
            $tenant = Tenant::create([
                'id' => $request->subdomain,
                'tenancy_db_name' => $request->db_name,
            ]);
            $tenant->domains()->create(['domain' => ($sub == 'localhost') ? "{$request->subdomain}.{$sub}" : $sub]);
        }
    }
}
