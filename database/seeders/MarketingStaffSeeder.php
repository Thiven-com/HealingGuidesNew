<?php

namespace Database\Seeders;

use App\Models\MarketingStaff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarketingStaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [

            [
                'name' => 'Rahul Sharma',
                'employee_code' => 'MKT001',
                'mobile' => '9876543210',
                'email' => 'rahul.marketing@example.com',
                'password' => Hash::make('123456'),
                'otp' => null,
                'dob' => '1995-05-15',
                'gender' => 'male',
                'photo' => null,
                'address' => 'Madhapur',
                'country' => 'India',
                'state' => 'Telangana',
                'city' => 'Hyderabad',
                'pincode' => '500081',
                'designation' => 'Marketing Executive',
                'joining_date' => '2026-01-10',
                'latitude' => 17.4483,
                'longitude' => 78.3915,
                'is_available' => 1,
                'last_login_at' => null,
                'status' => 1,
            ],

            [
                'name' => 'Arjun Reddy',
                'employee_code' => 'MKT002',
                'mobile' => '9876543211',
                'email' => 'arjun.marketing@example.com',
                'password' => Hash::make('123456'),
                'otp' => null,
                'dob' => '1994-08-20',
                'gender' => 'male',
                'photo' => null,
                'address' => 'Kukatpally',
                'country' => 'India',
                'state' => 'Telangana',
                'city' => 'Hyderabad',
                'pincode' => '500072',
                'designation' => 'Senior Marketing Executive',
                'joining_date' => '2026-02-01',
                'latitude' => 17.4948,
                'longitude' => 78.3996,
                'is_available' => 1,
                'last_login_at' => null,
                'status' => 1,
            ],

            [
                'name' => 'Priya Verma',
                'employee_code' => 'MKT003',
                'mobile' => '9876543212',
                'email' => 'priya.marketing@example.com',
                'password' => Hash::make('123456'),
                'otp' => null,
                'dob' => '1997-03-12',
                'gender' => 'female',
                'photo' => null,
                'address' => 'Gachibowli',
                'country' => 'India',
                'state' => 'Telangana',
                'city' => 'Hyderabad',
                'pincode' => '500032',
                'designation' => 'Marketing Executive',
                'joining_date' => '2026-03-15',
                'latitude' => 17.4401,
                'longitude' => 78.3489,
                'is_available' => 1,
                'last_login_at' => null,
                'status' => 1,
            ],

            [
                'name' => 'Sneha Rao',
                'employee_code' => 'MKT004',
                'mobile' => '9876543213',
                'email' => 'sneha.marketing@example.com',
                'password' => Hash::make('123456'),
                'otp' => null,
                'dob' => '1996-11-25',
                'gender' => 'female',
                'photo' => null,
                'address' => 'Ameerpet',
                'country' => 'India',
                'state' => 'Telangana',
                'city' => 'Hyderabad',
                'pincode' => '500016',
                'designation' => 'Field Marketing Executive',
                'joining_date' => '2026-04-01',
                'latitude' => 17.4375,
                'longitude' => 78.4483,
                'is_available' => 1,
                'last_login_at' => null,
                'status' => 1,
            ],

            [
                'name' => 'Kiran Kumar',
                'employee_code' => 'MKT005',
                'mobile' => '9876543214',
                'email' => 'kiran.marketing@example.com',
                'password' => Hash::make('123456'),
                'otp' => null,
                'dob' => '1993-07-08',
                'gender' => 'male',
                'photo' => null,
                'address' => 'Secunderabad',
                'country' => 'India',
                'state' => 'Telangana',
                'city' => 'Hyderabad',
                'pincode' => '500003',
                'designation' => 'Marketing Manager',
                'joining_date' => '2025-12-01',
                'latitude' => 17.4399,
                'longitude' => 78.4983,
                'is_available' => 1,
                'last_login_at' => null,
                'status' => 1,
            ],
        ];


        foreach ($staff as $item) {

            MarketingStaff::updateOrCreate(

                [
                    'employee_code' => $item['employee_code'],
                ],

                $item
            );
        }
    }
}