<?php

namespace App\Http\Modules\Reports\Listeners;

use App\Http\Modules\Reports\Events\ReportCreatedEvent;
use App\Models\User;
use App\Http\Modules\Reports\Notifications\ReportCreatedNotification;

class ReportCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Http\Modules\Reports\Events\ReportCreatedEvent  $event
     * @return void
     */
    public function handle(ReportCreatedEvent $event)
    {

        User::role('responsable')->each(function ($user) use ($event) {
            $user->notify(new ReportCreatedNotification($event->data));
        });
    }
}
