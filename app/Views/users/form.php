<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $user === null ? 'New user' : 'Edit user' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto w-full max-w-2xl">
    <div class="mb-5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Administration</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl"><?= $user === null ? 'New user' : 'Edit user' ?></h1>
        <p class="mt-1 text-sm text-slate-500"><?= $user === null ? 'Create an account for a team member.' : 'Update account details and access level.' ?></p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <?= form_open($action) ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Name</label>
                    <input type="text" id="name" name="name" value="<?= form_value('name', $user['name'] ?? '') ?>" required maxlength="100"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('name') ?>
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="<?= form_value('email', $user['email'] ?? '') ?>" required maxlength="100"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('email') ?>
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700"><?= $user === null ? 'Password' : 'New password' ?></label>
                    <input type="password" id="password" name="password" autocomplete="new-password" <?= $user === null ? 'required minlength="8"' : 'minlength="8"' ?>
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <p class="mt-1 text-xs text-slate-500"><?= $user === null ? 'At least 8 characters.' : 'Leave blank to keep the current password.' ?></p>
                    <?= field_error('password') ?>
                </div>

                <div>
                    <label for="password_confirm" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm password</label>
                    <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" <?= $user === null ? 'required' : '' ?>
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('password_confirm') ?>
                </div>
            </div>

            <?php $isSelf = $user !== null && $user['id'] === auth_user()['id']; ?>
            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="checkbox" name="is_admin" value="1"
                        <?= $isSelf ? 'checked' : set_checkbox('is_admin', '1', $user !== null && flag($user['is_admin'])) ?>
                        <?= $isSelf ? 'disabled' : '' ?>
                        class="mt-0.5 size-4 rounded border-slate-300 text-teal-700 focus:ring-teal-500 disabled:bg-slate-100">
                    <span>
                        <span class="block text-sm font-semibold text-slate-800">Administrator access</span>
                        <span class="mt-0.5 block text-xs text-slate-500">
                            <?= $isSelf
                                ? 'You cannot remove your own administrator rights.'
                                : 'Admins can manage items, users, and approve or receive requests.' ?>
                        </span>
                    </span>
                </label>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    href="<?= site_url('users') ?>">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    Save user
                </button>
            </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
