<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

interface SmsResponseContract
{
    public function getStatus(): bool;

    public function getStatusCode(): ?string;

    public function getStatusMessage(): ?string;

    public function getStatusText(): ?string;

    public function isLowBalance(): bool;

    public function getResponse(): array;

    public function isRateExceeded(): bool;
}
