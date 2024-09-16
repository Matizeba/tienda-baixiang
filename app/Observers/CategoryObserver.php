<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     */
    public function creating(category $category): void
    {
        $category->userId = Auth::check() ? Auth::id() : 0; 
    }

    /**
     * Handle the category "updated" event.
     */
    public function updating(category $category): void
    {
        $category->userId = Auth::check() ? Auth::id() : 0; 
    }
    

    /**
     * Handle the Product "updated" event.
     */
    

    /**
     * Handle the category "deleted" event.
     */
    public function deleted(category $category): void
    {
        //
    }

    /**
     * Handle the category "restored" event.
     */
    public function restored(category $category): void
    {
        //
    }

    /**
     * Handle the category "force deleted" event.
     */
    public function forceDeleted(category $category): void
    {
        //
    }
}
