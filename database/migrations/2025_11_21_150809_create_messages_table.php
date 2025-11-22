<?php

use App\Enums\Message\MessageStatus;
use App\Enums\Message\MessageType;
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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('body');
            $table->text('phone_number');
            $table->string('type')->default(MessageType::INSTANT);
            $table->string('status')->default(MessageStatus::PENDING);
            $table->timestamp('status_changed_at')->nullable();
            $table->string('provider');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
