<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-generate monthly invoices every 1st of the month at 12:00 AM
Schedule::call(function () {
    app(\App\Http\Controllers\InvoiceController::class)->generateMonthlyInvoices();
})->monthlyOn(1, '00:00');

Carbon::setTestNow(Carbon::create(2026, 5, 1));
app(\App\Http\Controllers\InvoiceController::class)->generateMonthlyInvoices();
Carbon::setTestNow();