<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Log in<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto grid w-full max-w-5xl items-stretch gap-8 lg:grid-cols-2">
    <?= $this->include('partials/auth-brand') ?>

    <div class="flex items-center">
        <div class="w-full rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex size-12 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                </svg>
            </div>

            <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
            <p class="mt-1 text-sm text-slate-500">Use the account an administrator created for you.</p>

            <?= form_open('login', ['class' => 'mt-6']) ?>
                <div class="mb-4">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="<?= form_value('email') ?>" required autofocus autocomplete="email"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('email') ?>
                </div>

                <div class="mb-5">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-4 focus:ring-teal-500/10">
                    <?= field_error('password') ?>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    Log in
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            <?= form_close() ?>

            <p class="mt-5 text-sm text-slate-500">
                No account yet?
                <a class="font-semibold text-teal-700 hover:text-teal-800" href="<?= site_url('register') ?>">Register</a>.
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
