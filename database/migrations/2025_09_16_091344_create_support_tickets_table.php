<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_content_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
            $table->string('subject');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                  ->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])
                  ->default('open');
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('website_content_id');
            $table->index('status');
            $table->index('priority');
            $table->index('assigned_to');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);

            $table->fullText('subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
