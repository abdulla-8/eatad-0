<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\InsuranceCompany;

class CompanyRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $companyRoute = $request->route('companyRoute');
        
        $company = InsuranceCompany::where('company_slug', $companyRoute)
            ->where('is_active', true)
            ->first();

        if (!$company) {
            abort(404);
        }

        session(['current_company' => $company]);
        
        return $next($request);
    }
}