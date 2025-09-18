<?php

declare(strict_types=1);

$usersList = $usersList ?? [];
?>
<section class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Utilisateurs</h2>
            <p class="mt-2 text-gray-600">Liste des comptes récemment connectés via Google.</p>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th scope="col" class="px-4 py-3">ID</th>
                    <th scope="col" class="px-4 py-3">user_id</th>
                    <th scope="col" class="px-4 py-3">Email</th>
                    <th scope="col" class="px-4 py-3">Prénom</th>
                    <th scope="col" class="px-4 py-3">Nom</th>
                    <th scope="col" class="px-4 py-3">Créé le</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if (empty($usersList)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usersList as $userRow): ?>
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900">#<?php echo (int) $userRow['id']; ?></td>
                            <td class="px-4 py-3 break-all text-gray-700"><?php echo htmlspecialchars($userRow['user_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="px-4 py-3 break-all text-gray-700"><?php echo htmlspecialchars($userRow['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($userRow['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($userRow['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="px-4 py-3 text-gray-500 text-xs"><?php echo htmlspecialchars($userRow['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
