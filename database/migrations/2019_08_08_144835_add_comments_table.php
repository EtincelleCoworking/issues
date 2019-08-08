<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content');
            $table->boolean('is_private')->default(false);

            $table->unsignedBigInteger('reporter_id');
            $table->foreign('reporter_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('issue_id');
            $table->foreign('issue_id')->references('id')
                ->on('issues')->onDelete('cascade');
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
