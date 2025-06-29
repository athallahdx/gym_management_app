<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPackage;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Registration',
                'description' => 'Pendaftaran awal untuk menjadi anggota gym.',
                'duration' => 0, // hari
                'price' => 50000,
                'status' => 'active',
                'images' => ['membership_package\/mp1.jpg'],
            ],
            [
                'name' => 'Silver Package',
                'description' => 'Paket keanggotaan dasar dengan akses gym standar.',
                'duration' => 30, // hari
                'price' => 200000,
                'status' => 'active',
                'images' => ['membership_package\/mp2.jpg'],
            ],
            [
                'name' => 'Gold Package',
                'description' => 'Paket dengan akses lengkap ke semua fasilitas gym dan kelas.',
                'duration' => 60,
                'price' => 350000,
                'status' => 'active',
                'images' => ['membership_package\/mp3.jpg'],
            ],
            [
                'name' => 'Platinum Package',
                'description' => 'Paket premium dengan fasilitas VIP dan sesi personal trainer.',
                'duration' => 90,
                'price' => 500000,
                'status' => 'active',
                'images' => ['membership_package\/mp4.jpg'],
            ],
            [
                'name' => 'Student Package',
                'description' => 'Paket khusus pelajar dengan harga terjangkau.',
                'duration' => 30,
                'price' => 150000,
                'status' => 'active',
                'images' => ['membership_package\/mp5.jpg'],
            ],
            [
                'name' => 'Quarterly Package',
                'description' => 'Paket keanggotaan selama 3 bulan.',
                'duration' => 120,
                'price' => 900000,
                'status' => 'inactive',
                'images' => ['membership_package\/mp6.jpg'],
            ],
            [
                'name' => 'Annual Package',
                'description' => 'Paket tahunan dengan diskon spesial.',
                'duration' => 365,
                'price' => 3000000,
                'status' => 'active',
                'images' => ['membership_package\/mp7.jpg'],
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::create($package);
        }
    }
}
