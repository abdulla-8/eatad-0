<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;
use Illuminate\Support\Facades\Storage;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::ordered()->get();
        return view('admin.specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('admin.specializations.addedit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_name_ar' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $data = $request->only(['brand_name', 'brand_name_ar', 'is_active', 'sort_order']);
            
            // Set default sort order if not provided
            if (!$request->has('sort_order') || $request->sort_order == 0) {
                $data['sort_order'] = Specialization::max('sort_order') + 1;
            }

            // Handle checkbox value
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('brands', $filename, 'public');
                $data['image'] = $path;
            }

            Specialization::create($data);

            return redirect()->route('admin.specializations.index')
                ->with('success', t('admin.specialization_created'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.addedit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_name_ar' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $data = $request->only(['brand_name', 'brand_name_ar', 'sort_order']);

            // Handle checkbox value
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($specialization->image && Storage::disk('public')->exists($specialization->image)) {
                    Storage::disk('public')->delete($specialization->image);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('brands', $filename, 'public');
                $data['image'] = $path;
            }

            $specialization->update($data);

            return redirect()->route('admin.specializations.index')
                ->with('success', t('admin.specialization_updated'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'))
                ->withInput();
        }
    }

    public function destroy(Specialization $specialization)
    {
        try {
            // Delete image if exists
            if ($specialization->image && Storage::disk('public')->exists($specialization->image)) {
                Storage::disk('public')->delete($specialization->image);
            }

            $specialization->delete();

            return redirect()->route('admin.specializations.index')
                ->with('success', t('admin.specialization_deleted'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', t('admin.error_occurred'));
        }
    }

    public function toggle(Specialization $specialization)
    {
        try {
            $specialization->update(['is_active' => !$specialization->is_active]);
            
            $message = $specialization->is_active 
                ? t('admin.specialization_activated')
                : t('admin.specialization_deactivated');

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
                Specialization::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}