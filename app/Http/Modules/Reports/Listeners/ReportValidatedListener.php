<?php

namespace App\Http\Modules\Reports\Listeners;

use App\Http\Modules\Reports\Events\ReportValidatedEvent;
use App\Http\Modules\Reports\Notifications\ReportValidatedNotification;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReportValidatedListener
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
     * @param  \App\Http\Modules\Reports\Events\ReportValidatedEvent  $event
     * @return void
     */
    public function handle(ReportValidatedEvent $event)
    {
        User::find($event->data->idUser)->notify(new ReportValidatedNotification($event->data));
        Categorie::find($event->data->idCategorie)->service->chefService->notify(new ReportValidatedNotification($event->data));
        User::role('responsable')->each(function ($user) use ($event) {
            $user->notify(new ReportValidatedNotification($event->data));
        });
    }
}
