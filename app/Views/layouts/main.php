<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $pageTitle = trim($this->renderSection('title')); ?>
    <title><?= $pageTitle !== '' ? esc($pageTitle) . ' &middot; ' : '' ?>Inventory</title>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-50 to-slate-100 text-slate-800 antialiased">
    <?= $this->include('partials/nav') ?>

    <main class="mx-auto w-full max-w-6xl px-4 pb-16 pt-6 sm:px-6 sm:pt-10">
        <?= $this->include('partials/flash') ?>
        <?= $this->include('partials/errors') ?>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>
