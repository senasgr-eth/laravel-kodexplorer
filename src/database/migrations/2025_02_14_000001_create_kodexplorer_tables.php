<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodExplorerTables extends Migration
{
    public function up()
    {
        Schema::create('kodexplorer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'key']);
        });

        Schema::create('kodexplorer_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('path');
            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kodexplorer_shares');
        Schema::dropIfExists('kodexplorer_settings');
    }
}
