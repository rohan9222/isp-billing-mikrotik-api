<?php

use App\Http\Controllers\AvatarController;
use App\Models\MainSiteData;

/**
 * Created by Md. Jahangir Alam Rohan.
 * User: Md. Jahangir Alam Rohan.
 * Date: 25-Jun-2024
 * Time: 03.01 PM
 */
if (! function_exists('siteUrlSettings')) {
    function siteUrlSettings($key)
    {
        return MainSiteData::getValue($key);
    }
}

if (! function_exists('generate_avatar')) {
    function generate_avatar($name)
    {
        $controller = app(AvatarController::class);

        return $controller->generateAvatar($name);
    }
}

if (! function_exists('hasAccess')) {
    function hasAccess(array $roles = [], array $permissions = []): bool
    {
        $user = auth()->user();

        return $user && (
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) ||
            (method_exists($user, 'hasAnyPermission') && $user->hasAnyPermission($permissions))
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

if (! function_exists('site_image')) {
    /**
     * Correctly resolve site images from public or storage.
     */
    function site_image($path, $fallback = 'images/logo.png')
    {
        if (! $path) return asset($fallback);
        if (str_starts_with($path, 'http')) return $path;

        // Path clean up
        $path = ltrim($path, '/');

        // Check 1: public folder
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // Check 2: storage folder (standard Filament/Laravel location)
        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        // Final fallback: asset with original path
        return asset($path);
    }
}

