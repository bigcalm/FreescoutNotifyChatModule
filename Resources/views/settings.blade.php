@extends('layouts.app')

@section('title_full', 'Notify Chat - ' . $mailbox->name)

@section('body_attrs')
    @parent data-mailbox_id="{{ $mailbox->id }}"
@endsection

@section('sidebar')
    @include('partials/sidebar_menu_toggle')
    @include('mailboxes/sidebar_menu')
@endsection

@section('content')
    <div class="section-heading">
        Notify Chat - {{ $mailbox->name }}
    </div>
    <div class="col-xs-12">
        <form class="form-horizontal margin-top margin-bottom" method="POST" action="">
            {{ csrf_field() }}

            <fieldset>
                <legend>{{ __("Discord settings") }}</legend>

                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2">
                        <p class="help-block">
                            <a href="https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks"
                               target="_blank">{{ __("Discord incoming webhooks documentation") }}</a>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="discord_enabled"
                           class="col-sm-2 control-label">{{ __("Enable Discord notifications") }}</label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="discord_enabled" id="discord_enabled"
                                           class="onoffswitch-checkbox"
                                           {!! $settings['discord_enabled'] ? "checked" : "" !!}
                                           data-toggle-targets="discord_webhook_url"
                                    >
                                    <label class="onoffswitch-label" for="discord_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Discord webhook URL") }}</label>

                    <div class="col-sm-6">
                        <input name="discord_webhook_url" id="discord_webhook_url" class="form-control"
                               placeholder="https://discord.com/api/webhooks/..."
                               value="{{ $settings['discord_webhook_url'] }}"
                               required="required"
                            {{ $settings['discord_enabled'] ? '' : 'disabled' }}
                        />
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>{{ __("Slack settings") }}</legend>

                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2">
                        <p class="help-block">
                            <a href="https://api.slack.com/messaging/webhooks"
                               target="_blank">{{ __("Slack incoming webhooks documentation") }}</a>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="slack_enabled"
                           class="col-sm-2 control-label">{{ __("Enable Slack notifications") }}</label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="slack_enabled" id="slack_enabled"
                                           class="onoffswitch-checkbox"
                                           data-toggle-targets="slack_webhook_url,slack_color_override,slack_channel_override,slack_username_override,slack_icon_url_override,slack_icon_emoji_override"
                                        {!! $settings['slack_enabled'] ? "checked" : "" !!}
                                    />
                                    <label class="onoffswitch-label" for="slack_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Slack webhook URL") }}</label>

                    <div class="col-sm-6">
                        <input name="slack_webhook_url" id="slack_webhook_url" class="form-control"
                               placeholder="https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX"
                               value="{{ $settings['slack_webhook_url'] }}"
                               required="required"
                            {{ $settings['slack_enabled'] ? '' : 'disabled' }}
                        />
                    </div>
                </div>

                <!-- Optional settings -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Color override") }}</label>

                    <div class="col-sm-6">
                        <input name="slack_color_override" class="form-control" id="slack_color_override"
                               placeholder="#92C5EB"
                               value="{{ $settings['slack_color_override'] }}" {{ $settings['slack_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Channel override") }}</label>

                    <div class="col-sm-6">
                        <input name="slack_channel_override" class="form-control" id="slack_channel_override"
                               placeholder="#channel-name or @user-name"
                               value="{{ $settings['slack_channel_override'] }}" {{ $settings['slack_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Username override") }}</label>

                    <div class="col-sm-6">
                        <input name="slack_username_override" id="slack_username_override"
                               class="form-control"
                               placeholder="freescout-notification"
                               value="{{ $settings['slack_username_override'] }}" {{ $settings['slack_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Icon URL override") }}</label>

                    <div class="col-sm-6">
                        <input name="slack_icon_url_override" id="slack_icon_url_override"
                               class="form-control"
                               placeholder="https://example.com/icon.png"
                               value="{{ $settings['slack_icon_url_override'] }}" {{ $settings['slack_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Icon emoji override") }}</label>

                    <a href="https://github.com/ikatyang/emoji-cheat-sheet/blob/master/README.md" target="_blank">Emoji cheet sheet</a>
                    <div class="col-sm-6">
                        <input name="slack_icon_emoji_override" id="slack_icon_emoji_override"
                               class="form-control" placeholder=":ghost:"
                               value="{{ $settings['slack_icon_emoji_override'] }}" {{ $settings['slack_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>{{ __("Mattermost settings") }}</legend>

                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2">
                        <p class="help-block">
                            <a href="https://developers.mattermost.com/integrate/webhooks/incoming/"
                               target="_blank">{{ __("Mattermost incoming webhooks documentation") }}</a>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mattermost_enabled"
                           class="col-sm-2 control-label">{{ __("Enable Mattermost notifications") }}</label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="mattermost_enabled" id="mattermost_enabled"
                                           class="onoffswitch-checkbox"
                                           data-toggle-targets="mattermost_webhook_url,mattermost_color_override,mattermost_channel_override,mattermost_username_override,mattermost_icon_url_override,mattermost_icon_emoji_override,mattermost_priority_level,mattermost_priority_requested_ack,mattermost_priority_urgent_persistent_notifications"
                                        {!! $settings['mattermost_enabled'] ? "checked" : "" !!}
                                    />
                                    <label class="onoffswitch-label" for="mattermost_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Webhook URL") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_webhook_url" id="mattermost_webhook_url" class="form-control"
                               placeholder="https://your-mattermost-server.com/hooks/xxx-generatedkey-xxx"
                               value="{{ $settings['mattermost_webhook_url'] }}"
                               required="required"
                            {{ $settings['mattermost_enabled'] ? '' : 'disabled' }}
                        />
                    </div>
                </div>

                <!-- Optional settings -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Color override") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_color_override" class="form-control" id="mattermost_color_override"
                               placeholder="#92C5EB"
                               value="{{ $settings['mattermost_color_override'] }}" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Channel override") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_channel_override" class="form-control" id="mattermost_channel_override"
                               placeholder="customer-support"
                               value="{{ $settings['mattermost_channel_override'] }}" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Username override") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_username_override" id="mattermost_username_override"
                               class="form-control"
                               placeholder="freescout-notification"
                               value="{{ $settings['mattermost_username_override'] }}" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Icon URL override") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_icon_url_override" id="mattermost_icon_url_override"
                               class="form-control"
                               placeholder="https://example.com/icon.png"
                               value="{{ $settings['mattermost_icon_url_override'] }}" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Icon emoji override") }}</label>

                    <a href="https://github.com/ikatyang/emoji-cheat-sheet/blob/master/README.md" target="_blank">Emoji cheet sheet</a>
                    <div class="col-sm-6">
                        <input name="mattermost_icon_emoji_override" id="mattermost_icon_emoji_override"
                               class="form-control" placeholder=":ghost:"
                               value="{{ $settings['mattermost_icon_emoji_override'] }}" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Priority level") }}</label>

                    <div class="col-sm-6">
                        <select name="mattermost_priority_level" id="mattermost_priority_level"
                                class="form-control" {{ $settings['mattermost_enabled'] ? '' : 'disabled' }}>
                            <option value="" {{ $settings['mattermost_priority_level'] == '' ? 'selected' : '' }}>
                                Normal
                            </option>
                            <option
                                value="important" {{ $settings['mattermost_priority_level'] == 'important' ? 'selected' : '' }}>
                                Important
                            </option>
                            <option
                                value="urgent" {{ $settings['mattermost_priority_level'] == 'urgent' ? 'selected' : '' }}>
                                Urgent
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mattermost_priority_requested_ack"
                           class="col-sm-2 control-label">
                        {{ __("Enable message acknowledgement") }}
                        (<a href="https://docs.mattermost.com/collaborate/message-priority.html#request-acknowledgements" target="_blank">Enterprise & Professional only</a>)
                    </label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="mattermost_priority_requested_ack"
                                           id="mattermost_priority_requested_ack" class="onoffswitch-checkbox"
                                        {!! $settings['mattermost_priority_requested_ack'] ? "checked" : "" !!} {{ $settings['mattermost_enabled'] ? '' : 'disabled' }}
                                    />
                                    <label class="onoffswitch-label" for="mattermost_priority_requested_ack"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mattermost_priority_urgent_persistent_notifications" class="col-sm-2 control-label">
                        {{ __("Enable persistent messages for urgent priority level") }}
                        (<a href="https://docs.mattermost.com/collaborate/message-priority.html#send-persistent-notifications" target="_blank">Enterprise & Professional only</a>)
                    </label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="mattermost_priority_urgent_persistent_notifications"
                                           id="mattermost_priority_urgent_persistent_notifications"
                                           class="onoffswitch-checkbox"
                                        {!! $settings['mattermost_priority_urgent_persistent_notifications'] ? "checked" : "" !!} {{ $settings['mattermost_enabled'] ? '' : 'disabled' }}
                                    />
                                    <label class="onoffswitch-label"
                                           for="mattermost_priority_urgent_persistent_notifications"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group margin-top margin-bottom">
                <div class="col-sm-6 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __("Save") }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset(\Module::getPublicPath(NOTIFY_CHAT_MODULE).'/js/module.js') }}"></script>
@endsection

@section('body_bottom')
    @parent
@endsection
