<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consultation;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultations = [
            [
                'content' => 'Initial nutrition consultation to assess your current diet and health goals. We will discuss your eating habits, lifestyle, and create a personalized nutrition plan.',
                'price' => 75.00,
                'time' => 60,
            ],
            [
                'content' => 'Follow-up consultation to review your progress, adjust your nutrition plan, and address any challenges you may be facing.',
                'price' => 50.00,
                'time' => 45,
            ],
            [
                'content' => 'Sports nutrition consultation specifically designed for athletes. We will focus on performance optimization, recovery nutrition, and competition day strategies.',
                'price' => 100.00,
                'time' => 90,
            ],
            [
                'content' => 'Weight management consultation to help you achieve your weight loss or weight gain goals through proper nutrition and lifestyle changes.',
                'price' => 65.00,
                'time' => 60,
            ],
            [
                'content' => 'Quick nutrition check-in for existing clients to answer questions and make minor adjustments to your current plan.',
                'price' => 35.00,
                'time' => 30,
            ],
        ];

        foreach ($consultations as $consultation) {
            Consultation::create($consultation);
        }
    }
}
