<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Models\Sale;
use App\Observers\SaleObserver;
use App\Models\ProductUnit;
use App\Observers\ProductUnitObserver;



class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
      
    }
    
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Sale::observe(SaleObserver::class);
        ProductUnit::observe(ProductUnitObserver::class);
    }
}
