<?php

namespace Modules\NotifyChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Mailbox;
use Modules\NotifyChat\Entities\NotifyChatSettings;

class NotifyChatModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('notifychat::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notifychat::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('notifychat::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('notifychat::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


    public function settings($mailbox_id) {
        $mailbox = Mailbox::findOrFail($mailbox_id);

        $settings = NotifyChatSettings::find($mailbox_id);

        if (empty($settings)) {
            $settings['mailbox_id'] = $mailbox_id;
            $settings["discord_enabled"] = false;
            $settings['discord_webhook_url'] = "";

            $settings["slack_enabled"] = false;
            $settings['slack_webhook_url'] = "";
            $settings['slack_color_override'] = "";
            $settings['slack_channel_override'] = "";
            $settings['slack_username_override'] = "";
            $settings['slack_icon_url_override'] = "";
            $settings['slack_icon_emoji_override'] = "";

            $settings["mattermost_enabled"] = false;
            $settings['mattermost_color_override'] = "";
            $settings['mattermost_webhook_url'] = "";
            $settings['mattermost_channel_override'] = "";
            $settings['mattermost_username_override'] = "";
            $settings['mattermost_icon_url_override'] = "";
            $settings['mattermost_icon_emoji_override'] = "";
            $settings['mattermost_priority_level'] = "";
            $settings['mattermost_priority_requested_ack'] = false;
            $settings['mattermost_priority_urgent_persistent_notifications'] = false;
        }

        return view('notifychat::settings', [
            'mailbox'   => $mailbox,
            'settings'  => $settings
        ]);
    }

    public function saveSettings($mailbox_id, Request $request) {
        NotifyChatSettings::updateOrCreate(
            ['mailbox_id' => $mailbox_id],
            [
                'discord_enabled' => isset($_POST['discord_enabled']),
                'discord_webhook_url' => $request->get("discord_webhook_url"),

                'slack_enabled' => isset($_POST['slack_enabled']),
                'slack_webhook_url' => $request->get("slack_webhook_url"),
                'slack_color_override' => !empty($request->get("slack_color_override")) && ($request->get("slack_color_override"))[0] != "#"
                    ? "#" . $request->get("slack_color_override")
                    : $request->get("slack_color_override"),
                'slack_channel_override' => $request->get("slack_channel_override"),
                'slack_username_override' => $request->get("slack_username_override"),
                'slack_icon_url_override' => $request->get("slack_icon_url_override"),
                'slack_icon_emoji_override' => $request->get("slack_icon_emoji_override"),

                'mattermost_enabled' => isset($_POST['mattermost_enabled']),
                'mattermost_webhook_url' => $request->get("mattermost_webhook_url"),
                'mattermost_color_override' => !empty($request->get("mattermost_color_override")) && ($request->get("mattermost_color_override"))[0] != "#"
                    ? "#" . $request->get("mattermost_color_override")
                    : $request->get("mattermost_color_override"),
                'mattermost_channel_override' => $request->get("mattermost_channel_override"),
                'mattermost_username_override' => $request->get("mattermost_username_override"),
                'mattermost_icon_url_override' => $request->get("mattermost_icon_url_override"),
                'mattermost_icon_emoji_override' => $request->get("mattermost_icon_emoji_override"),
                'mattermost_priority_level' => $request->get("mattermost_priority_level"),
                'mattermost_priority_requested_ack' => isset($_POST['mattermost_priority_requested_ack']),
                'mattermost_priority_urgent_persistent_notifications' => isset($_POST['mattermost_priority_urgent_persistent_notifications'])
            ]
        );

        return redirect()->route('notifychat.settings', ['mailbox_id' => $mailbox_id]);
    }
}
