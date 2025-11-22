<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property string $body
 * @property \BenSampo\Enum\Enum $recipient_type
 * @property string $recipients
 * @property string|null $extra_recipient_numbers
 * @property \BenSampo\Enum\Enum $type
 * @property \BenSampo\Enum\Enum $status
 * @property string|null $scheduled_at
 * @property array<array-key, mixed> $recurrence_config
 * @property string|null $last_processed_at
 * @property string $provider
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign filter(?array $filters = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign search($keyword = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign sort(array|string $sorting = [], string $direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereExtraRecipientNumbers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereLastProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereRecurrenceConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUserId($value)
 */
	class Campaign extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $country_code
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact filter(?array $filters = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact search($keyword = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact sort(array|string $sorting = [], string $direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUserId($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $campaign_id
 * @property int $user_id
 * @property int|null $contact_id
 * @property string|null $title
 * @property string $body
 * @property string $phone_number
 * @property string $type
 * @property string $status
 * @property string|null $status_changed_at
 * @property string $provider
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campaign $campaign
 * @property-read \App\Models\Contact|null $contact
 * @property-read \App\Models\User|null $creator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message filter(?array $filters = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message search($keyword = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message sort(array|string $sorting = [], string $direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereStatusChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $email
 * @property string $type
 * @property string $channel
 * @property string|null $expires_at
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereUserId($value)
 */
	class Otp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone_number
 * @property \BenSampo\Enum\Enum $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Otp> $otps
 * @property-read int|null $otps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User search($keyword = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

