<?php

declare(strict_types=1);

$navItems = $navItems ?? [];
$activeSection = $activeSection ?? 'home';
$sessionEmail = $sessionEmail ?? '';
?>
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/40 hidden lg:hidden z-40"></div>

<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-60 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 lg:static lg:w-56 transition-transform duration-300 ease-in-out flex flex-col">
    <div class="px-6 py-6 border-b border-gray-200 flex items-center gap-3">
        <button id="admin-sidebar-toggle" class="inline-flex lg:hidden items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:text-black hover:border-gray-300 transition">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 12h16.5m-16.5 6.75h16.5" />
            </svg>
        </button>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-yellow-500">Admin Dola</p>
            <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($sessionEmail, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
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
    <div class="px-4 py-6 border-t border-gray-200">
        <a href="/" class="inline-flex items-center justify-center w-full px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Revenir au site</a>
    </div>
</aside>

<script>
    (function () {
        const toggle = document.getElementById('admin-sidebar-toggle');
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        const opener = document.getElementById('admin-sidebar-open');
        if (!toggle || !sidebar || !overlay) return;
        const close = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };
        const open = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        if (opener) {
            opener.addEventListener('click', open);
        }
        overlay.addEventListener('click', close);
        sidebar.querySelectorAll('a[href]').forEach(link => link.addEventListener('click', () => {
            if (window.innerWidth < 1024) close();
        }));
    })();
</script>
