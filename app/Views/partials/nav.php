<?php
$path = uri_string();

$navLink = static function (string $prefix) use ($path): string {
    $active = $prefix === '' ? $path === '' : str_starts_with($path, $prefix);

    return $active
        ? 'bg-white/10 text-white shadow-sm'
        : 'text-slate-300 hover:bg-white/5 hover:text-white';
};
?>

<nav class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/95 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3 sm:px-6">
        <a class="flex items-center gap-2.5" href="<?= site_url('/') ?>">
            <span class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-br from-teal-300 to-emerald-500 text-slate-950 shadow-lg shadow-teal-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
            </span>
            <span class="text-base font-bold tracking-tight text-white">Inventory</span>
        </a>

        <?php if (auth_check()): ?>
            <div class="ml-4 hidden items-center gap-1 md:flex">
                <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('requests') ?>"
                    href="<?= site_url('requests') ?>">Requests</a>
                <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('items') ?>"
                    href="<?= site_url('items') ?>">Items</a>
                <?php if (auth_is_admin()): ?>
                    <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('users') ?>"
                        href="<?= site_url('users') ?>">Users</a>
                <?php endif; ?>
            </div>

            <div class="ml-auto hidden items-center gap-3 md:flex">
                <div class="text-right">
                    <div class="text-sm font-semibold leading-tight text-white"><?= esc(auth_user()['name']) ?></div>
                    <div class="text-xs text-slate-400"><?= auth_is_admin() ? 'Administrator' : 'Member' ?></div>
                </div>
                <?= form_open('logout', ['class' => 'm-0']) ?>
                    <button type="submit" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-slate-200 transition hover:border-white/20 hover:bg-white/10 hover:text-white">
                        Log out
                    </button>
                <?= form_close() ?>
            </div>

            <details class="relative ml-auto md:hidden">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10 [&::-webkit-details-marker]:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    Menu
                </summary>

                <div class="absolute right-0 z-50 mt-2 w-64 rounded-2xl border border-white/10 bg-slate-900 p-2 shadow-2xl">
                    <div class="px-3 py-2">
                        <div class="text-sm font-semibold text-white"><?= esc(auth_user()['name']) ?></div>
                        <div class="text-xs text-slate-400"><?= auth_is_admin() ? 'Administrator' : 'Member' ?></div>
                    </div>

                    <div class="flex flex-col gap-0.5">
                        <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('requests') ?>"
                            href="<?= site_url('requests') ?>">Requests</a>
                        <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('items') ?>"
                            href="<?= site_url('items') ?>">Items</a>
                        <?php if (auth_is_admin()): ?>
                            <a class="rounded-lg px-3 py-2 text-sm font-medium transition <?= $navLink('users') ?>"
                                href="<?= site_url('users') ?>">Users</a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-1 border-t border-white/10 pt-1">
                        <?= form_open('logout', ['class' => 'm-0']) ?>
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                                Log out
                            </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </details>
        <?php else: ?>
            <div class="ml-auto flex items-center gap-2">
                <a class="rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white"
                    href="<?= site_url('login') ?>">Log in</a>
                <a class="rounded-lg bg-white px-3.5 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-100"
                    href="<?= site_url('register') ?>">Register</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
