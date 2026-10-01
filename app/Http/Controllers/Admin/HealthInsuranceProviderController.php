<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthInsuranceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HealthInsuranceProviderController extends Controller
{
    /**
     * Display all health insurance providers.
     */
    public function index()
    {
        $providers = HealthInsuranceProvider::orderBy('display_order')
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.health_insurance_providers.index',
            compact('providers')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.health_insurance_providers.create'
        );
    }


    /**
     * Store new provider.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|string|max:100',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_insurance_providers,slug',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'description' => 'nullable|string',

            'website_url' => 'nullable|string|max:500',

            'support_email' => 'nullable|email|max:255',

            'support_phone' => 'nullable|string|max:30',

            'display_order' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);


        $data = $request->except('logo');


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $folder = public_path(
                'uploads/health-insurance-providers'
            );

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }


            $file = $request->file('logo');

            $fileName =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $folder,
                $fileName
            );


            $data['logo'] =
                'uploads/health-insurance-providers/' . $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $data['display_order'] =
            $request->input('display_order', 0);

        $data['status'] =
            $request->boolean('status', true);


        /*
        |--------------------------------------------------------------------------
        | Create Provider
        |--------------------------------------------------------------------------
        */

        HealthInsuranceProvider::create($data);


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider created successfully.'
            );
    }


    /**
     * Display single provider.
     */
    public function show($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        return view(
            'admin.health_insurance_providers.show',
            compact('provider')
        );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        return view(
            'admin.health_insurance_providers.edit',
            compact('provider')
        );
    }


    /**
     * Update provider.
     */
    public function update(Request $request, $id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|string|max:100',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_insurance_providers,slug,' . $provider->id,
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'description' => 'nullable|string',

            'website_url' => 'nullable|string|max:500',

            'support_email' => 'nullable|email|max:255',

            'support_phone' => 'nullable|string|max:30',

            'display_order' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);


        $data = $request->except('logo');


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $folder = public_path(
                'uploads/health-insurance-providers'
            );

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Logo
            |--------------------------------------------------------------------------
            */

            if (
                !empty($provider->logo) &&
                File::exists(
                    public_path($provider->logo)
                )
            ) {
                File::delete(
                    public_path($provider->logo)
                );
            }


            $file = $request->file('logo');

            $fileName =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $folder,
                $fileName
            );


            $data['logo'] =
                'uploads/health-insurance-providers/' . $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $data['display_order'] =
            $request->input('display_order', 0);

        $data['status'] =
            $request->boolean('status', false);


        /*
        |--------------------------------------------------------------------------
        | Update Provider
        |--------------------------------------------------------------------------
        */

        $provider->update($data);


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider updated successfully.'
            );
    }


    /**
     * Delete provider.
     */
    public function destroy($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Logo
        |--------------------------------------------------------------------------
        */

        if (
            !empty($provider->logo) &&
            File::exists(
                public_path($provider->logo)
            )
        ) {
            File::delete(
                public_path($provider->logo)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Provider
        |--------------------------------------------------------------------------
        */

        $provider->delete();


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider deleted successfully.'
            );
    }


    /**
     * Toggle provider status.
     */
    public function toggleStatus($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        $provider->status = !$provider->status;

        $provider->save();


        return redirect()
            ->back()
            ->with(
                'success',
                'Provider status updated successfully.'
            );
    }
}