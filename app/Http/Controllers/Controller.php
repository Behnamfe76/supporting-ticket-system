<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    /**
     * Authorize the current user for the given permission.
     *
     * @param array|string $permission The permission(s) to check
     * @throws AuthorizationException
     */
    protected function authorize(array|string $permission): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthorizationException('User not authenticated.');
        }

        // Handle an array of permissions (check if user has ANY of them)
        if (is_array($permission) && !$user->hasAnyPermission($permission)) {
            abort(403);
        }

        // Handle single permission string
        if (is_string($permission) && !$user->hasPermissionTo($permission)) {
            abort(403);
        }
    }
}
