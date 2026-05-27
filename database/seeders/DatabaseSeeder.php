<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (you)
        User::updateOrCreate(
            ['email' => 'admin@hiking.az'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_APPROVED,
            ]
        );

        // Demo approved company
        $company = User::updateOrCreate(
            ['email' => 'company@hiking.az'],
            [
                'name' => 'Qafqaz Hiking Tours',
                'password' => 'password',
                'phone' => '+994 50 123 45 67',
                'role' => User::ROLE_COMPANY,
                'status' => User::STATUS_APPROVED,
            ]
        );

        // A pending company (so the admin has something to review)
        User::updateOrCreate(
            ['email' => 'pending@hiking.az'],
            [
                'name' => 'Yeni Səyahət MMC',
                'password' => 'password',
                'phone' => '+994 51 987 65 43',
                'role' => User::ROLE_COMPANY,
                'status' => User::STATUS_PENDING,
            ]
        );

        // Sample approved tours
        $samples = [
            [
                'title' => 'Şahdağ Zirvəsinə Yürüş',
                'description' => 'Azərbaycanın ən yüksək zirvələrindən birinə 2 günlük peşəkar bələdçili yürüş.',
                'price' => 180,
            ],
            [
                'title' => 'Laza Şəlaləsi Trekkinqi',
                'description' => 'Qusar rayonunda möhtəşəm şəlalələr və dağ mənzərələri ilə 1 günlük macəra.',
                'price' => 65,
            ],
            [
                'title' => 'Göygöl Milli Parkı Gəzintisi',
                'description' => 'Göygöl və Maralgöl ətrafında ailəvi, asan səviyyəli təbiət yürüşü.',
                'price' => 45,
            ],
        ];

        foreach ($samples as $sample) {
            Tour::updateOrCreate(
                ['slug' => Str::slug($sample['title'])],
                [
                    'user_id' => $company->id,
                    'title' => $sample['title'],
                    'description' => $sample['description'],
                    'content' => '<p>'.$sample['description'].'</p><p>Daha ətraflı məlumat üçün şirkətlə əlaqə saxlayın.</p>',
                    'price' => $sample['price'],
                    'status' => Tour::STATUS_APPROVED,
                ]
            );
        }
    }
}
