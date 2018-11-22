<?php

Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

Route::post('api/:version/order','api/:version.Recharge/placeOrder');
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');

Route::get('api/:version/projects','api/:version.Project/getProjects');
Route::get('api/:version/project','api/:version.Project/getProject');

Route::get('api/:version/recharges','api/:version.Recharge/getRecharges');
Route::get('api/:version/recharge','api/:version.Recharge/getRecharge');

Route::get('api/:version/notices','api/:version.Notice/getNotices');
Route::get('api/:version/notice','api/:version.Notice/getNotice');

Route::get('api/:version/devices','api/:version.Device/getDevices');
Route::get('api/:version/device','api/:version.Device/getDevice');
Route::get('api/:version/permission','api/:version.Device/checkPermission');
Route::post('api/:version/state','api/:version.Device/getCurrentState');
Route::post('api/:version/control','api/:version.Device/control');
Route::post('api/:version/logs','api/:version.Device/createDeviceLogs');

Route::get('api/:version/user','api/:version.User/getUser');
Route::get('api/:version/wallet','api/:version.User/getWallet');

Route::post('api/:version/logs','api/:version.Device/createLogs');