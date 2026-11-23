<?php

/**
 * Small wrappers so views can ask about the current user without touching
 * the session directly.
 */

if (! function_exists('auth_user')) {
    /**
     * @return array<string, mixed>|null
     */
    function auth_user(): ?array
    {
        return service('auth')->user();
    }
}

if (! function_exists('auth_check')) {
    function auth_check(): bool
    {
        return service('auth')->check();
    }
}

if (! function_exists('auth_is_admin')) {
    function auth_is_admin(): bool
    {
        return service('auth')->isAdmin();
    }
}
