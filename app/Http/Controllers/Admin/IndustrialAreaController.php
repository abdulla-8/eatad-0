<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndustrialArea;

class IndustrialAreaController extends Controller
{
    public function index()
    {
        $industrialAreas = IndustrialArea::ordered()->get();
        return view('admin.industrial-areas.index', compact('industrialAreas'));
    }

    public function create()
    {
        return view('admin.industrial-areas.addedit');
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
                $data['sort_order'] = IndustrialArea::max('sort_order') + 1;
            }

            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            IndustrialArea::create($data);

            return redirect()->route('admin.industrial-areas.index')
                ->with('success', t('admin.industrial_area_created'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function edit(IndustrialArea $industrialArea)
    {
        return view('admin.industrial-areas.addedit', compact('industrialArea'));
    }

    public function update(Request $request, IndustrialArea $industrialArea)
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

            $industrialArea->update($data);

            return redirect()->route('admin.industrial-areas.index')
                ->with('success', t('admin.industrial_area_updated'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function destroy(IndustrialArea $industrialArea)
    {
        try {
            $industrialArea->delete();

            return redirect()->route('admin.industrial-areas.index')
                ->with('success', t('admin.industrial_area_deleted'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'));
        }
    }

    public function toggle(IndustrialArea $industrialArea)
    {
        try {
            $industrialArea->update(['is_active' => !$industrialArea->is_active]);
            
            $message = $industrialArea->is_active 
                ? t('admin.industrial_area_activated')
                : t('admin.industrial_area_deactivated');

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
                IndustrialArea::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}

