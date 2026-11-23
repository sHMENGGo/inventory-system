<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 p-6 text-white shadow-xl sm:p-8">
    <div class="absolute -right-20 -top-24 size-64 rounded-full bg-teal-400/20 blur-3xl"></div>
    <div class="absolute -bottom-24 -left-10 size-48 rounded-full bg-emerald-400/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-300">Dashboard</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Welcome back, <?= esc(auth_user()['name']) ?></h1>
            <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-300">
                Here is the latest stock and request activity. Create a request or jump into the catalogue.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-slate-900"
                href="<?= site_url('requests/new') ?>">New request</a>
            <a class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10"
                href="<?= site_url('items') ?>">Browse items</a>
        </div>
    </div>
</section>

<?php
$statsGrid = auth_is_admin()
    ? 'grid-cols-2 xl:grid-cols-4'
    : 'grid-cols-2 sm:grid-cols-3';
?>

<div class="mt-6 grid gap-4 <?= $statsGrid ?>">
    <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Items</div>
                <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900"><?= esc($stats['items']) ?></div>
            </div>
            <span class="flex size-10 items-center justify-center rounded-xl bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
            </span>
        </div>
        <p class="mt-3 text-xs text-slate-500">In the catalogue</p>
    </div>

    <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Requests</div>
                <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900"><?= esc($stats['requests']) ?></div>
            </div>
            <span class="flex size-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
                </svg>
            </span>
        </div>
        <p class="mt-3 text-xs text-slate-500"><?= auth_is_admin() ? 'Across all users' : 'Created by you' ?></p>
    </div>

    <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending</div>
                <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900"><?= esc($stats['pending']) ?></div>
            </div>
            <span class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </span>
        </div>
        <p class="mt-3 text-xs text-slate-500">Awaiting approval</p>
    </div>

    <?php if (isset($stats['users'])): ?>
        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Users</div>
                    <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900"><?= esc($stats['users']) ?></div>
                </div>
                <span class="flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-700 ring-1 ring-inset ring-violet-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs text-slate-500">Registered accounts</p>
        </div>
    <?php endif; ?>
</div>

<div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center gap-3 border-b border-slate-200 px-5 py-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Recent requests</h2>
            <p class="text-xs text-slate-500">The latest activity across the workflow.</p>
        </div>
        <a class="ml-auto text-sm font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('requests') ?>">View all</a>
    </div>

    <?php if ($recent === []): ?>
        <div class="flex flex-col items-center gap-2 px-5 py-12 text-center">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25 4.5H6.75A2.25 2.25 0 0 1 4.5 18.75V5.25A2.25 2.25 0 0 1 6.75 3h6.879a2.25 2.25 0 0 1 1.59.659l3.122 3.122a2.25 2.25 0 0 1 .659 1.59v10.379a2.25 2.25 0 0 1-2.25 2.25Z"/>
                </svg>
            </span>
            <p class="text-sm font-medium text-slate-600">No requests yet</p>
            <p class="text-xs text-slate-500">Create your first request to get started.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Item</th>
                        <?php if (auth_is_admin()): ?>
                            <th class="hidden whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 sm:table-cell">Requested by</th>
                        <?php endif; ?>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Qty</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="hidden whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 md:table-cell">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($recent as $request): ?>
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5 text-sm font-medium text-slate-900">
                                <a class="text-teal-700 hover:text-teal-800" href="<?= site_url('requests/' . $request['id']) ?>">
                                    <?= esc($request['item_name']) ?>
                                </a>
                            </td>
                            <?php if (auth_is_admin()): ?>
                                <td class="hidden px-5 py-3.5 text-sm text-slate-700 sm:table-cell"><?= esc($request['user_name']) ?></td>
                            <?php endif; ?>
                            <td class="px-5 py-3.5 text-sm text-slate-700"><?= esc($request['quantity']) ?></td>
                            <td class="px-5 py-3.5 text-sm"><?= status_badge($request['status']) ?></td>
                            <td class="hidden px-5 py-3.5 text-sm text-slate-500 md:table-cell"><?= esc($request['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
