<?php

if (! function_exists('flag')) {
    /**
     * PostgreSQL sends booleans as true/false or "t"/"f".
     */
    function flag(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 't', 'true'], true);
    }
}

if (! function_exists('status_class')) {
    /**
     * Tone classes for a request status badge.
     */
    function status_class(string $status): string
    {
        return match ($status) {
            'approved' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'received' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
            default    => 'bg-amber-50 text-amber-700 ring-amber-200',
        };
    }
}

if (! function_exists('status_badge')) {
    /**
     * Complete status pill with a dot indicator.
     */
    function status_badge(string $status): string
    {
        return '<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset '
            . status_class($status) . '">'
            . '<span class="size-1.5 rounded-full bg-current"></span>'
            . esc($status)
            . '</span>';
    }
}

if (! function_exists('form_value')) {
    /**
     * Old input when present, otherwise an escaped default. Safe to echo
     * inside value="" and <textarea>.
     */
    function form_value(string $field, mixed $default = ''): string
    {
        return (string) old($field, esc((string) $default));
    }
}

if (! function_exists('field_error')) {
    /**
     * Inline validation message for one field (fed by redirect()->with()).
     */
    function field_error(string $field): string
    {
        $errors = session('errors');

        if (! is_array($errors) || ! isset($errors[$field])) {
            return '';
        }

        return '<p class="mt-1 text-sm text-red-600">' . esc($errors[$field]) . '</p>';
    }
}

if (! function_exists('selected')) {
    /**
     * "selected" attribute helper for <option>.
     */
    function selected(mixed $value, mixed $current): string
    {
        return (string) $value === (string) $current ? ' selected' : '';
    }
}
