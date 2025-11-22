<?php

declare(strict_types=1);

namespace App\Services\Sms\Providers;

use App\Enums\Message\MessageStatus;
use App\Services\Sms\Contracts\SendSmsContract;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class T2froComsService implements SendSmsContract
{
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
     */
    protected ?string $message = '';

    /**
     * The message status code formatted to match the statuses of \App\Enums\Message\MessageStatus
     *
     * @var ?string
     */
    protected ?string $text = '';

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

        // set status
        $status = Arr::get($response, 'status');
        $this->status = $status;

        if (! $this->status) {
            $this->code = Arr::get($response, 'code');
            $this->message = Arr::get($response, 'message');

            $this->text = MessageStatus::FAILED;
        } else {
            $this->text = MessageStatus::SENT;
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
        return $this->message;
    }

    public function getStatusText(): ?string
    {
        return $this->text;
    }

    /**
     * Checks if balance is low
     */
    public function isLowBalance(): bool
    {
        return $this->code == '3003';
    }

    /**
     * Checks if rate is exceeded
     */
    public function isRateExceeded(): bool
    {
        return $this->code == '3001';
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

    /**
     * Send sms
     */
    public function sendSms(
        array $phone_numbers,
        string $message,
        array $options
    ): self {

        $payload = [
            'message' => $message,
            'contacts' => $phone_numbers,
            'contact_type' => 'input',
            'priority' => 'normal' // normal | high
        ];

        try {
            $response = Http::t2fromcoms()
                ->post('/campaigns/send', $payload);

            logger("response", $response->json());

            $this->setResponse($response->json());
        } catch (Exception $e) {
            logger($e);
        }

        return $this;
    }
}
