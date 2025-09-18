<?php

declare(strict_types=1);

$navItems = $navItems ?? [];
$activeSection = $activeSection ?? 'home';
$user = $user ?? ($_SESSION['user'] ?? null);
?>
<div class="lg:hidden bg-white border-b border-gray-200">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button id="dashboard-sidebar-toggle" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:text-black hover:border-gray-300 transition">
                <span class="sr-only">Ouvrir le menu</span>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <a href="/" class="inline-flex items-center gap-2">
                <img src="/images/dola.png" alt="Dola" class="h-8">
               
            </a>
        </div>
        <a href="/auth/logout.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition">Déconnexion</a>
    </div>
</div>

<div id="dashboard-sidebar-overlay" class="fixed inset-0 bg-black/40 hidden lg:hidden z-40"></div>

<aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    <div class="px-6 py-6 border-b border-gray-200 hidden lg:flex items-center gap-3">
        <a href="/" class="inline-flex items-center gap-2">
            <img src="/images/dola.png" alt="Dola" class="h-9">
            <div>
                <p class="text-sm font-semibold text-gray-900">Dashboard</p>
                <p class="text-xs text-gray-500">Gérez vos ventes </p>
            </div>
        </a>
    </div>
    <div class="flex-1 overflow-y-auto">
        <nav class="px-4 py-6 space-y-1">
            <?php foreach ($navItems as $slug => $item): ?>
                <?php $isActive = $slug === $activeSection; ?>
                <a href="?section=<?php echo urlencode($slug); ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition <?php echo $isActive ? 'bg-black text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <span class="w-6 h-6 flex items-center justify-center <?php echo $isActive ? 'text-yellow-400' : 'text-yellow-500 group-hover:text-yellow-600'; ?>">
                        <?php echo $item['icon']; ?>
                    </span>
                    <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    <div class="px-4 py-6 border-t border-gray-200">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
            <p class="text-sm font-semibold text-gray-900">
                <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="mt-1 text-xs text-gray-500 break-all"><?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="/auth/logout.php" class="mt-4 inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-red-700 hover:text-white transition">Déconnexion</a>
        </div>
    </div>
</aside>

<script>
    (function () {
        const toggle = document.getElementById('dashboard-sidebar-toggle');
        const sidebar = document.getElementById('dashboard-sidebar');
        const overlay = document.getElementById('dashboard-sidebar-overlay');
        if (!toggle || !sidebar || !overlay) return;
        const close = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        overlay.addEventListener('click', close);
        sidebar.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    close();
                }
            });
        });
    })();
</script>
