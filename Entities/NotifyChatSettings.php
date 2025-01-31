<?php

namespace Modules\NotifyChat\Entities;

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
        'mattermost_color_override',
        'mattermost_channel_override',
        'mattermost_username_override',
        'mattermost_icon_url_override',
        'mattermost_icon_emoji_override',
        'mattermost_priority_level',
        'mattermost_priority_requested_ack',
        'mattermost_priority_urgent_persistent_notifications',
    ];
}
