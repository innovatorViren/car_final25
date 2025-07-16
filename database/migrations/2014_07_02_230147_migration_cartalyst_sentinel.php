<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('activations')) {
            Schema::create('activations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->boolean('completed')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if (!Schema::hasTable('persistences')) {
            Schema::create('persistences', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->unique('code');
            });
        }

        if (!Schema::hasTable('reminders')) {
            Schema::create('reminders', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->boolean('completed')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->text('permissions')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->unique('slug');
            });
        }

        if (!Schema::hasTable('role_users')) {
            Schema::create('role_users', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->nullableTimestamps();

                $table->engine = 'InnoDB';
                $table->primary(['user_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('throttle')) {
            Schema::create('throttle', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('type');
                $table->string('ip')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->index('user_id');
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email');
                $table->string('password');
                $table->text('permissions')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('mobile');

                $table->string('emp_type')->default("non-emplyee");
                $table->bigInteger('emp_id')->nullable();

                $table->bigInteger('customer_id')->nullable();

                $table->unsignedInteger('roles_id')->nullable();
                $table->string('image')->nullable();
                $table->string('image_path')->nullable();
                $table->enum('is_ip_base', ['Yes', 'No'])->default('No');
                $table->string('allow_multi_login')->nullable();
                $table->text('ip')->nullable();
                $table->text('update_from_ip')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->enum('is_active', ['Yes', 'No'])->default('No');
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->softDeletes();
                $table->timestamps();
                $table->engine = 'InnoDB';
                $table->unique('email');
                $table->foreign('roles_id')->references('id')->on('roles');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
        Schema::dropIfExists('persistences');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('throttle');
        Schema::dropIfExists('users');
    }
}
