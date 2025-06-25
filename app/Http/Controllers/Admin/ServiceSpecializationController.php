<?php
// app/Http/Controllers/Admin/ServiceSpecializationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceSpecialization;

class ServiceSpecializationController extends Controller
{
    public function index()
    {
        $serviceSpecializations = ServiceSpecialization::ordered()->get();
        return view('admin.service-specializations.index', compact('serviceSpecializations'));
    }

    public function create()
    {
        return view('admin.service-specializations.addedit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $data = $request->only(['name', 'name_ar', 'is_active', 'sort_order']);
            
            if (!$request->has('sort_order') || $request->sort_order == 0) {
                $data['sort_order'] = ServiceSpecialization::max('sort_order') + 1;
            }

            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            ServiceSpecialization::create($data);

            return redirect()->route('admin.service-specializations.index')
                ->with('success', t('admin.service_specialization_created'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function edit(ServiceSpecialization $serviceSpecialization)
    {
        return view('admin.service-specializations.addedit', compact('serviceSpecialization'));
    }

    public function update(Request $request, ServiceSpecialization $serviceSpecialization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $data = $request->only(['name', 'name_ar', 'sort_order']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            $serviceSpecialization->update($data);

            return redirect()->route('admin.service-specializations.index')
                ->with('success', t('admin.service_specialization_updated'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function destroy(ServiceSpecialization $serviceSpecialization)
    {
        try {
            $serviceSpecialization->delete();

            return redirect()->route('admin.service-specializations.index')
                ->with('success', t('admin.service_specialization_deleted'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'));
        }
    }

    public function toggle(ServiceSpecialization $serviceSpecialization)
    {
        try {
            $serviceSpecialization->update(['is_active' => !$serviceSpecialization->is_active]);
            
            $message = $serviceSpecialization->is_active 
                ? t('admin.service_specialization_activated')
                : t('admin.service_specialization_deactivated');

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', t('admin.error_occurred'));
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $items = $request->items;
            
            foreach ($items as $index => $id) {
                ServiceSpecialization::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
