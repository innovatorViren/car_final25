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
        

        // $support_smtp_config = SmtpConfiguration::create([
        //     'from_name' => 'MNS Technologies Support',
        //     'host_name' => 'smtppro.zoho.com',
        //     'username' => 'virendrabutani@gmail.com',
        //     'port' => '465',
        //     'password' => 'Admin@123',
        //     'driver' => 'smtp',
        //     'encryption' => 'SSL',
        //     'is_active' => "Yes",
        //     "ip" => "127.0.0.1",
        //     "created_by" => $admin->id,
        // ]);

        // SmtpConfiguration::create([
        //     'from_name' => 'MNS Technologies Info',
        //     'host_name' => 'smtppro.zoho.com',
        //     'username' => 'virendrabutani@gmail.com',
        //     'port' => '465',
        //     'password' => 'Admin@123',
        //     'driver' => 'smtp',
        //     'encryption' => 'SSL',
        //     'is_active' => "Yes",
        //     "ip" => "127.0.0.1",
        //     "created_by" => $admin->id,
        // ]);

        // MailTemplate::create([
        //     'module_name' => 'customer_portal',
        //     'smtp_id' => $support_smtp_config->id,
        //     'subject' => 'Customer Portal Login Details',
        //     'message_body' => "
        //                 <p>Customer Portal</p>

        //                 <p>&nbsp;</p>

        //                 <p>URL: [URL]</p>

        //                 <p>ID: [USERID]</p>

        //                 <p>Password: [PASSWORD]</p>",
        //     'is_active' => "Yes",
        //     "ip" => "127.0.0.1",
        //     "created_by" => $admin->id,
        // ]);

    }
}
