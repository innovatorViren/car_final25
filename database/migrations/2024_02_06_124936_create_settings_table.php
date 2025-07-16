<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('title')->nullable();
                $table->text('value')->nullable();
                $table->string('group')->nullable();
                $table->string('update_from_ip')->nullable();
                $table->integer('created_by')->default(1);
                $table->integer('updated_by')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        $settingData = [
            [
                'name' => 'project_title',
                'title' => 'Project Title',
                'value' => 'Test',
                'group' => 'company',
            ],
            [
                'name' => 'company_name',
                'title' => 'Company Name',
                'value' => 'Test',
                'group' => 'company',
            ],
            [
                'name' => 'company_address',
                'title' => 'Company Address',
                'value' => 'Test',
                'group' => 'company',
            ],
            [
                'name' => 'gst_no',
                'title' => 'GST No',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'pan_no',
                'title' => 'PAN No',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'country',
                'title' => 'Country',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'state',
                'title' => 'State',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'city',
                'title' => 'City',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'pincode',
                'title' => 'Pincode',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'company_email',
                'title' => 'Email',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'company_mobile',
                'title' => 'Mobile No.',
                'value' => '',
                'group' => 'company',
            ],
            [
                'name' => 'android_version',
                'title' => 'Android Version',
                'value' => '1',
                'group' => 'app_version',
            ],
            [
                'name' => 'ios_version',
                'title' => 'IOS Version',
                'value' => '1',
                'group' => 'app_version',
            ],
        ];

        DB::table('settings')->insert($settingData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
