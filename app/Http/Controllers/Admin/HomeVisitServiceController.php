<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitService;
use App\Models\HomeVisitServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class HomeVisitServiceController extends Controller
{
     /**
     * Display Home Visit Services
     */
    public function index()
    {
        $categories = HomeVisitServiceCategory::orderBy('name', 'asc')->get();

        $services = HomeVisitService::with('category')
            ->latest()
            ->paginate(10);

        return view(
            'admin.home_visit_services.index',
            compact('categories', 'services')
        );
    }

    /**
     * Store Home Visit Service
     */
    public function store(Request $request)
    {
        $request->validate([
            'home_visit_service_categories_id' => [
                'required',
                'exists:home_visit_service_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:home_visit_services,slug',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // Actual price is a number
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            // Price per is Hour or Day
            'price_per' => [
                'required',
                'in:hour,day',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */
        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('home_visit_services', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Service
        |--------------------------------------------------------------------------
        */
        HomeVisitService::create([
            'home_visit_service_categories_id' =>
                $request->home_visit_service_categories_id,

            'name' => $request->name,

            'slug' => $slug,

            'image' => $imagePath,

            'price' => $request->price,

            'price_per' => $request->price_per,

            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.home-visit-services.index')
            ->with('success', 'Home visit service added successfully.');
    }

    /**
     * Show Edit Data
     */
    public function edit($id)
    {
        $service = HomeVisitService::with('category')
            ->findOrFail($id);

        $categories = HomeVisitServiceCategory::orderBy('name', 'asc')
            ->get();

        return view(
            'admin.home_visit_services.edit',
            compact('service', 'categories')
        );
    }

    /**
     * Update Home Visit Service
     */
    public function update(Request $request, $id)
    {
        $service = HomeVisitService::findOrFail($id);

        $request->validate([
            'home_visit_service_categories_id' => [
                'required',
                'exists:home_visit_service_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:home_visit_services,slug,' . $id,
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // Actual price is a number
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            // Price per is Hour or Day
            'price_per' => [
                'required',
                'in:hour,day',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */
        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        /*
        |--------------------------------------------------------------------------
        | Update Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->image = $request->file('image')
                ->store('home_visit_services', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Service
        |--------------------------------------------------------------------------
        */
        $service->home_visit_service_categories_id =
            $request->home_visit_service_categories_id;

        $service->name =
            $request->name;

        $service->slug =
            $slug;

        $service->price =
            $request->price;

        $service->price_per =
            $request->price_per;

        $service->description =
            $request->description;

        $service->save();

        return redirect()
            ->route('admin.home-visit-services.index')
            ->with('success', 'Home visit service updated successfully.');
    }

    /**
     * Delete Home Visit Service
     */
    public function destroy($id)
    {
        $service = HomeVisitService::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Service
        |--------------------------------------------------------------------------
        */
        $service->delete();

        return redirect()
            ->route('admin.home-visit-services.index')
            ->with('success', 'Home visit service deleted successfully.');
    }
}
