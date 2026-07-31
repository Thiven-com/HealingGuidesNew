<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DoctorSchedule::truncate();

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        foreach (Doctor::all() as $doctor) {

            foreach ($days as $day) {

                DoctorSchedule::create([

                    'doctor_id' => $doctor->id,

                    'day_of_week' => $day,

                    'available_from' => $doctor->available_from ?? '09:00:00',

                    'available_to' => $doctor->available_to ?? '17:00:00',

                    'slot_duration' => 15,

                    'consultation_type' => 'Clinic Visit',

                    'status' => 1,

                ]);

            }
        }

        $this->command->info('Doctor schedules created successfully.');
    }
}