<?php

declare(strict_types=1);

$metrics = $metrics ?? [
    'userCount' => 0,
    'storeCount' => 0,
];
$sessionEmail = $sessionEmail ?? '';
$sessionUserId = $sessionUserId ?? '';
?>
<section class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Utilisateurs</p>
        <p class="mt-3 text-3xl font-extrabold text-gray-900"><?php echo number_format($metrics['userCount']); ?></p>
        <p class="mt-1 text-sm text-gray-500">Nombre total de comptes créés.</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Boutiques</p>
        <p class="mt-3 text-3xl font-extrabold text-gray-900"><?php echo number_format($metrics['storeCount']); ?></p>
        <p class="mt-1 text-sm text-gray-500">Boutiques enregistrées dans Dola.</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin user_id (session)</p>
        <p class="mt-3 text-3xl font-extrabold text-gray-900 break-all"><?php echo htmlspecialchars((string) $sessionUserId, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="mt-1 text-sm text-gray-500">Doit correspondre à <span class="font-semibold">ADMIN_USER_ID</span>.</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin email (session)</p>
        <p class="mt-3 text-3xl font-extrabold text-gray-900 break-all"><?php echo htmlspecialchars((string) $sessionEmail, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="mt-1 text-sm text-gray-500">Doit correspondre à <span class="font-semibold">ADMIN_EMAIL</span>.</p>
    </div>
</section>

<section class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm mt-8">
    <h2 class="text-2xl font-extrabold text-gray-900">Vue d'ensemble</h2>
    <p class="mt-3 text-gray-600">Utilisez le menu latéral pour explorer les utilisateurs, lancer les migrations ou accéder aux futures sections marketing et analytics.</p>
</section>
