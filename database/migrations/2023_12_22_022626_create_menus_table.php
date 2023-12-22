<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['header', 'menu', 'navbar'])->index()->default('menu')->comment('[header, menu, navbar]');
            $table->integer('order')->default(0);
            $table->string('link', 100)->index()->default('#');
            $table->enum('link_type', ['route', 'url'])->default('url')->comment('[route, url]');
            $table->string('icon_class', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('use_translation')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
