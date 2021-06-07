<?php

namespace App\Providers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Listeners\SendTaskCreatedEmailToUser;
use App\Listeners\SendTaskDeletedEmailToUser;
use App\Listeners\SendTaskUpdatedEmailToUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskCreated::class => [
            SendTaskCreatedEmailToUser::class,
        ],
        TaskUpdated::class => [
            SendTaskUpdatedEmailToUser::class
        ],
        TaskDeleted::class => [
            SendTaskDeletedEmailToUser::class
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
}
