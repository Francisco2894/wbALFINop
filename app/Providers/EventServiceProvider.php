<?php

namespace wbALFINop\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
      'Illuminate\Auth\Events\Login' => [
      'wbALFINop\Listeners\SuccessfullLogin',
        ],
      'Illuminate\Auth\Events\Logout' => [
      'wbALFINop\Listeners\SuccessfullLogout',
        ],

      'wbALFINop\Events\UserNewPassword' => [
        'wbALFINop\Listeners\SendNewPassword',
          ],
      'wbALFINop\Events\UserNew' => [
        'wbALFINop\Listeners\SendNewUser',
          ],
      'wbALFINop\Events\SendOffer' => [
        'wbALFINop\Listeners\SendMessage',
          ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
