<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;

class HospitalTypeController extends Controller
{
    public function index()
    {
        $hospitalTypes = [
            [
                'id' => 1,
                'name' => 'General Hospital',
            ],
            [
                'id' => 2,
                'name' => 'Multi Speciality',
            ],
            [
                'id' => 3,
                'name' => 'Super Speciality',
            ],
            [
                'id' => 4,
                'name' => 'Government Hospital',
            ],
            [
                'id' => 5,
                'name' => 'Private Hospital',
            ],
            [
                'id' => 6,
                'name' => 'Medical College Hospital',
            ],
            [
                'id' => 7,
                'name' => 'Clinic',
            ],
            [
                'id' => 8,
                'name' => 'Diagnostic Center',
            ],
            [
                'id' => 9,
                'name' => 'Nursing Home',
            ],
        ];

        return response()->json([
            'success' => 1,
            'data' => $hospitalTypes,
        ]);
    }
}
