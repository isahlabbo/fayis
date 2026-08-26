<?php

namespace App\Providers;

use App\Models\SectionClassStudent;
use App\Services\Finance\ApplyAdvancePayments;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SectionClassStudent::created(function (SectionClassStudent $enrolment) {
            app(ApplyAdvancePayments::class)->handle($enrolment);
        });
    }
}
