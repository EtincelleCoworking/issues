<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = new \App\User([
            'name' => 'Sébastien Hordeaux',
            'email' => 'sebastien@etincelle-coworking.com',
            'password' => \Illuminate\Support\Facades\Hash::make('etincelle'),
            'api_token' => \Illuminate\Support\Str::random(60),
        ]);
        $user->save();

        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description');
            $table->unsignedBigInteger('reporter_id');
            $table->string('status');
            $table->foreign('reporter_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('responsible_id')->nullable();
            $table->foreign('responsible_id')->references('id')
                ->on('users')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
