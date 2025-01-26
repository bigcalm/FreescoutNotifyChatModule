<?php

namespace Modules\NotifyChatPlatform\Entities;

use Illuminate\Database\Eloquent\Model;

class NotifyChatSettings extends Model
{
    protected $table = 'notify_chat_settings';

    public $timestamps = false;

    protected $primaryKey = 'mailbox_id';

    public $incrementing = false;

    protected $fillable = [
        'mailbox_id',
        'discord_enabled',
        'discord_webhook_url',
        'slack_enabled',
        'slack_webhook_url',
        'mattermost_enabled',
        'mattermost_webhook_url',
    ];
}
