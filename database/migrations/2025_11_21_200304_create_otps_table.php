<?php

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('email')->index()->nullable();
            $table->string('type')->index()->default(OtpType::GENERAL);
            $table->string('channel')->index()->default(OtpChannel::MAIL);
            $table->timestamp('expires_at')->index()->nullable();
            $table->string('code')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
