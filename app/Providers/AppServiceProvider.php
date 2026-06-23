<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domains\User\Repositories\UserRepositoryInterface::class,
            \App\Domains\User\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Domains\Template\Repositories\TemplateRepositoryInterface::class,
            \App\Domains\Template\Repositories\TemplateRepository::class
        );
        $this->app->bind(
            \App\Domains\Resume\Repositories\ResumeRepositoryInterface::class,
            \App\Domains\Resume\Repositories\ResumeRepository::class
        );
        $this->app->bind(
            \App\Domains\AI\Repositories\AIRepositoryInterface::class,
            \App\Domains\AI\Repositories\AIRepository::class
        );
        $this->app->bind(
            \App\Domains\File\Repositories\FileRepositoryInterface::class,
            \App\Domains\File\Repositories\FileRepository::class
        );
        $this->app->bind(
            \App\Domains\Analytics\Repositories\AnalyticsRepositoryInterface::class,
            \App\Domains\Analytics\Repositories\AnalyticsRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\User\Models\User::class,
            \App\Domains\User\Policies\UserPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\Resume\Models\Resume::class,
            \App\Domains\Resume\Policies\ResumePolicy::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Domains\User\Events\UserRegisteredEvent::class,
            \App\Domains\Analytics\Listeners\LogUserRegistration::class
        );
        \Illuminate\Support\Facades\Event::listen(
            \App\Domains\Resume\Events\ResumeCreatedEvent::class,
            \App\Domains\Analytics\Listeners\LogResumeCreation::class
        );
        \Illuminate\Support\Facades\Event::listen(
            \App\Domains\File\Events\ResumeExportedEvent::class,
            \App\Domains\Analytics\Listeners\LogResumeExport::class
        );
    }
}
