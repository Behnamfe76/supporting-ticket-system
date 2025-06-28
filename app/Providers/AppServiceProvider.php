<?php

namespace App\Providers;

use App\Repositories\Contracts\ReplyRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\ReplyRepository;
use App\Repositories\TicketRepository;
use App\Services\Contracts\TicketServiceInterface;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReplyRepositoryInterface::class, ReplyRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(TicketServiceInterface::class, TicketService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
