<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthInsurancePlan;
use App\Models\HealthInsuranceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HealthInsurancePlanController extends Controller
{
    public function store(Request $request, HealthInsuranceProvider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',

            'policy_term_years' => 'nullable|integer|min:1',

            'price' => 'required|numeric|min:0',
            'actual_price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'tax_price' => 'required|numeric|min:0',

            'minimum_entry_age' => 'nullable|integer|min:0',
            'maximum_entry_age' => 'nullable|integer|min:0',

            'key_benefits' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if (
            isset($validated['minimum_entry_age'], $validated['maximum_entry_age'])
            && $validated['maximum_entry_age'] < $validated['minimum_entry_age']
        ) {
            throw ValidationException::withMessages([
                'maximum_entry_age' => 'Maximum entry age must be greater than or equal to minimum entry age.',
            ]);
        }
        // Upload plan image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('health-insurance/plans', 'public');
        }

        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $provider->plans()->create($validated);

        return redirect()
            ->route('admin.health-insurance-providers.show', $provider->id)
            ->with('success', 'Health insurance plan added successfully.');
    }


    public function updatePlan(Request $request, $provider, $plan)
    {
        $provider = HealthInsuranceProvider::findOrFail($provider);

        // Find the plan through this provider's relationship.
        // This ensures the plan belongs to the selected provider.
        $plan = $provider->plans()->findOrFail($plan);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'policy_term_years' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'actual_price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'tax_price' => 'required|numeric|min:0',
            'minimum_entry_age' => 'nullable|integer|min:0',
            'maximum_entry_age' => 'nullable|integer|min:0',
            'key_benefits' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if (
            isset($validated['minimum_entry_age'], $validated['maximum_entry_age']) &&
            $validated['maximum_entry_age'] < $validated['minimum_entry_age']
        ) {
            throw ValidationException::withMessages([
                'maximum_entry_age' =>
                    'Maximum entry age must be greater than or equal to minimum entry age.',
            ]);
        }

        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);
        // Upload new image only if one was selected
        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('health-insurance/plans', 'public');

            // Delete old image after successfully storing the new one
            if ($plan->image) {
                Storage::disk('public')->delete($plan->image);
            }

            $validated['image'] = $newImage;
        }

        $plan->update($validated);

        return redirect()
            ->route('admin.health-insurance-providers.show', $provider->id)
            ->with('success', 'Health insurance plan updated successfully.');
    }




    public function destroyPlan($provider, $plan)
    {
        $provider = HealthInsuranceProvider::findOrFail($provider);

        // Find the plan belonging to this provider
        $plan = $provider->plans()->findOrFail($plan);

        $plan->delete();

        return redirect()
            ->route('admin.health-insurance-providers.show', $provider->id)
            ->with('success', 'Health insurance plan deleted successfully.');
    }

}
