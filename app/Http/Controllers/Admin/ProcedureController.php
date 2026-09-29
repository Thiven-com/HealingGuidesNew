<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use App\Models\ProcedureBenefit;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcedureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Procedure::with([
            'specialization',
            'benefits',
        ])
            ->withCount([
                'benefits',
                'doctors',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Specialization Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('specialization_id')) {

            $query->where(
                'specialization_id',
                $request->specialization_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->has('status') && $request->status !== '') {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Order
        |--------------------------------------------------------------------------
        */

        $procedures = $query
            ->orderBy('display_order')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        return view(
            'admin.procedures.index',
            compact(
                'procedures',
                'specializations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        return view(
            'admin.procedures.create',
            compact('specializations')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'specialization_id' =>
                'nullable|exists:specializations,id',

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:procedures,slug',

            'short_description' =>
                'nullable|string|max:500',

            'description' =>
                'nullable|string',

            'about' =>
                'nullable|string',

            'duration' =>
                'nullable|string|max:255',

            'hospital_stay' =>
                'nullable|string|max:255',

            'recovery' =>
                'nullable|string|max:255',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'icon' =>
                'nullable|string|max:255',

            'price' =>
                'nullable|numeric|min:0',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|boolean',

            /*
            |--------------------------------------------------------------------------
            | Benefits
            |--------------------------------------------------------------------------
            */

            'benefits' =>
                'nullable|array',

            'benefits.*.title' =>
                'required|string|max:255',

            'benefits.*.description' =>
                'nullable|string',

            'benefits.*.icon' =>
                'nullable|string|max:255',

            'benefits.*.display_order' =>
                'nullable|integer|min:0',

            'benefits.*.status' =>
                'nullable|boolean',
        ]);


        DB::transaction(function () use ($request, $validated) {

            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */

            $slug = $validated['slug']
                ?? Str::slug($validated['name']);


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Slug
            |--------------------------------------------------------------------------
            */

            $originalSlug = $slug;
            $counter = 1;

            while (
                Procedure::where('slug', $slug)->exists()
            ) {

                $slug =
                    $originalSlug . '-' . $counter;

                $counter++;
            }


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            $image = null;

            if ($request->hasFile('image')) {

                $image =
                    $request->file('image')
                        ->store(
                            'procedures',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Procedure
            |--------------------------------------------------------------------------
            */

            $procedure = Procedure::create([

                'specialization_id' =>
                    $validated['specialization_id']
                    ?? null,

                'name' =>
                    $validated['name'],

                'slug' =>
                    $slug,

                'short_description' =>
                    $validated['short_description']
                    ?? null,

                'description' =>
                    $validated['description']
                    ?? null,

                'about' =>
                    $validated['about']
                    ?? null,

                'duration' =>
                    $validated['duration']
                    ?? null,

                'hospital_stay' =>
                    $validated['hospital_stay']
                    ?? null,

                'recovery' =>
                    $validated['recovery']
                    ?? null,

                'image' =>
                    $image,

                'icon' =>
                    $validated['icon']
                    ?? null,

                'price' =>
                    $validated['price']
                    ?? 0,

                'display_order' =>
                    $validated['display_order']
                    ?? 0,

                'status' =>
                    $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Store Benefits
            |--------------------------------------------------------------------------
            */

            $this->syncBenefits(
                $procedure,
                $validated['benefits'] ?? []
            );
        });


        return redirect()
            ->route('admin.procedures.index')
            ->with(
                'success',
                'Procedure created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $procedure = Procedure::with([
            'specialization',
            'benefits' => function ($query) {

                $query->orderBy('display_order');

            },
            'doctors',
        ])
            ->withCount([
                'benefits',
                'doctors',
            ])
            ->findOrFail($id);


        return view(
            'admin.procedures.show',
            compact('procedure')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $procedure = Procedure::with([
            'benefits' => function ($query) {

                $query->orderBy('display_order');

            },
        ])->findOrFail($id);


        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();


        return view(
            'admin.procedures.edit',
            compact(
                'procedure',
                'specializations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $procedure = Procedure::findOrFail($id);


        $validated = $request->validate([

            'specialization_id' =>
                'nullable|exists:specializations,id',

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:procedures,slug,' .
                $procedure->id,

            'short_description' =>
                'nullable|string|max:500',

            'description' =>
                'nullable|string',

            'about' =>
                'nullable|string',

            'duration' =>
                'nullable|string|max:255',

            'hospital_stay' =>
                'nullable|string|max:255',

            'recovery' =>
                'nullable|string|max:255',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'icon' =>
                'nullable|string|max:255',

            'price' =>
                'nullable|numeric|min:0',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|boolean',

            /*
            |--------------------------------------------------------------------------
            | Benefits
            |--------------------------------------------------------------------------
            */

            'benefits' =>
                'nullable|array',

            'benefits.*.id' =>
                'nullable|integer|exists:procedure_benefits,id',

            'benefits.*.title' =>
                'required|string|max:255',

            'benefits.*.description' =>
                'nullable|string',

            'benefits.*.icon' =>
                'nullable|string|max:255',

            'benefits.*.display_order' =>
                'nullable|integer|min:0',

            'benefits.*.status' =>
                'nullable|boolean',
        ]);


        DB::transaction(function () use ($request, $validated, $procedure) {

            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */

            $slug =
                $validated['slug']
                ?? Str::slug($validated['name']);


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            $image = $procedure->image;

            if ($request->hasFile('image')) {

                if (
                    $procedure->image &&
                    Storage::disk('public')
                        ->exists($procedure->image)
                ) {

                    Storage::disk('public')
                        ->delete(
                            $procedure->image
                        );
                }


                $image =
                    $request->file('image')
                        ->store(
                            'procedures',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Procedure
            |--------------------------------------------------------------------------
            */

            $procedure->update([

                'specialization_id' =>
                    $validated['specialization_id']
                    ?? null,

                'name' =>
                    $validated['name'],

                'slug' =>
                    $slug,

                'short_description' =>
                    $validated['short_description']
                    ?? null,

                'description' =>
                    $validated['description']
                    ?? null,

                'about' =>
                    $validated['about']
                    ?? null,

                'duration' =>
                    $validated['duration']
                    ?? null,

                'hospital_stay' =>
                    $validated['hospital_stay']
                    ?? null,

                'recovery' =>
                    $validated['recovery']
                    ?? null,

                'image' =>
                    $image,

                'icon' =>
                    $validated['icon']
                    ?? null,

                'price' =>
                    $validated['price']
                    ?? 0,

                'display_order' =>
                    $validated['display_order']
                    ?? 0,

                'status' =>
                    $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Sync Benefits
            |--------------------------------------------------------------------------
            */

            $this->syncBenefits(
                $procedure,
                $validated['benefits'] ?? []
            );
        });


        return redirect()
            ->route(
                'admin.procedures.edit',
                $procedure->id
            )
            ->with(
                'success',
                'Procedure updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SYNC BENEFITS
    |--------------------------------------------------------------------------
    */

    private function syncBenefits(
        Procedure $procedure,
        array $benefits
    ) {

        /*
        |--------------------------------------------------------------------------
        | Existing IDs From Request
        |--------------------------------------------------------------------------
        */

        $submittedIds = collect($benefits)
            ->pluck('id')
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Delete Removed Benefits
        |--------------------------------------------------------------------------
        */

        ProcedureBenefit::where(
            'procedure_id',
            $procedure->id
        )
            ->when(
                count($submittedIds) > 0,
                function ($query) use ($submittedIds) {

                    $query->whereNotIn(
                        'id',
                        $submittedIds
                    );

                },
                function ($query) {

                    $query->whereNotNull('id');

                }
            )
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | Create / Update Benefits
        |--------------------------------------------------------------------------
        */

        foreach ($benefits as $index => $benefit) {

            $benefitId =
                $benefit['id'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Update Existing Benefit
            |--------------------------------------------------------------------------
            */

            if ($benefitId) {

                $procedureBenefit =
                    ProcedureBenefit::where(
                        'id',
                        $benefitId
                    )
                        ->where(
                            'procedure_id',
                            $procedure->id
                        )
                        ->first();


                if ($procedureBenefit) {

                    $procedureBenefit->update([

                        'title' =>
                            $benefit['title'],

                        'description' =>
                            $benefit['description']
                            ?? null,

                        'icon' =>
                            $benefit['icon']
                            ?? null,

                        'display_order' =>
                            $benefit['display_order']
                            ?? $index,

                        'status' =>
                            $benefit['status']
                            ?? 1,
                    ]);
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | Create New Benefit
                |--------------------------------------------------------------------------
                */

                ProcedureBenefit::create([

                    'procedure_id' =>
                        $procedure->id,

                    'title' =>
                        $benefit['title'],

                    'description' =>
                        $benefit['description']
                        ?? null,

                    'icon' =>
                        $benefit['icon']
                        ?? null,

                    'display_order' =>
                        $benefit['display_order']
                        ?? $index,

                    'status' =>
                        $benefit['status']
                        ?? 1,
                ]);
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $procedure = Procedure::findOrFail($id);


        DB::transaction(function () use ($procedure) {

            /*
            |--------------------------------------------------------------------------
            | Delete Image
            |--------------------------------------------------------------------------
            */

            if (
                $procedure->image &&
                Storage::disk('public')
                    ->exists($procedure->image)
            ) {

                Storage::disk('public')
                    ->delete(
                        $procedure->image
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Benefits
            |--------------------------------------------------------------------------
            */

            ProcedureBenefit::where(
                'procedure_id',
                $procedure->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Doctor Assignments
            |--------------------------------------------------------------------------
            */

            $procedure->doctors()->detach();


            /*
            |--------------------------------------------------------------------------
            | Procedure
            |--------------------------------------------------------------------------
            */

            $procedure->delete();
        });


        return redirect()
            ->route('admin.procedures.index')
            ->with(
                'success',
                'Procedure deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $procedure = Procedure::findOrFail($id);

        $procedure->update([
            'status' => !$procedure->status,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => 1,
                'message' => $procedure->status
                    ? 'Procedure activated successfully.'
                    : 'Procedure deactivated successfully.',
                'status' => $procedure->status,
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                $procedure->status
                ? 'Procedure activated successfully.'
                : 'Procedure deactivated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BENEFIT
    |--------------------------------------------------------------------------
    */

    public function destroyBenefit($id)
    {
        $benefit =
            ProcedureBenefit::findOrFail($id);


        $benefit->delete();


        return redirect()
            ->back()
            ->with(
                'success',
                'Procedure benefit deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BENEFIT STATUS
    |--------------------------------------------------------------------------
    */

    public function benefitStatus($id)
    {
        $benefit =
            ProcedureBenefit::findOrFail($id);


        $benefit->update([

            'status' =>
                !$benefit->status,

        ]);


        return redirect()
            ->back()
            ->with(
                'success',
                $benefit->status
                ? 'Benefit activated successfully.'
                : 'Benefit deactivated successfully.'
            );
    }
}