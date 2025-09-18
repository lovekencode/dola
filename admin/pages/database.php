<?php

declare(strict_types=1);

$messages = $messages ?? [];
$errors = $errors ?? [];
$tablesInfo = $tablesInfo ?? [];
?>
<section class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Gestion de la base de données</h2>
            <p class="mt-2 text-gray-600">Exécutez toutes les migrations (users, stores, etc.) via <code>runSchemas()</code>.</p>
        </div>
        <form method="post" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="action" value="run-schemas">
            <button type="submit" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition">
                Exécuter runSchemas()
            </button>
        </form>
    </div>

    <?php if ($messages): ?>
        <div class="mt-6 rounded-3xl border border-green-200 bg-green-50 p-6 text-green-800">
            <?php foreach ($messages as $message): ?>
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-6 text-red-700">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 p-6 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Tables couvertes</h3>
            <ul class="mt-3 text-sm text-gray-600 space-y-1">
                <?php foreach ($tablesInfo as $table): ?>
                    <li>• <?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Bonnes pratiques</h3>
            <p class="mt-2 text-sm text-gray-600">Exécutez ce script en pré-déploiement pour éviter d'avoir du DDL dans les routes applicatives.</p>
        </div>
    </div>
</section>
