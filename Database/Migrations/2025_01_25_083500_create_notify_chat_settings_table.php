<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifyChatSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notify_chat_settings', function (Blueprint $table) {
            $table->integer('mailbox_id');

            $table->boolean('discord_enabled')->default(false);
            $table->string('discord_webhook_url')->nullable();

            $table->boolean('slack_enabled')->default(false);
            $table->string('slack_webhook_url')->nullable();

            $table->boolean('mattermost_enabled')->default(false);
            $table->string('mattermost_webhook_url')->nullable();
            $table->string('mattermost_color_override')->nullable();
            $table->string('mattermost_channel_override')->nullable();
            $table->string('mattermost_username_override')->nullable();
            $table->string('mattermost_icon_url_override')->nullable();
            $table->string('mattermost_icon_emoji_override')->nullable();
            $table->string('mattermost_priority_level')->nullable(); // important or urgent
            $table->boolean('mattermost_priority_requested_ack')->default(false); // only when priority set
            $table->boolean('mattermost_priority_urgent_persistent_notifications')->default(false); // only for urgent
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notify_chat_settings');
    }
}
