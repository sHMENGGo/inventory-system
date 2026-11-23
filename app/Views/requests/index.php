<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Requests<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Workflow</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Requests</h1>
        <p class="mt-1 text-sm text-slate-500">Track requests and move them through approval and receiving.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a class="inline-flex flex-1 items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:flex-none"
            href="<?= site_url('requests/new') ?>">New request</a>
    </div>
</div>

<div class="-mx-4 mb-5 flex items-center gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0">
    <a class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition <?= $status === '' ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>"
        href="<?= site_url('requests') ?>">All</a>
    <?php foreach ($statuses as $option): ?>
        <a class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium capitalize transition <?= $status === $option ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>"
            href="<?= site_url('requests?status=' . $option) ?>"><?= esc($option) ?></a>
    <?php endforeach; ?>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-5 py-4">
        <div>
            <h2 class="text-sm font-semibold text-slate-900"><?= $status === '' ? 'All requests' : ucfirst($status) . ' requests' ?></h2>
            <p class="text-xs text-slate-500"><?= auth_is_admin() ? 'Requests from every user.' : 'Requests you created.' ?></p>
        </div>
        <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
            <?= esc(count($requests)) ?>
        </span>
    </div>

    <?php if ($requests === []): ?>
        <div class="flex flex-col items-center gap-2 px-5 py-12 text-center">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25 4.5H6.75A2.25 2.25 0 0 1 4.5 18.75V5.25A2.25 2.25 0 0 1 6.75 3h6.879a2.25 2.25 0 0 1 1.59.659l3.122 3.122a2.25 2.25 0 0 1 .659 1.59v10.379a2.25 2.25 0 0 1-2.25 2.25Z"/>
                </svg>
            </span>
            <p class="text-sm font-medium text-slate-600">No requests found</p>
            <p class="text-xs text-slate-500">Try another status filter or create a new request.</p>
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
                        <th class="hidden whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 md:table-cell">Submitted</th>
                        <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($requests as $request): ?>
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <a class="text-sm font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('requests/' . $request['id']) ?>">
                                    <?= esc($request['item_name']) ?>
                                </a>
                                <?php if (auth_is_admin()): ?>
                                    <div class="mt-0.5 text-xs text-slate-500 sm:hidden">by <?= esc($request['user_name']) ?></div>
                                <?php endif; ?>
                            </td>
                            <?php if (auth_is_admin()): ?>
                                <td class="hidden px-5 py-3.5 text-sm text-slate-700 sm:table-cell"><?= esc($request['user_name']) ?></td>
                            <?php endif; ?>
                            <td class="px-5 py-3.5 text-sm text-slate-700"><?= esc($request['quantity']) ?></td>
                            <td class="px-5 py-3.5 text-sm"><?= status_badge($request['status']) ?></td>
                            <td class="hidden px-5 py-3.5 text-sm text-slate-500 md:table-cell"><?= esc($request['created_at']) ?></td>
                            <td class="px-5 py-3.5 text-sm">
                                <div class="flex justify-end">
                                    <a class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                        href="<?= site_url('requests/' . $request['id']) ?>">
                                        View
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
