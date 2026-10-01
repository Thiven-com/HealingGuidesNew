<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookAdmission;
use Illuminate\Http\Request;

class BookAdmissionController extends Controller
{
     /**
     * Display all admission requests.
     */
    public function index(Request $request)
    {
        $query = BookAdmission::with([
            'customer',
            'familyMember',
            'preferredDoctor'
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('admission_type', 'LIKE', "%{$search}%")
                    ->orWhere('surgery_procedure', 'LIKE', "%{$search}%")
                    ->orWhere('additional_information', 'LIKE', "%{$search}%")

                    ->orWhereHas('familyMember', function ($familyQuery) use ($search) {
                        $familyQuery->where('name', 'LIKE', "%{$search}%");
                    })

                    ->orWhereHas('preferredDoctor', function ($doctorQuery) use ($search) {
                        $doctorQuery->where('doctor_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Admission Type filter
        if ($request->filled('admission_type')) {
            $query->where(
                'admission_type',
                $request->admission_type
            );
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookAdmissions = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.book-admissions.index',
            compact('bookAdmissions')
        );
    }

    /**
     * Display single admission request.
     */
    public function show($id)
    {
        $bookAdmission = BookAdmission::with([
            'customer',
            'familyMember',
            'preferredDoctor'
        ])->findOrFail($id);

        return view(
            'admin.book-admissions.show',
            compact('bookAdmission')
        );
    }

    /**
     * Update admission status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $bookAdmission = BookAdmission::findOrFail($id);

        $bookAdmission->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Admission status updated successfully.');
    }
}
