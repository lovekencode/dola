<?php

declare(strict_types=1);

session_start();

$projectRoot = dirname(__DIR__);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

if (class_exists(Dotenv\Dotenv::class)) {
    Dotenv\Dotenv::createMutable($projectRoot)->safeLoad();
}

if (!function_exists('env')) {
    require_once $projectRoot . '/database/dbconnect.php';
}

require_once $projectRoot . '/database/userSchema.php';
require_once $projectRoot . '/database/storeSchema.php';
require_once $projectRoot . '/database/schema.php';

$requiredEmail = env('ADMIN_EMAIL');
$requiredUserId = env('ADMIN_USER_ID');

$user = $_SESSION['user'] ?? null;
$isAuthenticated = $_SESSION['is_authenticated'] ?? false;
$sessionEmail = $user['email'] ?? null;
$sessionUserId = $user['user_id'] ?? null;

if (
    !$isAuthenticated ||
    !$user ||
    !$requiredEmail ||
    !$requiredUserId ||
    $sessionEmail !== $requiredEmail ||
    (string) $sessionUserId !== (string) $requiredUserId
) {
 
    exit;
}

$section = $_GET['section'] ?? 'home';
$section = is_string($section) ? strtolower($section) : 'home';

$navItems = [
    'home' => [
        'label' => 'Accueil',
        'icon' => '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l9.338-9.338a1.125 1.125 0 011.592 0L22.5 12M4.5 9.75V21h4.5v-4.5A1.5 1.5 0 0110.5 15h3a1.5 1.5 0 011.5 1.5V21H19.5V9.75"/></svg>'
    ],
    'users' => [
        'label' => 'Utilisateurs',
        'icon' => '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/></svg>'
    ],
    'marketing' => [
        'label' => 'Marketing',
        'icon' => '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12 12 5.25 4.5 12m15 0-1.5 6.75h-12L4.5 12m15 0h-15"/></svg>'
    ],
    'analytics' => [
        'label' => 'Analytics',
        'icon' => '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 15l3-3 2.25 2.25L18 9"/></svg>'
    ],
    'database' => [
        'label' => 'Database',
        'icon' => '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-3.866 0-7 1.119-7 2.5v13c0 1.381 3.134 2.5 7 2.5s7-1.119 7-2.5v-13C19 4.119 15.866 3 12 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 8c0 1.381-3.134 2.5-7 2.5S5 9.381 5 8"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 12.5c0 1.381-3.134 2.5-7 2.5s-7-1.119-7-2.5"/></svg>'
    ],
];

$sectionMap = [
    'home' => __DIR__ . '/pages/home.php',
    'users' => __DIR__ . '/pages/users.php',
    'marketing' => __DIR__ . '/pages/marketing.php',
    'analytics' => __DIR__ . '/pages/analytics.php',
    'database' => __DIR__ . '/pages/database.php',
];

if (!array_key_exists($section, $sectionMap)) {
    $section = 'home';
}

$messages = [];
$errors = [];
$tablesInfo = ['users', 'stores'];

$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($section === 'database') && isset($_POST['action']) && $_POST['action'] === 'run-schemas') {
    try {
        runSchemas();
        $messages[] = 'Les schémas ont été exécutés avec succès.';
    } catch (Throwable $e) {
        $errors[] = "Erreur lors de l'exécution des schémas : " . $e->getMessage();
    }
}

$metrics = [
    'userCount' => 0,
    'storeCount' => 0,
];

try {
    $metrics['userCount'] = (int) ($pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() ?? 0);
} catch (Throwable $e) {
    $errors[] = "Impossible de récupérer le nombre d'utilisateurs : " . $e->getMessage();
}

try {
    $storeExists = (int) ($pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'stores'")->fetchColumn() ?? 0);
    if ($storeExists) {
        $metrics['storeCount'] = (int) ($pdo->query('SELECT COUNT(*) FROM stores')->fetchColumn() ?? 0);
    }
} catch (Throwable $e) {
    $errors[] = "Impossible de récupérer le nombre de boutiques : " . $e->getMessage();
}

$usersList = [];
if ($section === 'users') {
    try {
        $stmt = $pdo->query('SELECT id, user_id, email, first_name, last_name, created_at FROM users ORDER BY id DESC LIMIT 50');
        $usersList = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Throwable $e) {
        $errors[] = "Impossible de récupérer la liste des utilisateurs : " . $e->getMessage();
    }
}

$activeSection = $section;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Administration Dola</title>
</head>
<body class="bg-gray-50 min-h-screen">
<?php include __DIR__ . '/components/AdminSidebar.php'; ?>

<div class="lg:pl-72">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-screen-xl mx-auto px-6 py-6 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-yellow-500">Admin</p>
                <h1 class="text-2xl font-extrabold text-gray-900">Bienvenue <?php echo htmlspecialchars($sessionEmail ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="flex gap-3">
                <a href="/dashboard/index.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Dashboard utilisateur</a>
                <a href="/auth/logout.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition">Déconnexion</a>
            </div>
        </div>
    </header>
    <?php include $sectionMap[$section]; ?>
    <main class="px-6 py-10 max-w-screen-xl mx-auto ">
        <?php if ($messages && $section !== 'database'): ?>
            <div class="rounded-3xl border border-green-200 bg-green-50 p-6 text-green-800">
                <?php foreach ($messages as $message): ?>
                    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($errors && $section !== 'database'): ?>
            <div class="rounded-3xl border border-red-200 bg-red-50 p-6 text-red-700">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
