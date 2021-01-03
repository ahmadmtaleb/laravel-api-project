<?php

namespace App\Providers;
use App\Models\User;
use App\Models\Items;
use App\Models\Comments;
use App\Models\Images;



use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('item-owner', function (User $user, Items $item) {
            return $user->id === $item->user_id;
        });
        // Gate::define('image-owner', function (User $user, Items $item, Images $image) {
        //     return $image->item_id === $item->id && $item->user_id === $user->id;
        // });
        Gate::define('comment-owner', function (User $user, Comments $comment) {
            return $user->id === $comment->user_id;
        });
    }
}
