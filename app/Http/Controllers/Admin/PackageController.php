<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Benefit Types
    |--------------------------------------------------------------------------
    */

    private function benefitTypes()
    {
        return [

            'free_consultation' =>
                'Free Doctor Consultation',

            'free_video_consultation' =>
                'Free Video Consultation',

            'free_ambulance' =>
                'Free Ambulance',

            'free_home_visit' =>
                'Free Home Visit',

            'free_surgery_quote' =>
                'Free Surgery Quote',

            'free_medicine' =>
                'Free Medicine',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Package::withCount('benefits')
            ->with('activeBenefits');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'slug',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Packages
        |--------------------------------------------------------------------------
        */

        $packages = $query
            ->orderBy('display_order')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.packages.index',
            compact('packages')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $benefitTypes = $this->benefitTypes();

        return view(
            'admin.packages.create',
            compact('benefitTypes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $benefitTypes = $this->benefitTypes();


        $request->validate([

            'name' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'price' =>
                'required|numeric|min:0',

            'duration_days' =>
                'required|integer|min:1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',

            'benefits' =>
                'nullable|array',

            'benefits.*.type' =>
                'required_with:benefits|string|in:' .
                implode(
                    ',',
                    array_keys($benefitTypes)
                ),

            'benefits.*.quantity' =>
                'required_with:benefits|integer|min:0',

            'benefits.*.description' =>
                'nullable|string',

            'benefits.*.status' =>
                'nullable|boolean',

        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            $image = null;

            if ($request->hasFile('image')) {

                $image = $request
                    ->file('image')
                    ->store(
                        'packages',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Package
            |--------------------------------------------------------------------------
            */

            $package = Package::create([

                'name' =>
                    $request->name,

                'slug' =>
                    Str::slug($request->name),

                'description' =>
                    $request->description,

                'price' =>
                    $request->price,

                'duration_days' =>
                    $request->duration_days,

                'image' =>
                    $image,

                'display_order' =>
                    $request->display_order ?? 0,

                'status' =>
                    $request->boolean('status', true),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Benefits
            |--------------------------------------------------------------------------
            */

            if ($request->filled('benefits')) {

                foreach (
                    $request->benefits
                    as $index => $benefit
                ) {

                    $type =
                        $benefit['type'];

                    /*
                    |--------------------------------------------------------------------------
                    | Ignore duplicate benefit types
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $package->benefits()
                            ->where(
                                'benefit_type',
                                $type
                            )
                            ->exists()
                    ) {
                        continue;
                    }


                    PackageBenefit::create([

                        'package_id' =>
                            $package->id,

                        'benefit_type' =>
                            $type,

                        'benefit_name' =>
                            $benefitTypes[$type],

                        'quantity' =>
                            $benefit['quantity'] ?? 1,

                        'description' =>
                            $benefit['description'] ?? null,

                        'display_order' =>
                            $index,

                        'status' =>
                            isset($benefit['status'])
                                ? (bool) $benefit['status']
                                : true,

                    ]);
                }
            }


            DB::commit();


            return redirect()
                ->route('admin.packages.index')
                ->with(
                    'success',
                    'Package created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $package = Package::with([
            'benefits'
        ])->findOrFail($id);


        return view(
            'admin.packages.show',
            compact('package')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $package = Package::with([
            'benefits'
        ])->findOrFail($id);

        $benefitTypes =
            $this->benefitTypes();


        return view(
            'admin.packages.edit',
            compact(
                'package',
                'benefitTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $package =
            Package::findOrFail($id);

        $benefitTypes =
            $this->benefitTypes();


        $request->validate([

            'name' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'price' =>
                'required|numeric|min:0',

            'duration_days' =>
                'required|integer|min:1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',

            'benefits' =>
                'nullable|array',

            'benefits.*.type' =>
                'required_with:benefits|string|in:' .
                implode(
                    ',',
                    array_keys($benefitTypes)
                ),

            'benefits.*.quantity' =>
                'required_with:benefits|integer|min:0',

            'benefits.*.description' =>
                'nullable|string',

            'benefits.*.status' =>
                'nullable|boolean',

        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                $package->image =
                    $request
                        ->file('image')
                        ->store(
                            'packages',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Package
            |--------------------------------------------------------------------------
            */

            $package->name =
                $request->name;

            $package->slug =
                Str::slug(
                    $request->name
                );

            $package->description =
                $request->description;

            $package->price =
                $request->price;

            $package->duration_days =
                $request->duration_days;

            $package->display_order =
                $request->display_order ?? 0;

            $package->status =
                $request->boolean(
                    'status',
                    true
                );

            $package->save();


            /*
            |--------------------------------------------------------------------------
            | Existing Benefits
            |--------------------------------------------------------------------------
            */

            $submittedBenefitIds = [];


            if ($request->filled('benefits')) {

                foreach (
                    $request->benefits
                    as $index => $benefit
                ) {

                    $type =
                        $benefit['type'];


                    /*
                    |--------------------------------------------------------------------------
                    | Find Existing Benefit
                    |--------------------------------------------------------------------------
                    */

                    $packageBenefit =
                        $package->benefits()
                            ->where(
                                'benefit_type',
                                $type
                            )
                            ->first();


                    if (!$packageBenefit) {

                        $packageBenefit =
                            new PackageBenefit();

                        $packageBenefit->package_id =
                            $package->id;

                        $packageBenefit->benefit_type =
                            $type;
                    }


                    $packageBenefit->benefit_name =
                        $benefitTypes[$type];

                    $packageBenefit->quantity =
                        $benefit['quantity'] ?? 1;

                    $packageBenefit->description =
                        $benefit['description'] ?? null;

                    $packageBenefit->display_order =
                        $index;

                    $packageBenefit->status =
                        isset($benefit['status'])
                            ? (bool) $benefit['status']
                            : true;

                    $packageBenefit->save();


                    $submittedBenefitIds[] =
                        $packageBenefit->id;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Removed Benefits
            |--------------------------------------------------------------------------
            */

            $deleteQuery =
                $package->benefits();

            if (!empty($submittedBenefitIds)) {

                $deleteQuery->whereNotIn(
                    'id',
                    $submittedBenefitIds
                );

            }

            $deleteQuery->delete();


            DB::commit();


            return redirect()
                ->route(
                    'admin.packages.index'
                )
                ->with(
                    'success',
                    'Package updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $package =
            Package::findOrFail($id);

        $package->status =
            !$package->status;

        $package->save();


        return redirect()
            ->back()
            ->with(
                'success',
                'Package status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $package =
            Package::findOrFail($id);

        $package->delete();


        return redirect()
            ->route(
                'admin.packages.index'
            )
            ->with(
                'success',
                'Package deleted successfully.'
            );
    }
}