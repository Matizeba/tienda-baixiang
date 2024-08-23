<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
      
    }
    
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Product::observe(ProductObserver::class);
    }
}
