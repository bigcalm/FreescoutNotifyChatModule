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
        return view('notify-chat::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notify-chat::create');
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
        return view('notify-chat::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('notify-chat::edit');
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
            $settings["enabled"] = false;
            $settings['webhook_url'] = "";
        }

        return view('notify-chat::settings', [
            'mailbox'   => $mailbox,
            'settings'  => $settings
        ]);
    }

    public function saveSettings($mailbox_id, Request $request) {
        NotifyChatSettings::updateOrCreate(
            ['mailbox_id' => $mailbox_id],
            [
                'enabled' => isset($_POST['enabled']),
                'webhook_url' => $request->get("webhook_url")
            ]
        );

        return redirect()->route('notify-chat.settings', ['mailbox_id' => $mailbox_id]);
    }
}
