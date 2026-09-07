<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function create(Request $request)
    {
        $doctors = Doctor::where('status', 1)
            ->orderBy('doctor_name')
            ->get();

        $selectedDoctor = null;

        if ($request->filled('doctor_id')) {
            $selectedDoctor = Doctor::find($request->doctor_id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Doctor schedule data fetched successfully',
            'data' => [
                'doctors' => $doctors,
                'selected_doctor' => $selectedDoctor,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:50',
            'available_from' => 'required|date_format:H:i',
            'available_to' => 'required|date_format:H:i|after:available_from',
            'slot_duration' => 'required|integer|min:1',
            'consultation_type' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        DoctorSchedule::create($validated);

        return redirect()
            ->route('hospital.doctors.show', $validated['doctor_id'])
            ->with('success', 'Doctor schedule added successfully.');
    }

    public function edit(DoctorSchedule $doctorSchedule)
    {
        $doctors = Doctor::where('status', 1)
            ->orderBy('doctor_name')
            ->get();

        return view(
            'hospital.doctor-schedules.edit',
            compact('doctorSchedule', 'doctors')
        );
    }

    public function update(Request $request, DoctorSchedule $doctorSchedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:50',
            'available_from' => 'required|date_format:H:i',
            'available_to' => 'required|date_format:H:i|after:available_from',
            'slot_duration' => 'required|integer|min:1',
            'consultation_type' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $doctorSchedule->update($validated);

        return redirect()
            ->route('hospital.doctors.show', $validated['doctor_id'])
            ->with('success', 'Doctor schedule updated successfully.');
    }

    public function destroy(DoctorSchedule $doctorSchedule)
    {
        $doctorId = $doctorSchedule->doctor_id;

        $doctorSchedule->delete();

        return redirect()
            ->route('hospital.doctors.show', $doctorId)
            ->with('success', 'Doctor schedule deleted successfully.');
    }
}
