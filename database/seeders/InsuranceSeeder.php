<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use Carbon\Carbon;

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        Insurance::create([
            'customer_id' => 1,

            'insurance_provider' => 'Star Health Insurance',

            'policy_number' => 'STAR2026001001',

            'policy_type' => 'Health Insurance',

            'policy_holder_name' => 'Test Customer',

            'member_id' => 'STAR-MEM-1001',

            'coverage_amount' => 500000,

            'start_date' => $today->copy()->subMonths(2),

            'expiry_date' => $today->copy()->addMonths(10),

            'document' => 'insurances/sample-health-insurance.pdf',

            'notes' => 'Family health insurance policy.',

            'status' => 'active',
        ]);

        Insurance::create([
            'customer_id' => 1,

            'insurance_provider' => 'HDFC ERGO',

            'policy_number' => 'HDFC2026002002',

            'policy_type' => 'Individual Health Insurance',

            'policy_holder_name' => 'Test Customer',

            'member_id' => 'HDFC-MEM-2002',

            'coverage_amount' => 1000000,

            'start_date' => $today->copy()->subMonths(5),

            'expiry_date' => $today->copy()->addMonths(7),

            'document' => 'insurances/sample-hdfc-insurance.pdf',

            'notes' => 'Individual health insurance policy.',

            'status' => 'active',
        ]);

        Insurance::create([
            'customer_id' => 1,

            'insurance_provider' => 'ICICI Lombard',

            'policy_number' => 'ICICI2025003003',

            'policy_type' => 'Health Insurance',

            'policy_holder_name' => 'Test Customer',

            'member_id' => 'ICICI-MEM-3003',

            'coverage_amount' => 750000,

            'start_date' => $today->copy()->subYear(),

            'expiry_date' => $today->copy()->subDays(15),

            'document' => 'insurances/sample-icici-insurance.pdf',

            'notes' => 'Expired health insurance policy.',

            'status' => 'expired',
        ]);
    }
}