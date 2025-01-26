<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\NotifyChat\Http\Controllers'], function()
{
    Route::get('/mailbox/notifychat-settings/{mailbox_id}', ['uses' => 'NotifyChatModuleController@settings', 'middleware' => ['auth', 'roles'], 'roles' => ['admin']])->name('notifychat.settings');
    Route::post('/mailbox/notifychat-settings/{mailbox_id}', ['uses' => 'NotifyChatModuleController@saveSettings', 'middleware' => ['auth', 'roles'], 'roles' => ['admin']]);
});
