<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'BS. Nguyễn Văn Minh',
                'specialty' => 'Tim mạch',
                'email' => 'dr.minh@healthsync.vn',
                'phone' => '0912111222',
                'avatar' => 'https://i.pravatar.cc/100?img=68',
                'place' => 'Vinmec Times City',
                'address' => '458 Phạm Văn Đồng, Bắc Từ Liêm, Hà Nội',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
            [
                'name' => 'BS. Trần Thị Hoa',
                'specialty' => 'Dinh dưỡng',
                'email' => 'dr.hoa@healthsync.vn',
                'phone' => '0987333444',
                'avatar' => 'https://i.pravatar.cc/100?img=47',
                'place' => 'Tư vấn video (Online)',
                'address' => 'Quận 1, TP.HCM',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
            [
                'name' => 'BS. Lê Hoàng Nam',
                'specialty' => 'Da liễu',
                'email' => 'dr.nam@healthsync.vn',
                'phone' => '0905555666',
                'avatar' => 'https://i.pravatar.cc/100?img=12',
                'place' => 'Bệnh viện Bạch Mai',
                'address' => 'Bạch Mai, Hà Nội',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
            [
                'name' => 'BS. Phạm Việt Dũng',
                'specialty' => 'Huyết học',
                'email' => 'dr.dung@healthsync.vn',
                'phone' => '0977888999',
                'avatar' => 'https://i.pravatar.cc/100?img=33',
                'place' => 'Bệnh viện 108',
                'address' => '1 Trần Khánh Dư, Hà Nội',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
            [
                'name' => 'BS. Vũ Thị Linh',
                'specialty' => 'Nhi khoa',
                'email' => 'dr.linh@healthsync.vn',
                'phone' => '0968777555',
                'avatar' => 'https://i.pravatar.cc/100?img=25',
                'place' => 'Bệnh viện Nhi Trung ương',
                'address' => '216 Ba Trieu, Ha Noi',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
            [
                'name' => 'BS. Đỗ Minh Tuấn',
                'specialty' => 'Tâm thần',
                'email' => 'dr.tuan@healthsync.vn',
                'phone' => '0955666777',
                'avatar' => 'https://i.pravatar.cc/100?img=42',
                'place' => 'Viện Sức khỏe Tâm thần',
                'address' => 'Hà Nội',
                'password' => Hash::make('doctor123'),
                'status' => 'active'
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::firstOrCreate(
                ['email' => $doctor['email']],
                $doctor
            );
        }
    }
}
