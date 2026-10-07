<?php

namespace Database\Seeders;

use App\Models\FinanceProvider;
use Illuminate\Database\Seeder;

class FinanceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [

            /*
            |--------------------------------------------------------------------------
            | 1. HealthCare Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'HealthCare Finance',
                'code' => 'HEALTHCARE_FINANCE',

                'logo' => null,

                'description' =>
                    'Flexible healthcare financing for medical treatment, hospitalization, surgery, diagnostics, medicines and other healthcare expenses.',

                'website' => null,

                'contact_name' => 'Healthcare Finance Support',
                'contact_mobile' => '1800123456',
                'contact_email' => 'support@healthcarefinance.example',

                'min_amount' => 25000,
                'max_amount' => 1000000,

                'interest_rate' => 10.50,
                'processing_fee' => 1500,

                'tenure_min_months' => 6,
                'tenure_max_months' => 60,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. Medical Care Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Medical Care Finance',
                'code' => 'MEDICAL_CARE_FINANCE',

                'logo' => null,

                'description' =>
                    'Medical finance solution designed to help customers manage healthcare expenses with flexible repayment options.',

                'website' => null,

                'contact_name' => 'Medical Care Finance Support',
                'contact_mobile' => '1800123457',
                'contact_email' => 'support@medicalcarefinance.example',

                'min_amount' => 10000,
                'max_amount' => 500000,

                'interest_rate' => 11.00,
                'processing_fee' => 999,

                'tenure_min_months' => 6,
                'tenure_max_months' => 48,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 2,
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. Hospital Treatment Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Hospital Treatment Finance',
                'code' => 'HOSPITAL_TREATMENT_FINANCE',

                'logo' => null,

                'description' =>
                    'Finance options focused on hospitalization, medical treatment and surgical procedures.',

                'website' => null,

                'contact_name' => 'Hospital Finance Desk',
                'contact_mobile' => '1800123458',
                'contact_email' => 'support@hospitalfinance.example',

                'min_amount' => 50000,
                'max_amount' => 1500000,

                'interest_rate' => 10.99,
                'processing_fee' => 2000,

                'tenure_min_months' => 12,
                'tenure_max_months' => 60,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => false,
                'medicines' => false,
                'doctor_consultation' => true,
                'dental_treatment' => false,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 3,
            ],

            /*
            |--------------------------------------------------------------------------
            | 4. Surgery Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Surgery Finance',
                'code' => 'SURGERY_FINANCE',

                'logo' => null,

                'description' =>
                    'Healthcare financing for planned and required surgical treatments.',

                'website' => null,

                'contact_name' => 'Surgery Finance Support',
                'contact_mobile' => '1800123459',
                'contact_email' => 'support@surgeryfinance.example',

                'min_amount' => 75000,
                'max_amount' => 2000000,

                'interest_rate' => 10.75,
                'processing_fee' => 2500,

                'tenure_min_months' => 12,
                'tenure_max_months' => 72,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => false,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 4,
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. Diagnostic Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Diagnostic Finance',
                'code' => 'DIAGNOSTIC_FINANCE',

                'logo' => null,

                'description' =>
                    'Finance option for diagnostic investigations, laboratory tests, imaging and preventive health testing.',

                'website' => null,

                'contact_name' => 'Diagnostic Finance Support',
                'contact_mobile' => '1800123460',
                'contact_email' => 'support@diagnosticfinance.example',

                'min_amount' => 5000,
                'max_amount' => 200000,

                'interest_rate' => 12.50,
                'processing_fee' => 499,

                'tenure_min_months' => 3,
                'tenure_max_months' => 24,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => false,
                'surgery' => false,
                'diagnostics' => true,
                'medicines' => false,
                'doctor_consultation' => true,
                'dental_treatment' => false,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 5,
            ],

            /*
            |--------------------------------------------------------------------------
            | 6. Medicine Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Medicine Finance',
                'code' => 'MEDICINE_FINANCE',

                'logo' => null,

                'description' =>
                    'Healthcare financing support for prescription medicines and ongoing medical treatment expenses.',

                'website' => null,

                'contact_name' => 'Medicine Finance Support',
                'contact_mobile' => '1800123461',
                'contact_email' => 'support@medicinefinance.example',

                'min_amount' => 5000,
                'max_amount' => 150000,

                'interest_rate' => 12.75,
                'processing_fee' => 399,

                'tenure_min_months' => 3,
                'tenure_max_months' => 18,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => false,
                'surgery' => false,
                'diagnostics' => false,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => false,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 6,
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. Dental Care Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Dental Care Finance',
                'code' => 'DENTAL_CARE_FINANCE',

                'logo' => null,

                'description' =>
                    'Finance option for dental treatment, oral surgery, implants, orthodontic treatment and related procedures.',

                'website' => null,

                'contact_name' => 'Dental Finance Support',
                'contact_mobile' => '1800123462',
                'contact_email' => 'support@dentalfinance.example',

                'min_amount' => 10000,
                'max_amount' => 300000,

                'interest_rate' => 11.50,
                'processing_fee' => 750,

                'tenure_min_months' => 6,
                'tenure_max_months' => 36,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => false,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => false,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 7,
            ],

            /*
            |--------------------------------------------------------------------------
            | 8. General Medical Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'General Medical Finance',
                'code' => 'GENERAL_MEDICAL_FINANCE',

                'logo' => null,

                'description' =>
                    'General-purpose medical finance for eligible healthcare requirements and treatment expenses.',

                'website' => null,

                'contact_name' => 'Medical Finance Support',
                'contact_mobile' => '1800123463',
                'contact_email' => 'support@generalmedicalfinance.example',

                'min_amount' => 25000,
                'max_amount' => 750000,

                'interest_rate' => 11.25,
                'processing_fee' => 1250,

                'tenure_min_months' => 6,
                'tenure_max_months' => 60,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 8,
            ],

            /*
            |--------------------------------------------------------------------------
            | 9. Family Healthcare Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Family Healthcare Finance',
                'code' => 'FAMILY_HEALTHCARE_FINANCE',

                'logo' => null,

                'description' =>
                    'Healthcare financing for medical expenses of customers and eligible family members.',

                'website' => null,

                'contact_name' => 'Family Finance Support',
                'contact_mobile' => '1800123464',
                'contact_email' => 'support@familyhealthfinance.example',

                'min_amount' => 25000,
                'max_amount' => 1000000,

                'interest_rate' => 11.75,
                'processing_fee' => 1499,

                'tenure_min_months' => 6,
                'tenure_max_months' => 60,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 9,
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. Premium Healthcare Finance
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Premium Healthcare Finance',
                'code' => 'PREMIUM_HEALTHCARE_FINANCE',

                'logo' => null,

                'description' =>
                    'Higher-value healthcare finance for major medical procedures and treatment requirements.',

                'website' => null,

                'contact_name' => 'Premium Finance Support',
                'contact_mobile' => '1800123465',
                'contact_email' => 'support@premiumhealthfinance.example',

                'min_amount' => 100000,
                'max_amount' => 3000000,

                'interest_rate' => 10.25,
                'processing_fee' => 3000,

                'tenure_min_months' => 12,
                'tenure_max_months' => 84,

                'cibil_required' => true,
                'medical_finance' => true,

                'hospitalization' => true,
                'surgery' => true,
                'diagnostics' => true,
                'medicines' => true,
                'doctor_consultation' => true,
                'dental_treatment' => true,
                'medical_treatment' => true,

                'status' => true,
                'display_order' => 10,
            ],
        ];

        foreach ($providers as $provider) {
            FinanceProvider::updateOrCreate(
                [
                    'code' => $provider['code'],
                ],
                $provider
            );
        }
    }
}