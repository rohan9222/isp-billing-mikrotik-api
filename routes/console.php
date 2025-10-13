<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\ScheduledTasksController;
use App\Livewire\MikrotikSync;

Schedule::call(function () {
    app(ScheduledTasksController::class)->createMonthlyBill();
})->lastDayOfMonth('23:45')->timezone('Asia/Dhaka');

Schedule::call(function () {
    app(ScheduledTasksController::class)->allCustomersMonthlyBillSMS();
})->monthlyOn(1, '10:00')->timezone('Asia/Dhaka');

Schedule::call(function () {
    app(ScheduledTasksController::class)->createAlert();
})->dailyAt('08:00')->timezone('Asia/Dhaka');

Schedule::call(function () {
    app(ScheduledTasksController::class)->userDisable();
})->dailyAt('08:30')->timezone('Asia/Dhaka');

Schedule::call(function () {
    app(MikrotikSync::class)->allSync();
})->daily()->timezone('Asia/Dhaka');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
