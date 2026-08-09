<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppNotification;
use Carbon\Carbon;

class AppNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $notifications = [

            // 1. Welcome
            [
                'type' => 'welcome',
                'title' => 'Welcome to Our Healthcare App',
                'message' => 'Welcome! Book doctor appointments, order medicines and manage your healthcare easily.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'home',
                'data' => ['screen' => 'home'],
                'is_read' => false,
            ],

            // 2. Registration
            [
                'type' => 'registration',
                'title' => 'Registration Successful',
                'message' => 'Your account has been created successfully.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'profile',
                'data' => ['screen' => 'profile'],
                'is_read' => true,
            ],

            // 3. Login
            [
                'type' => 'login',
                'title' => 'Login Successful',
                'message' => 'You have successfully logged into your account.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'home',
                'data' => ['screen' => 'home'],
                'is_read' => true,
            ],

            // 4. Coupon
            [
                'type' => 'coupon',
                'title' => 'New Coupon Available',
                'message' => 'Use WELCOME10 and get 10% discount on your eligible order.',
                'reference_type' => 'coupon',
                'reference_id' => 1,
                'action' => 'coupon_details',
                'data' => [
                    'coupon_id' => 1,
                    'coupon_code' => 'WELCOME10',
                ],
                'is_read' => false,
            ],

            // 5. Offer
            [
                'type' => 'offer',
                'title' => 'Special Healthcare Offer',
                'message' => 'Get special discounts on doctor appointments and medicine orders.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'offers',
                'data' => ['screen' => 'offers'],
                'is_read' => false,
            ],

            // 6. Free Appointment
            [
                'type' => 'free_appointment',
                'title' => 'Free Appointment Available',
                'message' => 'You are eligible for a free first doctor appointment. Use code FREEAPT.',
                'reference_type' => 'coupon',
                'reference_id' => 5,
                'action' => 'coupon_details',
                'data' => [
                    'coupon_id' => 5,
                    'coupon_code' => 'FREEAPT',
                    'free_appointment' => true,
                ],
                'is_read' => false,
            ],

            // 7. Appointment Booked
            [
                'type' => 'appointment_booked',
                'title' => 'Appointment Booked',
                'message' => 'Your doctor appointment has been booked successfully.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 1,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 1,
                    'appointment_no' => 'APT20260808001',
                ],
                'is_read' => false,
            ],

            // 8. Appointment Confirmed
            [
                'type' => 'appointment_confirmed',
                'title' => 'Appointment Confirmed',
                'message' => 'Your doctor appointment has been confirmed by the hospital.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 1,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 1,
                    'appointment_no' => 'APT20260808001',
                ],
                'is_read' => false,
            ],

            // 9. Appointment Pending
            [
                'type' => 'appointment_pending',
                'title' => 'Appointment Pending',
                'message' => 'Your appointment is waiting for confirmation from the hospital.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 2,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 2,
                ],
                'is_read' => false,
            ],

            // 10. Appointment Rejected
            [
                'type' => 'appointment_rejected',
                'title' => 'Appointment Rejected',
                'message' => 'Unfortunately, your doctor appointment has been rejected.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 3,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 3,
                    'reason' => 'Doctor unavailable',
                ],
                'is_read' => false,
            ],

            // 11. Appointment Cancelled
            [
                'type' => 'appointment_cancelled',
                'title' => 'Appointment Cancelled',
                'message' => 'Your doctor appointment has been cancelled.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 4,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 4,
                ],
                'is_read' => false,
            ],

            // 12. Appointment Rescheduled
            [
                'type' => 'appointment_rescheduled',
                'title' => 'Appointment Rescheduled',
                'message' => 'Your appointment has been successfully rescheduled to a new date and time.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 5,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 5,
                    'appointment_date' => $now->copy()->addDays(3)->format('Y-m-d'),
                    'appointment_time' => '10:30 AM',
                ],
                'is_read' => false,
            ],

            // 13. Appointment Reminder
            [
                'type' => 'appointment_reminder',
                'title' => 'Appointment Reminder',
                'message' => 'Your doctor appointment is scheduled for tomorrow.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 6,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 6,
                    'reminder' => 'tomorrow',
                ],
                'is_read' => false,
            ],

            // 14. Appointment Completed
            [
                'type' => 'appointment_completed',
                'title' => 'Appointment Completed',
                'message' => 'Your doctor consultation has been completed successfully.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 7,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 7,
                ],
                'is_read' => true,
            ],

            // 15. Payment Pending
            [
                'type' => 'payment_pending',
                'title' => 'Payment Pending',
                'message' => 'Payment for your appointment is still pending.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 8,
                'action' => 'payment',
                'data' => [
                    'appointment_id' => 8,
                    'payment_status' => 'pending',
                ],
                'is_read' => false,
            ],

            // 16. Payment Successful
            [
                'type' => 'payment_success',
                'title' => 'Payment Successful',
                'message' => 'Your payment has been completed successfully.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 9,
                'action' => 'payment_details',
                'data' => [
                    'appointment_id' => 9,
                    'payment_status' => 'paid',
                ],
                'is_read' => true,
            ],

            // 17. Payment Failed
            [
                'type' => 'payment_failed',
                'title' => 'Payment Failed',
                'message' => 'Your payment could not be completed. Please try again.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 10,
                'action' => 'payment',
                'data' => [
                    'appointment_id' => 10,
                    'payment_status' => 'failed',
                ],
                'is_read' => false,
            ],

            // 18. Medicine Order Placed
            [
                'type' => 'medicine_order_placed',
                'title' => 'Medicine Order Placed',
                'message' => 'Your medicine order has been placed successfully.',
                'reference_type' => 'medicine_order',
                'reference_id' => 1,
                'action' => 'medicine_order_details',
                'data' => [
                    'medicine_order_id' => 1,
                ],
                'is_read' => false,
            ],

            // 19. Medicine Order Accepted
            [
                'type' => 'medicine_order_accepted',
                'title' => 'Medicine Order Accepted',
                'message' => 'Your medicine order has been accepted by the pharmacy.',
                'reference_type' => 'medicine_order',
                'reference_id' => 1,
                'action' => 'medicine_order_details',
                'data' => [
                    'medicine_order_id' => 1,
                ],
                'is_read' => false,
            ],

            // 20. Medicine Order Packed
            [
                'type' => 'medicine_order_packed',
                'title' => 'Medicine Order Packed',
                'message' => 'Your medicines have been packed and are ready for dispatch.',
                'reference_type' => 'medicine_order',
                'reference_id' => 1,
                'action' => 'medicine_order_details',
                'data' => [
                    'medicine_order_id' => 1,
                ],
                'is_read' => false,
            ],

            // 21. Medicine Order Shipped
            [
                'type' => 'medicine_order_shipped',
                'title' => 'Medicine Order Shipped',
                'message' => 'Your medicine order has been shipped.',
                'reference_type' => 'medicine_order',
                'reference_id' => 1,
                'action' => 'medicine_order_tracking',
                'data' => [
                    'medicine_order_id' => 1,
                ],
                'is_read' => false,
            ],

            // 22. Medicine Order Delivered
            [
                'type' => 'medicine_order_delivered',
                'title' => 'Medicine Order Delivered',
                'message' => 'Your medicine order has been delivered successfully.',
                'reference_type' => 'medicine_order',
                'reference_id' => 1,
                'action' => 'medicine_order_details',
                'data' => [
                    'medicine_order_id' => 1,
                ],
                'is_read' => true,
            ],

            // 23. Medicine Order Cancelled
            [
                'type' => 'medicine_order_cancelled',
                'title' => 'Medicine Order Cancelled',
                'message' => 'Your medicine order has been cancelled.',
                'reference_type' => 'medicine_order',
                'reference_id' => 2,
                'action' => 'medicine_order_details',
                'data' => [
                    'medicine_order_id' => 2,
                ],
                'is_read' => false,
            ],

            // 24. Prescription Uploaded
            [
                'type' => 'prescription_uploaded',
                'title' => 'Prescription Uploaded',
                'message' => 'Your prescription has been uploaded successfully.',
                'reference_type' => 'prescription',
                'reference_id' => 1,
                'action' => 'prescription_details',
                'data' => [
                    'prescription_id' => 1,
                ],
                'is_read' => false,
            ],

            // 25. Prescription Approved
            [
                'type' => 'prescription_approved',
                'title' => 'Prescription Approved',
                'message' => 'Your prescription has been reviewed and approved.',
                'reference_type' => 'prescription',
                'reference_id' => 1,
                'action' => 'prescription_details',
                'data' => [
                    'prescription_id' => 1,
                    'status' => 'approved',
                ],
                'is_read' => false,
            ],

            // 26. Prescription Rejected
            [
                'type' => 'prescription_rejected',
                'title' => 'Prescription Rejected',
                'message' => 'Your prescription could not be approved. Please upload a valid prescription.',
                'reference_type' => 'prescription',
                'reference_id' => 2,
                'action' => 'prescription_details',
                'data' => [
                    'prescription_id' => 2,
                    'status' => 'rejected',
                ],
                'is_read' => false,
            ],

            // 27. Video Consultation
            [
                'type' => 'video_consultation',
                'title' => 'Video Consultation Ready',
                'message' => 'Your video consultation is ready. Join at your scheduled appointment time.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 11,
                'action' => 'join_video',
                'data' => [
                    'appointment_id' => 11,
                    'meeting_status' => 'ready',
                ],
                'is_read' => false,
            ],

            // 28. Chat Consultation
            [
                'type' => 'chat_consultation',
                'title' => 'Doctor Chat Available',
                'message' => 'Your doctor chat consultation is now available.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 12,
                'action' => 'open_chat',
                'data' => [
                    'appointment_id' => 12,
                ],
                'is_read' => false,
            ],

            // 29. Home Visit
            [
                'type' => 'home_visit',
                'title' => 'Home Visit Confirmed',
                'message' => 'Your doctor home visit has been confirmed.',
                'reference_type' => 'doctor_appointment',
                'reference_id' => 13,
                'action' => 'appointment_details',
                'data' => [
                    'appointment_id' => 13,
                    'visit_status' => 'scheduled',
                ],
                'is_read' => false,
            ],

            // 30. Family Member
            [
                'type' => 'family_member',
                'title' => 'Family Member Added',
                'message' => 'A new family member has been added to your account.',
                'reference_type' => 'family_member',
                'reference_id' => 1,
                'action' => 'family_members',
                'data' => [
                    'family_member_id' => 1,
                ],
                'is_read' => true,
            ],

            // 31. Profile
            [
                'type' => 'profile',
                'title' => 'Complete Your Profile',
                'message' => 'Complete your profile to get a better healthcare experience.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'profile',
                'data' => [
                    'screen' => 'profile',
                ],
                'is_read' => false,
            ],

            // 32. Promotional
            [
                'type' => 'promotional',
                'title' => 'Special Healthcare Promotion',
                'message' => 'Discover our latest healthcare offers and services.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'offers',
                'data' => [
                    'screen' => 'offers',
                ],
                'is_read' => false,
            ],

            // 33. System
            [
                'type' => 'system',
                'title' => 'System Update',
                'message' => 'Our healthcare application has been updated with new features.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'home',
                'data' => [
                    'screen' => 'home',
                ],
                'is_read' => true,
            ],

            // 34. Security
            [
                'type' => 'security',
                'title' => 'Security Alert',
                'message' => 'Your account security settings were recently updated.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'security',
                'data' => [
                    'screen' => 'security',
                ],
                'is_read' => false,
            ],

            // 35. Health Reminder
            [
                'type' => 'health_reminder',
                'title' => 'Health Reminder',
                'message' => 'Remember to take care of your health and schedule your regular checkup.',
                'reference_type' => null,
                'reference_id' => null,
                'action' => 'doctors',
                'data' => [
                    'screen' => 'doctors',
                ],
                'is_read' => false,
            ],
        ];

        foreach ($notifications as $notification) {

            AppNotification::create([
                'notifiable_type' => 'customer',
                'notifiable_id' => 1,

                'type' => $notification['type'],
                'title' => $notification['title'],
                'message' => $notification['message'],

                'reference_type' => $notification['reference_type'],
                'reference_id' => $notification['reference_id'],

                'action' => $notification['action'],
                'data' => $notification['data'],

                'is_read' => $notification['is_read'],

                'read_at' => $notification['is_read']
                    ? $now->copy()->subHours(rand(1, 24))
                    : null,

                'status' => true,

                'created_at' => $now->copy()->subHours(rand(1, 72)),
                'updated_at' => $now,
            ]);
        }
    }
}