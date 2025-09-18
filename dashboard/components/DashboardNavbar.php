<?php

declare(strict_types=1);

$user = $user ?? ($_SESSION['user'] ?? null);
$activeSection = $activeSection ?? 'home';

$navItems = [
    'home' => [
        'label' => 'Accueil',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V21h15V9.75"/></svg>',
    ],
    'products' => [
        'label' => 'Produits',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7.5v7.5H3zM13.5 3H21v7.5h-7.5zM3 13.5h7.5V21H3zM13.5 13.5H21V21h-7.5z"/></svg>',
    ],
];
?>
<header class="bg-white border-b border-gray-200">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <button id="dashboard-mobile-toggle" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 text-gray-600 hover:text-black hover:border-gray-300 transition">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="/" class="inline-flex items-center gap-2">
                    <img src="/images/dola.png" alt="Dola" class="h-8">
                    <span class="text-lg font-semibold text-gray-900 hidden sm:inline">Dola Dashboard</span>
                </a>
            </div>
            <div class="hidden lg:flex items-center gap-2">
                <?php foreach ($navItems as $slug => $item): ?>
                    <?php $isActive = $slug === $activeSection; ?>
                    <a href="?section=<?php echo urlencode($slug); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium <?php echo $isActive ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100'; ?> transition">
                        <?php echo $item['icon']; ?>
                        <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($user['first_name'] ?? 'Compte', ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <a href="/auth/logout.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition">Déconnexion</a>
            </div>
        </div>
    </div>
    <nav id="dashboard-mobile-menu" class="lg:hidden hidden border-t border-gray-200">
        <div class="px-4 py-3 flex flex-col gap-2 bg-white">
            <?php foreach ($navItems as $slug => $item): ?>
                <?php $isActive = $slug === $activeSection; ?>
                <a href="?section=<?php echo urlencode($slug); ?>" class="inline-flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium <?php echo $isActive ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100'; ?> transition">
                    <?php echo $item['icon']; ?>
                    <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>
<script>
    (function () {
        const toggle = document.getElementById('dashboard-mobile-toggle');
        const menu = document.getElementById('dashboard-mobile-menu');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    })();
</script>
