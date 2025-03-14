<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanyInformationSeeder extends Seeder
{
    public function run()
    {
        // $user = DB::table('users')->inRandomOrder()->first();

        DB::table('company_information')->insert([
            'id' => Str::uuid(),
            'user_id' => 2,
            'approval_status' => 'pending',
            'admin_feedback' => null,
            'approved_by' => null,
            'approved_at' => null,
            'company_name' => 'Tech Solutions Inc.',
            'primary_number' => '+1234567890',
            'secondary_number' => '+0987654321',
            'hq_location_name' => 'Main Headquarters',
            'hq_address' => '123 Tech Street',
            'hq_city' => 'Techville',
            'hq_state' => 'TechState',
            'hq_postal_code' => '12345',
            'hq_country' => 'Techland',
            'hq_province' => 'Tech Province',
            'hq_fax' => '+1122334455',
            'hq_website' => 'https://techsolutions.com',
            'hq_opening_time' => '08:00:00',
            'hq_closing_time' => '18:00:00',
            'attachment' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
