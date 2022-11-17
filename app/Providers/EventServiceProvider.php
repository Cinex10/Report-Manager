<?php

namespace App\Providers;

use App\Listeners\SendNewReportNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendNewReportNotification::class,
        ],

        \App\Http\Modules\Reports\Events\ReportCreatedEvent::class => [
            \App\Http\Modules\Reports\Listeners\ReportCreatedListener::class,
        ],
        \App\Http\Modules\Reports\Events\ReportValidatedEvent::class => [
            \App\Http\Modules\Reports\Listeners\ReportValidatedListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
