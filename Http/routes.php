<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\NotifyChat\Http\Controllers'], function()
{
    Route::get('/mailbox/notify-chat-settings/{mailbox_id}', ['uses' => 'NotifyChatModuleController@settings', 'middleware' => ['auth', 'roles'], 'roles' => ['admin']])->name('notify-chat.settings');
    Route::post('/mailbox/notify-chat-settings/{mailbox_id}', ['uses' => 'NotifyChatModuleController@saveSettings', 'middleware' => ['auth', 'roles'], 'roles' => ['admin']]);
});
