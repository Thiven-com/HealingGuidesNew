<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::truncate();

        $path = database_path('seed-data/customers.json');

        if (!File::exists($path)) {
            $this->command->error('customers.json not found.');
            return;
        }

        $customers = json_decode(File::get($path), true);

        foreach ($customers as $customer) {

            Customer::create([

                'customer_code' => $customer['customer_code'],

                'name' => $customer['name'],

                'mobile' => $customer['mobile'],

                'alternate_mobile' => $customer['alternate_mobile'],

                'email' => $customer['email'],

                'password' => Hash::make($customer['password']),

                'otp' => $customer['otp'],

                'gender' => $customer['gender'],

                'dob' => $customer['dob'],

                'age' => $customer['age'],

                'blood_group' => $customer['blood_group'],

                'height' => $customer['height'],

                'weight' => $customer['weight'],

                'occupation' => $customer['occupation'],

                'aadhaar_no' => $customer['aadhaar_no'],

                'photo' => $customer['photo'],

                'address' => $customer['address'],

                'country' => $customer['country'],

                'state' => $customer['state'],

                'city' => $customer['city'],

                'pincode' => $customer['pincode'],

                'emergency_contact_name' => $customer['emergency_contact_name'],

                'emergency_contact_mobile' => $customer['emergency_contact_mobile'],

                'is_verified' => $customer['is_verified'],

                'status' => $customer['status'],

            ]);
        }

        $this->command->info(count($customers) . ' Customers Imported Successfully.');
    }
}