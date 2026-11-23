<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto grid w-full max-w-5xl items-stretch gap-8 lg:grid-cols-2">
    <?= $this->include('partials/auth-brand') ?>

    <div class="flex items-center">
        <div class="w-full rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex size-12 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                </svg>
            </div>

            <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900">Create an account</h1>
            <p class="mt-1 text-sm text-slate-500">New accounts can request items. An administrator can upgrade you later.</p>

            <?= form_open('register', ['class' => 'mt-6']) ?>
                <div class="mb-4">
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Name</label>
                    <input type="text" id="name" name="name" value="<?= form_value('name') ?>" required maxlength="100" autocomplete="name"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('name') ?>
                </div>

                <div class="mb-4">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="<?= form_value('email') ?>" required maxlength="100" autocomplete="email"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('email') ?>
                </div>

                <div class="mb-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                        <p class="mt-1 text-xs text-slate-500">At least 8 characters.</p>
                        <?= field_error('password') ?>
                    </div>

                    <div>
                        <label for="password_confirm" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm</label>
                        <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password"
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                        <?= field_error('password_confirm') ?>
                    </div>
                </div>

                <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    Create account
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            <?= form_close() ?>

            <p class="mt-5 text-sm text-slate-500">
                Already have an account?
                <a class="font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('login') ?>">Log in</a>.
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
