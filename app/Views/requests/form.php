<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $request === null ? 'New request' : 'Edit request' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto w-full max-w-2xl">
    <div class="mb-5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Workflow</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl"><?= $request === null ? 'New request' : 'Edit request' ?></h1>
        <p class="mt-1 text-sm text-slate-500">
            <?= $request === null ? 'Choose an item and the quantity you need.' : 'Only the quantity can be changed while the request is pending.' ?>
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <?= form_open($action) ?>
            <?php if ($request === null): ?>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="item_search" class="mb-1.5 block text-sm font-semibold text-slate-700">Item</label>
                        <?php
                        $chosenItem = (string) old('item_id', '', false);
                        $chosenName = '';
                        foreach ($items as $item) {
                            if ((string) $item['id'] === $chosenItem) {
                                $chosenName = $item['name'] . ' (' . $item['stock'] . ' in stock)';
                                break;
                            }
                        }
                        ?>
                        <div class="relative" id="item-picker">
                            <input type="hidden" name="item_id" id="item_id" value="<?= esc($chosenItem) ?>">
                            <input type="text" id="item_search" autocomplete="off" placeholder="Search for an item..."
                                value="<?= esc($chosenName) ?>"
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 pr-10 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-slate-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>

                            <div id="item-options" class="absolute z-20 mt-1 hidden max-h-60 w-full overflow-auto rounded-xl border border-slate-200 bg-white p-1 shadow-lg">
                                <?php foreach ($items as $item): ?>
                                    <?php $label = $item['name'] . ' (' . $item['stock'] . ' in stock)'; ?>
                                    <button type="button"
                                        class="item-option flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-teal-50 hover:text-teal-900"
                                        data-id="<?= esc($item['id']) ?>"
                                        data-label="<?= esc($label, 'attr') ?>"
                                        data-search="<?= esc(strtolower($item['name'] . ' ' . ($item['description'] ?? '')), 'attr') ?>">
                                        <span class="font-medium"><?= esc($item['name']) ?></span>
                                        <span class="ml-3 shrink-0 text-xs text-slate-500"><?= esc($item['stock']) ?> in stock</span>
                                    </button>
                                <?php endforeach; ?>
                                <div id="item-empty" class="hidden px-3 py-2 text-sm text-slate-500">No items match your search.</div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Type to search, then pick an item from the list.</p>
                        <?= field_error('item_id') ?>
                    </div>

                    <div>
                        <label for="quantity" class="mb-1.5 block text-sm font-semibold text-slate-700">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="<?= form_value('quantity', 1) ?>" required
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                        <?= field_error('quantity') ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Item</label>
                        <input type="text" value="<?= esc($request['item_name']) ?>" disabled
                            class="block w-full rounded-xl border border-slate-300 bg-slate-100 px-3.5 py-2.5 text-sm text-slate-600 shadow-sm">
                        <p class="mt-1 text-xs text-slate-500">The item cannot be changed after a request is created.</p>
                    </div>

                    <div>
                        <label for="quantity" class="mb-1.5 block text-sm font-semibold text-slate-700">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="<?= form_value('quantity', $request['quantity'] ?? 1) ?>" required
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                        <?= field_error('quantity') ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-6 flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    href="<?= $request === null ? site_url('requests') : site_url('requests/' . $request['id']) ?>">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <?= $request === null ? 'Submit request' : 'Save changes' ?>
                </button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?php if ($request === null): ?>
<script>
(() => {
    const picker = document.getElementById('item-picker');
    if (!picker) return;
    const search = document.getElementById('item_search');
    const hidden = document.getElementById('item_id');
    const list = document.getElementById('item-options');
    const empty = document.getElementById('item-empty');
    const options = [...picker.querySelectorAll('.item-option')];

    function open() { list.classList.remove('hidden'); }
    function close() { list.classList.add('hidden'); }

    function filter() {
        const q = search.value.toLowerCase().trim();
        let visible = 0;
        options.forEach((btn) => {
            const match = q === '' || btn.dataset.search.includes(q) || btn.dataset.label.toLowerCase().includes(q);
            btn.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        empty.classList.toggle('hidden', visible !== 0);
    }

    search.addEventListener('focus', () => { filter(); open(); });
    search.addEventListener('input', () => {
        hidden.value = '';
        filter();
        open();
    });

    options.forEach((btn) => {
        btn.addEventListener('click', () => {
            hidden.value = btn.dataset.id;
            search.value = btn.dataset.label;
            close();
        });
    });

    document.addEventListener('click', (e) => {
        if (!picker.contains(e.target)) close();
    });

    search.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
})();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
