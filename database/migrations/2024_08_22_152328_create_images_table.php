<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url'); // URL of the image
            $table->timestamps(); // Created at and updated at columns
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('images');
    }
    

};
