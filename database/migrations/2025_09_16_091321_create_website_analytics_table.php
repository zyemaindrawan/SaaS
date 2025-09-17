<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_content_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('visitors_count')->default(0);
            $table->integer('page_views_count')->default(0);
            $table->date('date');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['website_content_id', 'date']);
            $table->index('website_content_id');
            $table->index('date');
            $table->index(['date', 'visitors_count']);
            $table->index(['website_content_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_analytics');
    }
};
