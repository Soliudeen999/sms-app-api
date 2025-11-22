<?php

use App\Enums\Campaign\CampaignRecipientType;
use App\Enums\Campaign\CampaignStatus;
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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable(); // Sender Name
            $table->text('body');
            $table->string('recipient_type')->default(CampaignRecipientType::CONTACT_IDS);
            $table->json('recipients'); // Keep the numbers list | contact_ids | tag ids
            $table->json('extra_recipient_numbers')->nullable(); // Keep the numbers list

            $table->string('type')->default(MessageType::INSTANT);
            $table->string('status')->default(CampaignStatus::PENDING);
            $table->timestamp('scheduled_at')->nullable(); // Required to process SCHEDULED msgs
            $table->json('recurrence_config')->nullable(); // Array of [recurrence = [daily, weekly, monthly], day = ['mon', 'tue, wed, 'thur, fri, sat, sun], time : 24 hrs time]
            $table->timestamp('last_processed_at')->nullable();
            $table->string('provider');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
