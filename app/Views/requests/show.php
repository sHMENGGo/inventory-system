<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Request details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$isOwner    = $request['user_id'] === auth_user()['id'];
$isAdmin    = auth_is_admin();
$canEdit    = $isOwner && $request['status'] === 'pending';
$canDelete  = $request['status'] !== 'received' && ($isAdmin || ($isOwner && $request['status'] === 'pending'));
$stockLow   = $isAdmin && $request['status'] === 'approved' && (int) $request['item_stock'] < (int) $request['quantity'];
$hasActions = ($isAdmin && in_array($request['status'], ['pending', 'approved'], true)) || $canEdit || $canDelete;
$comments   = $comments ?? [];
?>

<div class="mx-auto w-full max-w-4xl">
    <div class="mb-5 flex flex-wrap items-center gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Workflow</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Request details</h1>
        </div>

        <a class="ml-auto inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            href="<?= site_url('requests') ?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to requests
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-gradient-to-br from-slate-50 to-white px-6 py-5 sm:px-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="flex size-11 flex-none items-center justify-center rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
                    </svg>
                </span>

                <div class="min-w-0">
                    <h2 class="truncate text-xl font-bold tracking-tight text-slate-900 sm:text-2xl"><?= esc($request['item_name']) ?></h2>
                    <p class="mt-0.5 text-xs text-slate-500">Request #<?= esc(strtoupper(substr($request['id'], 0, 8))) ?></p>
                </div>

                <div class="ml-auto"><?= status_badge($request['status']) ?></div>
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-x-6 gap-y-5 border-b border-slate-200 px-6 py-6 sm:grid-cols-3 sm:px-8">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Quantity</dt>
                <dd class="mt-1 text-lg font-bold text-slate-900"><?= esc($request['quantity']) ?></dd>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Requested by</dt>
                <dd class="mt-1 truncate text-sm font-semibold text-slate-900"><?= esc($request['user_name']) ?></dd>
                <?php if (auth_is_admin()): ?>
                    <dd class="truncate text-xs text-slate-500"><?= esc($request['user_email']) ?></dd>
                <?php endif; ?>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Current stock</dt>
                <dd class="mt-1 text-lg font-bold <?= (int) $request['item_stock'] === 0 ? 'text-rose-700' : 'text-slate-900' ?>">
                    <?= esc($request['item_stock']) ?>
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Submitted</dt>
                <dd class="mt-1 text-sm text-slate-700"><?= esc($request['created_at']) ?></dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last updated</dt>
                <dd class="mt-1 text-sm text-slate-700"><?= esc($request['updated_at']) ?></dd>
            </div>
        </dl>

        <?php if ($hasActions): ?>
        <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:items-center sm:px-8">
            <?php if ($isAdmin && $request['status'] === 'pending'): ?>
                <?= form_open('requests/' . $request['id'] . '/approve', ['class' => 'm-0 inline']) ?>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        Approve
                    </button>
                <?= form_close() ?>

                <?= form_open('requests/' . $request['id'] . '/reject', ['class' => 'm-0 inline']) ?>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                <?= form_close() ?>
            <?php endif; ?>

            <?php if ($isAdmin && $request['status'] === 'approved'): ?>
                <?= form_open('requests/' . $request['id'] . '/receive', ['class' => 'm-0 inline']) ?>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Receive &amp; deduct stock
                    </button>
                <?= form_close() ?>
            <?php endif; ?>

            <div class="flex flex-col gap-2 sm:ml-auto sm:flex-row sm:items-center">
                <?php if ($canEdit): ?>
                    <a class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:w-auto"
                        href="<?= site_url('requests/' . $request['id'] . '/edit') ?>">Edit quantity</a>
                <?php endif; ?>

                <?php if ($canDelete): ?>
                    <?= form_open('requests/' . $request['id'] . '/delete', [
                        'class'    => 'm-0 inline',
                        'onsubmit' => "return confirm('Delete this request?')",
                    ]) ?>
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm transition hover:bg-rose-50 sm:w-auto">
                            Delete
                        </button>
                    <?= form_close() ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($stockLow): ?>
            <div class="flex items-start gap-3 border-t border-rose-200 bg-rose-50 px-6 py-4 text-sm text-rose-800 sm:px-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mt-0.5 size-5 flex-none text-rose-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <span>
                    Stock is too low to receive this request:
                    <strong><?= esc($request['item_stock']) ?></strong> available,
                    <strong><?= esc($request['quantity']) ?></strong> needed.
                </span>
            </div>
        <?php endif; ?>
    </div>

    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-sm font-semibold text-slate-900">Request timeline</h2>

        <ol class="mt-4 space-y-4">
            <?php
            $timeline = [
                ['label' => 'Submitted', 'description' => 'Request created by ' . $request['user_name'], 'date' => $request['created_at'], 'done' => true],
                ['label' => 'Reviewed', 'description' => $request['status'] === 'pending' ? 'Waiting for an administrator' : 'Decision recorded by an administrator', 'date' => $request['status'] === 'pending' ? null : $request['updated_at'], 'done' => $request['status'] !== 'pending'],
                ['label' => 'Received', 'description' => $request['status'] === 'received' ? 'Stock has been deducted' : 'Stock is deducted when an administrator receives it', 'date' => $request['status'] === 'received' ? $request['updated_at'] : null, 'done' => $request['status'] === 'received'],
            ];
            ?>

            <?php foreach ($timeline as $step): ?>
                <li class="flex gap-3">
                    <span class="mt-1 flex size-6 flex-none items-center justify-center rounded-full <?= $step['done'] ? 'bg-teal-600 text-white' : 'border border-slate-300 bg-white text-slate-400' ?>">
                        <?php if ($step['done']): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                        <?php else: ?>
                            <span class="size-1.5 rounded-full bg-current"></span>
                        <?php endif; ?>
                    </span>

                    <div class="min-w-0">
                        <div class="text-sm font-semibold <?= $step['done'] ? 'text-slate-900' : 'text-slate-500' ?>"><?= esc($step['label']) ?></div>
                        <div class="text-xs text-slate-500"><?= esc($step['description']) ?></div>
                        <?php if ($step['date'] !== null): ?>
                            <div class="mt-0.5 text-xs text-slate-400"><?= esc($step['date']) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </section>

    <section id="comments" class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4 sm:px-6">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Comments</h2>
                <p class="text-xs text-slate-500">Discuss this request with the team.</p>
            </div>
            <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                <?= esc(count($comments)) ?>
            </span>
        </div>

        <div class="space-y-5 px-5 py-5 sm:px-6">
            <?php if ($comments === []): ?>
                <p class="py-2 text-sm text-slate-500">No comments yet. Be the first to add one.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <?php $commentIsAdmin = flag($comment['user_is_admin']); ?>
                    <div class="flex gap-3">
                        <span class="flex size-9 flex-none items-center justify-center rounded-full <?= $commentIsAdmin ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-600' ?> text-xs font-bold">
                            <?= esc(strtoupper(substr(trim($comment['user_name']), 0, 1))) ?>
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                <span class="text-sm font-semibold text-slate-900"><?= esc($comment['user_name']) ?></span>
                                <?php if ($commentIsAdmin): ?>
                                    <span class="inline-flex rounded-full bg-violet-50 px-2 py-0.5 text-[11px] font-semibold text-violet-700 ring-1 ring-inset ring-violet-200">Admin</span>
                                <?php endif; ?>
                                <span class="text-xs text-slate-400"><?= esc($comment['created_at']) ?></span>
                            </div>
                            <p class="mt-1 whitespace-pre-wrap text-sm leading-relaxed text-slate-700"><?= esc($comment['body']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="border-t border-slate-200 bg-slate-50/60 px-5 py-4 sm:px-6">
            <?= form_open('requests/' . $request['id'] . '/comments') ?>
                <label for="body" class="mb-1.5 block text-sm font-semibold text-slate-700">Add a comment</label>
                <textarea id="body" name="body" rows="3" required maxlength="2000"
                    placeholder="Write a note for this request..."
                    class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm leading-relaxed text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-500/10"><?= esc(old('body', '')) ?></textarea>
                <?= field_error('body') ?>

                <div class="mt-3 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                        Post comment
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
