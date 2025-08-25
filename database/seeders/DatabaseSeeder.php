<?php

namespace Database\Seeders;



use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
// use App\Models\MailTemplate;
use App\Models\Setting;
// use App\Models\SmtpConfiguration;
use App\Models\State;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            SentinelDatabaseSeeder::class,
        ]);

        $admin = User::where('email', "virendrabutani@gmail.com")->first();

    }
}
