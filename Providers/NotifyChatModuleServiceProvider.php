<?php

namespace Modules\NotifyChat\Providers;

use App\Conversation;
use App\Customer;
use App\Thread;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\NotifyChat\Entities\NotifyChatSettings;
use App\Misc;

define('NOTIFY_CHAT_MODULE', 'notifychat');

class NotifyChatModuleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        \Eventy::addAction('mailboxes.settings.menu', function($mailbox) {
            if (auth()->user()->isAdmin()) {
                echo \View::make('notifychat::partials/settings_menu', ['mailbox' => $mailbox])->render();
            }
        }, 80);

        \Eventy::addAction('conversation.created_by_customer', function($conversation, $thread, $customer) {
            $settings = NotifyChatSettings::findOrFail($conversation->mailbox_id);

            $home = \Helper::urlHome();
            $conversation_url = $home . "/conversation/" . $conversation->id;

            if ($settings->discord_enabled && !empty($settings->discord_webhook_url)) {
                $this->sendToDiscord(
                    $settings->discord_webhook_url,
                    "New Support Ticket",
                    $conversation_url,
                    "A new support ticket has been created!",
                    $this->compileDiscordFields($conversation, $thread, $customer)
                );
            }

            if ($settings->slack_enabled && !empty($settings->slack_webhook_url)) {
                $this->sendToSlack(
                    $settings,
                    $conversation->subject ?? "New Support Ticket",
                    $conversation_url,
                    "A new support ticket has been created!",
                    $thread->getBodyAsText(),
                    $this->compileSlackAndMattermostFields($conversation, $thread, $customer)
                );
            }

            if ($settings->mattermost_enabled && !empty($settings->mattermost_webhook_url)) {
                $this->sendToMattermost(
                    $settings,
                    $conversation->subject ?? "New Support Ticket",
                    $conversation_url,
                    "A new support ticket has been created!",
                    $thread->getBodyAsText(),
                    $this->compileSlackAndMattermostFields($conversation, $thread, $customer)
                );
            }
        }, 20, 3);

        \Eventy::addAction('conversation.customer_replied', function($conversation, $thread, $customer) {
            $settings = NotifyChatSettings::findOrFail($conversation->mailbox_id);

            $home = \Helper::urlHome();
            $conversation_url = $home . "/conversation/" . $conversation->id;

            if ($settings->discord_enabled && !empty($settings->discord_webhook_url)) {
                $this->sendToDiscord(
                    $settings->discord_webhook_url,
                    "New Reply to Ticket",
                    $conversation_url,
                    "A new reply has been sent by the customer!",
                    $this->compileDiscordFields($conversation, $thread, $customer)
                );
            }

            if ($settings->slack_enabled && !empty($settings->slack_webhook_url)) {
                $this->sendToSlack(
                    $settings,
                    $conversation->subject ?? "New Reply to Ticket",
                    $conversation_url,
                    "A new reply has been sent by the customer!",
                    $thread->getBodyAsText()
                );
            }

            if ($settings->mattermost_enabled && !empty($settings->mattermost_webhook_url)) {
                $this->sendToMattermost(
                    $settings,
                    $conversation->subject ?? "New Reply to Ticket",
                    $conversation_url,
                    "A new reply has been sent by the customer!",
                    $thread->getBodyAsText(),
                    $this->compileSlackAndMattermostFields($conversation, $thread, $customer)
                );
            }
        }, 20, 3);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('notifychat.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'notifychat'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/notifychat');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/notifychat';
        }, \Config::get('view.paths')), [$sourcePath]), 'notifychat');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/notifychat');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'notifychat');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'notifychat');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    public function compileDiscordFields(Conversation $conversation, Thread $thread, Customer $customer): array
    {
        return [
            [
                "name" => "Sender Name",
                "value" => $customer->getFullName(),
                "inline" => true
            ],
            [
                "name" => "Sender Address",
                "value" => $customer->getMainEmail(),
                "inline" => true
            ],
            [
                "name" => "Subject",
                "value" => $conversation->subject,
                "inline" => false
            ],
            [
                "name" => "Body",
                "value" => $thread->getBodyAsText().substr(0, 500)
            ]
        ];
    }

    public function compileSlackAndMattermostFields(Conversation $conversation, Thread $thread, Customer $customer): array
    {
        return [
            [
                "title" => "Sender Name",
                "value" => $customer->getFullName(),
                "short" => true
            ],
            [
                "title" => "Sender Address",
                "value" => $customer->getMainEmail(),
                "short" => true
            ],
            [
                "title" => "Subject",
                "value" => $conversation->subject,
                "short" => true
            ]
        ];
    }


    public function sendToDiscord($webhook_url, $title, $url, $description, $fields) {
        $json_data = json_encode([
            "embeds" => [[
                "title" => $title,
                "url" => $url,
                "type" => "rich",
                "description" => $description,
                "timestamp" => date("c", strtotime("now")),
                "color" => hexdec("3366ff"),
                "fields" => $fields
            ]]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $this->curlRequest($webhook_url, $json_data, 'json');
    }

    public function sendToSlack(NotifyChatSettings $settings, string $title, string $url, string $description, string $body, array $fields) {
        $payload = [
            'attachments' => [
                [
                    'fallback' => $description,
                    'pretext' => $description,
                    'text' => join(PHP_EOL, [
                        '<a href="' . $url . '">' . $title . '</a>',
                        $body,
                        ]),
                    'fields' => $fields
                ],
            ],
        ];

        if (!empty($settings->slack_color_override)) {
            $payload['attachments'][0]['color'] = $settings->slack_color_override;
        }

        if (!empty($settings->slack_channel_override)) {
            $payload['channel'] = $settings->slack_channel_override;
        }

        if (!empty($settings->slack_username_override)) {
            $payload['username'] = $settings->slack_username_override;
        }

        if (!empty($settings->slack_icon_url_override)) {
            $payload['icon_url'] = $settings->slack_icon_url_override;
        }

        if (!empty($settings->slack_icon_emoji_override)) {
            $payload['icon_emoji'] = $settings->slack_icon_emoji_override;
        }

        $json_data = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $this->curlRequest($settings->slack_webhook_url, $json_data, 'json');
    }

    public function sendToMattermost(NotifyChatSettings $settings, string $title, string $url, string $description, string $body, array $fields): void
    {
        $payload = [
            'attachments' => [
                [
                    'fallback' => $description,
                    'pretext' => $description,
                    'text' => $body,
                    'title' => $title,
                    'title_link' => $url,
                    'fields' => $fields
                ],
            ],
        ];

        if (!empty($settings->mattermost_color_override)) {
            $payload['attachments'][0]['color'] = $settings->mattermost_color_override;
        }

        if (!empty($settings->mattermost_channel_override)) {
            $payload['channel'] = $settings->mattermost_channel_override;
        }

        if (!empty($settings->mattermost_username_override)) {
            $payload['username'] = $settings->mattermost_username_override;
        }

        if (!empty($settings->mattermost_icon_url_override)) {
            $payload['icon_url'] = $settings->mattermost_icon_url_override;
        }

        if (!empty($settings->mattermost_icon_emoji_override)) {
            $payload['icon_emoji'] = $settings->mattermost_icon_emoji_override;
        }

        if (!empty($settings->mattermost_priority_level)) {
            $payload['priority'] = [
                'priority' => $settings->mattermost_priority_level
            ];

            if ($settings->mattermost_priority_requested_ack) {
                $payload['priority']["requested_ack"] = true;
            }

            if ($settings->mattermost_priority_level == 'urgent' && $settings->mattermost_priority_urgent_persistent_notifications) {
                $payload['priority']["persistent_notifications"] = true;
            }
        }

        $json_data = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $this->curlRequest($settings->mattermost_webhook_url, $json_data, 'json');
    }

    public function curlRequest(string $webhook_url, string $data, string $dataType = null): void
    {
        try {
            $ch = curl_init($webhook_url);

            $headers = [
                'Content-Length: ' . strlen($data)
            ];

            switch ($dataType) {
                case 'form':
                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    break;

                case 'json':
                default:
                    $headers[] = 'Content-Type: application/json';
                    break;
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, config('app.curl_timeout'));
            curl_setopt($ch, CURLOPT_PROXY, config('app.proxy'));

            if (curl_error($ch)) {
                \Log::error('[Debugging] NotifyChat - curlRequest - curl_error: ' . curl_error($ch));
            }

            curl_exec($ch);
            curl_close($ch);
        }
        catch (\Exception $e) {
            \Log::error('[Debugging] NotifyChat - curlRequest - Exception: ' . $e->getMessage());
        }
    }
}
