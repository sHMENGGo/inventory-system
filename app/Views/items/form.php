<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $item === null ? 'New item' : 'Edit item' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto w-full max-w-2xl">
    <div class="mb-5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Catalogue</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl"><?= $item === null ? 'New item' : 'Edit item' ?></h1>
        <p class="mt-1 text-sm text-slate-500"><?= $item === null ? 'Add a product to the inventory catalogue.' : 'Update the product details and stock level.' ?></p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <?= form_open($action) ?>
            <div class="grid gap-4 sm:grid-cols-[1fr_140px]">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Name</label>
                    <input type="text" id="name" name="name" value="<?= form_value('name', $item['name'] ?? '') ?>" required maxlength="100"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('name') ?>
                </div>

                <div>
                    <label for="stock" class="mb-1.5 block text-sm font-semibold text-slate-700">Stock</label>
                    <input type="number" id="stock" name="stock" value="<?= form_value('stock', $item['stock'] ?? 0) ?>" min="0" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('stock') ?>
                </div>
            </div>

            <div class="mt-4">
                <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="description" name="description" rows="5" maxlength="1000"
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm leading-relaxed text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10"><?= form_value('description', $item['description'] ?? '') ?></textarea>
                <p class="mt-1 text-xs text-slate-500">Optional. Keep it short and useful for other users.</p>
                <?= field_error('description') ?>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    href="<?= site_url('items') ?>">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    Save item
                </button>
            </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
