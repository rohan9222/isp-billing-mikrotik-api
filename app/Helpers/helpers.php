<?php

/**
 * Created by Md. Jahangir Alam Rohan.
 * User: Md. Jahangir Alam Rohan.
 * Date: 25-Jun-2024
 * Time: 03.01 PM
 */

 use App\Models\SiteSetting;
 
 if (!function_exists('siteUrlSettings')) {
    function siteUrlSettings($key)
    {
        $settings = SiteSetting::first(); // Always fetch latest from DB
        return $settings->{$key} ?? null;
    }
}

if (! function_exists('generate_avatar')) {
    function generate_avatar($name)
    {
        $controller = app(App\Http\Controllers\AvatarController::class);

        return $controller->generateAvatar($name);
    }
}

if (! function_exists('hasAccess')) {
    function hasAccess(array $roles = [], array $permissions = []): bool
    {
        $user = auth()->user();

        return $user && (
            $user->hasAnyRole($roles) ||
            $user->hasAnyPermission($permissions)
        );
    }
}

if (! function_exists('abortIfNoAccess')) {
    function abortIfNoAccess(array $roles = [], array $permissions = [], string $message = 'You do not have permission.'): bool
    {
        if (! hasAccess($roles, $permissions)) {
            flash()->error($message);

            return true;
        }

        return false;
    }
}

if (! function_exists('warningIfNoAccess')) {
    function warningIfNoAccess(array $roles = [], array $permissions = [], string $message = 'You do not have permission.'): bool
    {
        if (! hasAccess($roles, $permissions)) {
            flash()->warning($message);

            return true;
        }

        return false;
    }
}