<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Items<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Catalogue</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Items</h1>
        <p class="mt-1 text-sm text-slate-500">Browse available stock and manage the catalogue.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a class="inline-flex flex-1 items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:flex-none"
            href="<?= site_url('requests/new') ?>">New request</a>
        <?php if (auth_is_admin()): ?>
            <a class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:flex-none"
                href="<?= site_url('items/new') ?>">New item</a>
        <?php endif; ?>
    </div>
</div>

<form action="<?= site_url('items') ?>" method="get" class="mb-5">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="search" name="q" value="<?= esc($q ?? '') ?>" placeholder="Search items by name or description..."
                class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-3.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-500/10">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 sm:flex-none">Search</button>
            <?php if (($q ?? '') !== ''): ?>
                <a href="<?= site_url('items') ?>" class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:flex-none">Clear</a>
            <?php endif; ?>
        </div>
    </div>
</form>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-5 py-4">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Inventory catalogue</h2>
            <p class="text-xs text-slate-500"><?= ($q ?? '') !== '' ? 'Search results for "' . esc($q) . '"' : 'Stock on hand for every item.' ?></p>
        </div>
        <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
            <?= esc(count($items)) ?> <?= count($items) === 1 ? 'item' : 'items' ?>
        </span>
    </div>

    <?php if ($items === []): ?>
        <div class="flex flex-col items-center gap-2 px-5 py-12 text-center">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
            </span>
            <p class="text-sm font-medium text-slate-600"><?= ($q ?? '') !== '' ? 'No items match your search' : 'No items found' ?></p>
            <?php if (($q ?? '') !== ''): ?>
                <a class="text-sm font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('items') ?>">Clear search</a>
            <?php elseif (auth_is_admin()): ?>
                <a class="text-sm font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('items/new') ?>">Add the first item</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Item</th>
                        <th class="hidden whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 md:table-cell">Description</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Stock</th>
                        <?php if (auth_is_admin()): ?>
                            <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($items as $item): ?>
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <div class="text-sm font-semibold text-slate-900"><?= esc($item['name']) ?></div>
                                <div class="mt-0.5 line-clamp-2 text-xs text-slate-500 md:hidden"><?= esc($item['description'] ?? '') ?></div>
                            </td>
                            <td class="hidden max-w-md px-5 py-3.5 text-sm text-slate-500 md:table-cell">
                                <div class="line-clamp-2"><?= esc($item['description'] ?? '') ?></div>
                            </td>
                            <td class="px-5 py-3.5 text-sm">
                                <?php if ((int) $item['stock'] === 0): ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-200">
                                        <span class="size-1.5 rounded-full bg-current"></span>
                                        Out of stock
                                    </span>
                                <?php elseif ((int) $item['stock'] < 10): ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                                        <span class="size-1.5 rounded-full bg-current"></span>
                                        <?= esc($item['stock']) ?> low
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                        <span class="size-1.5 rounded-full bg-current"></span>
                                        <?= esc($item['stock']) ?> in stock
                                    </span>
                                <?php endif; ?>
                            </td>
                            <?php if (auth_is_admin()): ?>
                                <td class="px-5 py-3.5 text-sm">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                            href="<?= site_url('items/' . $item['id'] . '/edit') ?>">Edit</a>
                                        <?= form_open('items/' . $item['id'] . '/delete', [
                                            'class'    => 'm-0 inline',
                                            'onsubmit' => "return confirm('Delete this item?')",
                                        ]) ?>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                                                Delete
                                            </button>
                                        <?= form_close() ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
