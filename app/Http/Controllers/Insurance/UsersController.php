<?php

namespace App\Http\Controllers\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\InsuranceUser;
use App\Models\InsuranceCompany;

class UsersController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();
        
        // بناء الاستعلام
        $query = InsuranceUser::where('insurance_company_id', $company->id)
            ->orderBy('created_at', 'desc');

        // تطبيق الفلاتر
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('policy_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // الحصول على المستخدمين مع pagination
        $users = $query->paginate(15);

        // إحصائيات
        $stats = [
            'total' => InsuranceUser::where('insurance_company_id', $company->id)->count(),
            'active' => InsuranceUser::where('insurance_company_id', $company->id)->where('is_active', true)->count(),
            'inactive' => InsuranceUser::where('insurance_company_id', $company->id)->where('is_active', false)->count(),
            'this_month' => InsuranceUser::where('insurance_company_id', $company->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('insurance.users.index', compact('users', 'stats', 'company'));
    }

    /**
     * عرض صفحة إنشاء مستخدم جديد
     */
    public function create()
    {
        $company = Auth::guard('insurance_company')->user();
        return view('insurance.users.create', compact('company'));
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        $company = Auth::guard('insurance_company')->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('insurance_users', 'phone')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })
            ],
            'national_id' => [
                'required',
                'string',
                'size:14',
                Rule::unique('insurance_users', 'national_id')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })
            ],
            'policy_number' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = InsuranceUser::create([
                'insurance_company_id' => $company->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'policy_number' => $request->policy_number,
                'password' => Hash::make($request->password),
                'is_active' => true
            ]);

            DB::commit();

            return redirect()->route('insurance.users.index', $company->company_slug)
                ->with('success', 'تم إنشاء المستخدم بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المستخدم')
                ->withInput();
        }
    }

    /**
 * عرض تفاصيل المستخدم مع الإحصائيات
 */
public function show($companyRoute, $userId)
{
    $company = Auth::guard('insurance_company')->user();
    
    $user = InsuranceUser::where('insurance_company_id', $company->id)
        ->where('id', $userId)
        ->firstOrFail();

    // إحصائيات المطالبات للمستخدم
    $claimsStats = [
        'total' => \App\Models\Claim::where('insurance_user_id', $user->id)->count(),
        'pending' => \App\Models\Claim::where('insurance_user_id', $user->id)->where('status', 'pending')->count(),
        'approved' => \App\Models\Claim::where('insurance_user_id', $user->id)->where('status', 'approved')->count(),
        'rejected' => \App\Models\Claim::where('insurance_user_id', $user->id)->where('status', 'rejected')->count(),
        'in_progress' => \App\Models\Claim::where('insurance_user_id', $user->id)->where('status', 'in_progress')->count(),
        'completed' => \App\Models\Claim::where('insurance_user_id', $user->id)->where('status', 'completed')->count(),
        'this_month' => \App\Models\Claim::where('insurance_user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count(),
        'this_year' => \App\Models\Claim::where('insurance_user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->count(),
    ];

    // أحدث المطالبات
    $recentClaims = \App\Models\Claim::where('insurance_user_id', $user->id)
        ->with(['serviceCenter', 'insuranceCompany'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // إحصائيات شهرية للرسم البياني
    $monthlyStats = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $monthlyStats[] = [
            'month' => $date->format('M Y'),
            'count' => \App\Models\Claim::where('insurance_user_id', $user->id)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count()
        ];
    }

    return view('insurance.users.show', compact('user', 'company', 'claimsStats', 'recentClaims', 'monthlyStats'));
}


    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit($companyRoute, $userId)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $user = InsuranceUser::where('insurance_company_id', $company->id)
            ->where('id', $userId)
            ->firstOrFail();

        return view('insurance.users.edit', compact('user', 'company'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, $companyRoute, $userId)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $user = InsuranceUser::where('insurance_company_id', $company->id)
            ->where('id', $userId)
            ->firstOrFail();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('insurance_users', 'phone')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })->ignore($user->id)
            ],
            'national_id' => [
                'required',
                'string',
                'size:14',
                Rule::unique('insurance_users', 'national_id')->where(function ($query) use ($company) {
                    return $query->where('insurance_company_id', $company->id);
                })->ignore($user->id)
            ],
            'policy_number' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'policy_number' => $request->policy_number,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('insurance.users.index', $company->company_slug)
                ->with('success', 'تم تحديث بيانات المستخدم بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات')
                ->withInput();
        }
    }

    /**
     * تفعيل/إلغاء تفعيل المستخدم
     */
    public function toggleStatus(Request $request, $companyRoute, $userId)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $user = InsuranceUser::where('insurance_company_id', $company->id)
            ->where('id', $userId)
            ->firstOrFail();

        try {
            $user->update([
                'is_active' => !$user->is_active
            ]);

            $status = $user->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
            
            return response()->json([
                'success' => true,
                'message' => $status . ' المستخدم بنجاح',
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة المستخدم'
            ], 500);
        }
    }

    /**
     * حذف المستخدم (تعطيل نهائي)
     */
    public function destroy($companyRoute, $userId)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $user = InsuranceUser::where('insurance_company_id', $company->id)
            ->where('id', $userId)
            ->firstOrFail();

        try {
            $user->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المستخدم'
            ], 500);
        }
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request, $companyRoute, $userId)
    {
        $company = Auth::guard('insurance_company')->user();
        
        $user = InsuranceUser::where('insurance_company_id', $company->id)
            ->where('id', $userId)
            ->firstOrFail();

        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة تعيين كلمة المرور'
            ], 500);
        }
    }
}
