<?php

declare(strict_types=1);

namespace App\Services\Sms\Providers;

use App\Enums\Message\MessageStatus;
use App\Services\Sms\Contracts\SendSmsContract;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class DotgoService implements SendSmsContract
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
        $this->status = $status === 'ok' || $status === 'accepted';

        if (! $this->status) {
            $this->code = Arr::get($response, 'error_code');
            $this->message = Arr::get($response, 'error_reason');

            $this->text = MessageStatus::FAILED;

            if ($this->code == '3040') {
                $this->text = MessageStatus::EXPIRED;
            } elseif ($this->code == '3041') {
                $this->text = MessageStatus::FAILED;
            }
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
        $expiry = $options['expiry'] ?? null;
        $options['expiry'] = $expiry
            ? Carbon::parse($expiry)
            ->second
            : null;

        $payload = [
            ...$options,
            'body' => $message,
            'to' => implode(',', $phone_numbers),
            'callback_url' => url(route('webhooks.dotgo.sent-messages')),
            'api_token' => config('services.dotgo.api_token'),
        ];

        try {
            $response = Http::dotgo()
                ->get('/Messages', $payload);
            $this->setResponse($response->json());
        } catch (Exception $e) {
            logger($e);
        }

        return $this;
    }

    /**
     * Sends voice message to Dotgo for processing
     */
    public function sendVoiceMessage(
        array $phone_numbers,
        string $media_url,
        array $options
    ): self {

        $payload = [
            ...$options,
            'media_url' => url(public_path($media_url)),
            'recipient' => implode(',', $phone_numbers),
            'callback_url' => url(route('webhooks.dotgo.voice-messages')),
            'api_token' => config('services.dotgo.api_token'),
        ];

        try {
            $response = Http::dotgo()
                ->get('/Calls', $payload);

            $this->setResponse($response->json());
        } catch (Exception $e) {
            logger($e);
        }

        return $this;
    }

    public function registerSenderId(
        string $phone_number,
        string $id
    ): self {
        $payload = [
            'api_token' => config('services.dotgo.api_token'),
            'callback_url' => url(route('webhooks.dotgo.inbound-messages', $phone_number)),
            'phone_number' => $phone_number,
            'id' => $id,
        ];

        try {
            $response = Http::dotgo()
                ->get('/InboundPhoneNumbers', $payload);

            $this->setResponse($response->json());

            $this->setResponse($response->json());
        } catch (Exception $e) {
            logger($e);
        }

        return $this;
    }

    public function registerSenderMask(
        string $mask,
        string $type,
        string $remarks
    ): self {
        $payload = [
            'sender_mask' => $mask,
            'type' => $type,
            'remarks' => $remarks
        ];

        try {
            $account_id = config('services.dotgo.api_token');
            $response = Http::dotgo()
                ->withHeader('Authorization', $account_id)
                ->post('/ReqSenderMask', $payload);

            logger()->channel('sms')->info("Dotgo: Sender mask response", [
                'http' => $response->status(),
                'data' => $response->json(),
                'type' => $type,
                'remarks' => $remarks,
                'mask' => $mask
            ]);
            $this->setResponse($response->json());

            $this->setResponse($response->json());
        } catch (Exception $e) {
            logger($e);
        }

        return $this;
    }
}
