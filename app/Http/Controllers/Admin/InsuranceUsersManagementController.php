<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsuranceCompany;
use App\Models\InsuranceUser;
use Illuminate\Support\Facades\Hash;

class InsuranceUsersManagementController extends Controller
{
    // عرض مستخدمين شركة التأمين المحددة
    public function index(InsuranceCompany $insuranceCompany)
    {
        $users = $insuranceCompany->users()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.insurance-users.index', compact('insuranceCompany', 'users'));
    }

    // تبديل حالة المستخدم (نشط/غير نشط)
    public function toggle(InsuranceCompany $insuranceCompany, InsuranceUser $user)
    {
        try {
            // التأكد أن المستخدم ينتمي لهذه الشركة
            if ($user->insurance_company_id !== $insuranceCompany->id) {
                return redirect()->back()->with('error', t('admin.unauthorized_action'));
            }

            $user->update(['is_active' => !$user->is_active]);

            $message = $user->is_active
                ? t('admin.user_activated')
                : t('admin.user_deactivated');

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // حذف المستخدم
    public function destroy(InsuranceCompany $insuranceCompany, InsuranceUser $user)
    {
        try {
            // التأكد أن المستخدم ينتمي لهذه الشركة
            if ($user->insurance_company_id !== $insuranceCompany->id) {
                return redirect()->back()->with('error', t('admin.unauthorized_action'));
            }

            $user->delete();
            return redirect()->back()->with('success', t('admin.user_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // إعادة تعيين كلمة المرور
    public function resetPassword(Request $request, InsuranceCompany $insuranceCompany, InsuranceUser $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        try {
            // التأكد أن المستخدم ينتمي لهذه الشركة
            if ($user->insurance_company_id !== $insuranceCompany->id) {
                return redirect()->back()->with('error', t('admin.unauthorized_action'));
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->back()->with('success', t('admin.password_reset_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    // إحصائيات عامة لجميع مستخدمين شركات التأمين
    public function stats()
    {
        $stats = [
            'total_users' => InsuranceUser::count(),
            'active_users' => InsuranceUser::where('is_active', true)->count(),
            'inactive_users' => InsuranceUser::where('is_active', false)->count(),
            'companies_with_users' => InsuranceCompany::has('users')->count(),
            'users_by_company' => InsuranceCompany::withCount('users')
                ->orderBy('users_count', 'desc')
                ->take(10)
                ->get()
        ];

        return response()->json($stats);
    }
}