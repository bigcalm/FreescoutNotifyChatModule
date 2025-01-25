<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiCommunicationPlatformSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_communication_platform_settings', function (Blueprint $table) {
            $table->integer('mailbox_id');
            $table->boolean('discord_enabled');
            $table->string('discord_webhook_url');
            $table->boolean('slack_enabled');
            $table->string('slack_webhook_url');
            $table->boolean('mattermost_enabled');
            $table->string('mattermost_webhook_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multi_communication_platform_settings');
    }
}
