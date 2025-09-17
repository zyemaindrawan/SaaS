<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('template_slug');
            $table->string('website_name');
            $table->json('content_data');
            $table->string('custom_domain')->nullable();
            $table->string('subdomain')->nullable();
            $table->enum('status', [
                'draft', 'previewed', 'paid', 'processing', 'active', 'suspended'
            ])->default('draft');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('template_slug');
            $table->index('status');
            $table->index('custom_domain');
            $table->index('subdomain');
            $table->index('activated_at');
            $table->index('expires_at');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['template_slug', 'status']);
            
            $table->fullText('website_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_contents');
    }
};
