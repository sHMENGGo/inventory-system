<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Users<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Administration</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Users</h1>
        <p class="mt-1 text-sm text-slate-500">Manage accounts and administrator access.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a class="inline-flex flex-1 items-center justify-center rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:flex-none"
            href="<?= site_url('users/new') ?>">New user</a>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-5 py-4">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Accounts</h2>
            <p class="text-xs text-slate-500">Everyone who can sign in to the inventory.</p>
        </div>
        <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
            <?= esc(count($users)) ?> users
        </span>
    </div>

    <?php if ($users === []): ?>
        <div class="flex flex-col items-center gap-2 px-5 py-12 text-center">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </span>
            <p class="text-sm font-medium text-slate-600">No users found</p>
            <a class="text-sm font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('users/new') ?>">Add the first user</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">User</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Role</th>
                        <th class="hidden whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 lg:table-cell">Created</th>
                        <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($users as $user): ?>
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-9 flex-none items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-xs font-bold text-white">
                                        <?= esc(strtoupper(substr(trim($user['name']), 0, 1))) ?>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-semibold text-slate-900"><?= esc($user['name']) ?></div>
                                        <div class="truncate text-xs text-slate-500"><?= esc($user['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-sm">
                                <?php if (flag($user['is_admin'])): ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-200">
                                        <span class="size-1.5 rounded-full bg-current"></span>
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-200">
                                        <span class="size-1.5 rounded-full bg-current"></span>
                                        Member
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="hidden px-5 py-3.5 text-sm text-slate-500 lg:table-cell"><?= esc($user['created_at']) ?></td>
                            <td class="px-5 py-3.5 text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                        href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a>

                                    <?php if ($user['id'] !== auth_user()['id']): ?>
                                        <?= form_open('users/' . $user['id'] . '/delete', [
                                            'class'    => 'm-0 inline',
                                            'onsubmit' => "return confirm('Delete this user?')",
                                        ]) ?>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                                                Delete
                                            </button>
                                        <?= form_close() ?>
                                    <?php endif; ?>
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
