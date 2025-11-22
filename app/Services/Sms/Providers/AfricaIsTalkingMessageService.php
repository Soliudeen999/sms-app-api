<?php

declare(strict_types=1);

namespace App\Services\Sms\Providers;

use App\Enums\Message\MessageStatus;
use App\Services\AfricaIsTalkingService\Concerns\Smsable;
use App\Services\Sms\Contracts\SendSmsContract;
use Illuminate\Support\Arr;

class AfricaIsTalkingMessageService implements SendSmsContract
{
    use Smsable;

    /**
     * The response data
     *
     * @var array<string, string>
     */
    protected array $response = [];

    /**
     * Response status which is usually text but converted to boolean
     * depending on the content of the text
     */
    protected bool $status = false;

    /**
     * The response message in the case of error or failure
     *
     * @var bool
     */
    protected ?string $message;

    /**
     * The message status code formatted to match the statuses of \App\Enums\Message\MessageStatus
     *
     * @var ?string
     */
    protected ?string $text;

    /**
     * The response status code
     *
     * @var ?string
     */
    protected ?string $code = '';

    /**
     * Sets the values of the response
     *
     * @return void
     */
    protected function setResponse(?array $response = [])
    {
        $this->response = is_array($response) ? $response : [];
        $recipients = Arr::get($response, 'SMSMessageData.Message.Recipients');

        $success = ! empty($recipients)
            ? $recipients[0]['status']
            : null;

        if ($success === 'success') {
            $this->status = true;
            $this->text = MessageStatus::Sent;
            $this->message = Arr::get($response, 'SMSMessageData.Message');
        } else {
            $this->status = false;
            $this->text = MessageStatus::Failed;
            $this->message = Arr::get($response, 'SMSMessageData.Message');
        }
    }

    /**
     * get response status
     */
    public function getStatus(): bool
    {
        return (bool) $this->status;
    }

    public function getStatusCode(): ?string
    {
        return (string) $this->code;
    }

    public function getStatusMessage(): ?string
    {
        return $this->message ?? null;
    }

    public function getStatusText(): ?string
    {
        return $this->text;
    }

    public function isLowBalance(): bool
    {
        return false;
    }

    /**
     * returns the raw response body
     *
     * @return array<string, string|int|bool>
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
