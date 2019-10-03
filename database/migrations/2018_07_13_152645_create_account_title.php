<?php
declare (strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;

class CreateAccountTitle extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        \Schema::create('account_title', function (Blueprint $table) : void {
            $table->increments('id');
            $table->enum(
                'type',
                ['ASSET', 'LIABILITY', 'NET_ASSET', 'REVENUE', 'EXPENSE', 'OTHER']
            );
            $table->string('name')->unique();
            $table->integer('parent_id');
            $table->string('system_key')->default('');
            $table->timestamps();
        });

        \DB::table('account_title')->insert([
            'type' => 'OTHER',
            'name' => '開始残高',
            'parent_id' => 0,
            'system_key' => 'OPENING_BALANCE',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        \Schema::dropIfExists('account_title');
    }
}
