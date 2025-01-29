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
            $settings["mattermost_enabled"] = false;
            $settings['mattermost_webhook_url'] = "";
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
                'mattermost_enabled' => isset($_POST['mattermost_enabled']),
                'mattermost_webhook_url' => $request->get("mattermost_webhook_url"),
            ]
        );

        return redirect()->route('notifychat.settings', ['mailbox_id' => $mailbox_id]);
    }
}
