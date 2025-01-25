<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateDiscordSettingsToMultiCommunicationPlatformSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // first check if the discord table exists
        if (!Schema::hasTable('discord')) {
            return;
        }

        // copy data from discord table to multi_communication_platform_settings table
        DB::table('discord')->get()->each(function ($discord) {
            DB::table('multi_communication_platform_settings')->insert([
                'mailbox_id' => $discord->mailbox_id,
                'discord_enabled' => $discord->enabled,
                'discord_webhook_url' => $discord->webhook_url,
                'slack_enabled' => false,
                'slack_webhook_url' => '',
                'mattermost_enabled' => false,
                'mattermost_webhook_url' => ''
            ]);
        });

        // drop discord table
        Schema::dropIfExists('discord');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // there is no way of knowing if the discord table existed before the migration was run
        // for safety, we'll create it anyway and copy the data back

        // create discord table
        Schema::create('discord', function (Blueprint $table) {
            $table->integer('mailbox_id');
            $table->boolean('enabled');
            $table->string('webhook_url');
        });

        // copy data from multi_communication_platform_settings table to discord table
        DB::table('multi_communication_platform_settings')->get()->each(function ($multi) {
            DB::table('discord')->insert([
                'mailbox_id' => $multi->mailbox_id,
                'enabled' => $multi->discord_enabled,
                'webhook_url' => $multi->discord_webhook_url
            ]);
        });
    }
}