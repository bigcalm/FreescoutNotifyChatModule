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
                    <label for="slack_enabled"
                           class="col-sm-2 control-label">{{ __("Enable Slack notifications") }}</label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="slack_enabled" id="slack_enabled"
                                           class="onoffswitch-checkbox"
                                           data-toggle-targets="slack_webhook_url"
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
                               placeholder="https://discord.com/api/webhooks/..."
                               value="{{ $settings['slack_webhook_url'] }}"
                               required="required"
                            {{ $settings['slack_enabled'] ? '' : 'disabled' }}
                        />
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>{{ __("Mattermost settings") }}</legend>

                <div class="form-group">
                    <label for="mattermost_enabled" class="col-sm-2 control-label">{{ __("Enable Mattermost notifications") }}</label>

                    <div class="col-sm-6">
                        <div class="controls">
                            <div class="onoffswitch-wrap">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="mattermost_enabled" id="mattermost_enabled" class="onoffswitch-checkbox"
                                        {!! $settings['mattermost_enabled'] ? "checked" : "" !!}
                                    >
                                    <label class="onoffswitch-label" for="mattermost_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __("Mattermost webhook URL") }}</label>

                    <div class="col-sm-6">
                        <input name="mattermost_webhook_url" class="form-control" placeholder="https://discord.com/api/webhooks/..." value="{{ $settings['mattermost_webhook_url'] }}" required />
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
@endsection

@section('body_bottom')
    @parent
@endsection
