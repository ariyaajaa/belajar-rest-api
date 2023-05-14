<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Recipe;
use App\Observers\RecipeObserver;
use App\Models\Tool;
use App\Observers\ToolObserver;
use App\Models\Ingredients;
use App\Observers\IngredientsObserver;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Recipe::observe(RecipeObserver::class);
        Tool::observe(ToolObserver::class);
        Ingredients::observe(IngredientsObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
}
