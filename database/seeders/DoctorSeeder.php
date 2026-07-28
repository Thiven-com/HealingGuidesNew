<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\HospitalSpecialization;
use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        Doctor::truncate();

        $path = database_path('seed-data/doctors.json');

        if (!File::exists($path)) {
            $this->command->error('doctors.json not found.');
            return;
        }

        $doctors = json_decode(File::get($path), true);

        foreach ($doctors as $doctor) {

            $hospital = Hospital::where(
                'hospital_code',
                $doctor['hospital_code']
            )->first();

            if (!$hospital) {
                $this->command->warn("Hospital not found: {$doctor['hospital_code']}");
                continue;
            }

            $specialization = Specialization::where(
                'specialization_name',
                $doctor['specialization']
            )->first();

            if (!$specialization) {
                $this->command->warn("Specialization not found: {$doctor['specialization']}");
                continue;
            }

            $mapping = HospitalSpecialization::where([
                'hospital_id' => $hospital->id,
                'specialization_id' => $specialization->id,
            ])->first();

            if (!$mapping) {
                $this->command->warn(
                    "Mapping not found for {$doctor['hospital_code']} - {$doctor['specialization']}"
                );
                continue;
            }

            Doctor::create([

                'hospital_id' => $hospital->id,

                'hospital_specialization_id' => $mapping->id,

                'doctor_name' => $doctor['doctor_name'],

                'slug' => Str::slug($doctor['doctor_name']),

                'doctor_code' => $doctor['doctor_code'],

                'qualification' => $doctor['qualification'],

                'designation' => $doctor['designation'],

                'experience' => $doctor['experience'],

                'consultation_fee' => $doctor['consultation_fee'],

                'email' => $doctor['email'],

                'mobile' => $doctor['mobile'],

                'dob' => $doctor['dob'],

                'gender' => $doctor['gender'],

                'blood_group' => $doctor['blood_group'],

                'photo' => $doctor['photo'],

                'address' => $doctor['address'],

                'about' => $doctor['about'],

                'available_from' => $doctor['available_from'],

                'available_to' => $doctor['available_to'],

                'status' => $doctor['status'],
            ]);
        }

        $this->command->info(count($doctors) . ' Doctors Imported Successfully.');
    }
}