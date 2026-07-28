<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\FamilyMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FamilyMemberSeeder extends Seeder
{
    public function run(): void
    {
        FamilyMember::truncate();

        $path = database_path('seed-data/family_members.json');

        if (!File::exists($path)) {
            $this->command->error('family_members.json not found.');
            return;
        }

        $members = json_decode(File::get($path), true);

        foreach ($members as $member) {

            $customer = Customer::where('mobile', $member['customer_mobile'])->first();

            if (!$customer) {
                $this->command->warn("Customer not found: {$member['customer_mobile']}");
                continue;
            }

            FamilyMember::create([

                'customer_id' => $customer->id,

                'name' => $member['name'],

                'relationship' => $member['relationship'],

                'gender' => $member['gender'],

                'dob' => $member['dob'],

                'age' => $member['age'],

                'blood_group' => $member['blood_group'],

                'height' => $member['height'],

                'weight' => $member['weight'],

                'occupation' => $member['occupation'],

                'aadhaar_no' => $member['aadhaar_no'],

                'photo' => $member['photo'],

                'status' => $member['status'],

            ]);
        }

        $this->command->info(count($members) . ' Family Members Imported Successfully.');
    }
}