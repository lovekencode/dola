<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$projectRoot = dirname(__DIR__);

// Autoload + env + DB
if (file_exists($projectRoot . '/vendor/autoload.php')) {
    require_once $projectRoot . '/vendor/autoload.php';
}
require_once $projectRoot . '/database/dbconnect.php';
require_once $projectRoot . '/database/storeSchema.php';

// Resolve username from request path: expected /s/{username}
$requested = (string)($_SERVER['REQUEST_URI'] ?? '/');
$path = parse_url($requested, PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/');
$username = '';
if (preg_match('#^/s/([a-z0-9-]{1,80})$#i', $path, $m)) {
    $username = strtolower($m[1]);
}

// Support explicit ?u= for dev/testing
if ($username === '') {
    $u = isset($_GET['u']) ? (string) $_GET['u'] : '';
    if ($u !== '') {
        $username = strtolower(preg_replace('/[^a-z0-9-]/i', '', $u) ?? '');
    }
}

http_response_code(200);
$store = null;

try {
    $pdo = getConnection();
    if (function_exists('createStoreSchema')) {
        createStoreSchema($pdo);
    }
    if ($username !== '') {
        $stmt = $pdo->prepare('SELECT id, username, name, description, product_ids, is_pro_store, created_at FROM stores WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $store = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
} catch (Throwable $e) {
    http_response_code(500);
}

$notFound = ($username === '' || !$store);
if ($notFound && http_response_code() < 400) {
    http_response_code(404);
}

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <title><?php echo $notFound ? 'Boutique introuvable' : e((string)$store['name']); ?> • Dola</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="color-scheme" content="light">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        .gradient-border {
            background: linear-gradient(135deg, #fbbf24, #f59e0b, #d97706);
            padding: 2px;
            border-radius: 1rem;
        }
        .gradient-border-inner {
            background: white;
            border-radius: calc(1rem - 2px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-yellow-50 min-h-screen text-gray-900 font-sans">
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <main class="relative mx-auto max-w-lg px-6 py-12">
        <?php if ($notFound): ?>
            <section class="text-center animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-3xl shadow-lg border border-gray-100 mb-8">
                    <img src="/images/dola.png" alt="Dola" class="w-12 h-12 rounded-2xl object-contain" />
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Boutique introuvable</h1>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">La boutique que vous recherchez n'existe pas ou a été supprimée.</p>
                <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-2xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </section>
        <?php else: ?>
            <?php
            $desc = (string)($store['description'] ?? '');
            $rawProducts = (string)($store['product_ids'] ?? '');
            $productIds = array_values(array_filter(array_map(function ($v) {
                $t = trim((string)$v);
                return preg_match('/^\d+$/', $t) ? $t : '';
            }, $rawProducts !== '' ? explode(',', $rawProducts) : [])));
            ?>

            <!-- Header avec effet glass -->
            <section class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 glass-effect rounded-3xl shadow-xl border border-white/20 mb-6 animate-float">
                    <img src="/images/dola.png" alt="Dola" class="w-12 h-12 rounded-2xl object-contain" />
                </div>
                
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-4"><?php echo e((string)$store['name']); ?></h1>
                
                <div class="flex items-center justify-center gap-3 mb-6">
                    <span class="text-gray-600 font-medium">@<?php echo e((string)$store['username']); ?></span>
                    <?php if ((int)$store['is_pro_store'] === 1): ?>
                        <div class="gradient-border">
                            <div class="gradient-border-inner px-3 py-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm text-gray-800">Pro</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($desc !== ''): ?>
                    <div class="glass-effect rounded-2xl p-6 border border-white/20 shadow-lg max-w-md mx-auto">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line"><?php echo e($desc); ?></p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Section produits -->
            <section class="animate-slide-up" style="animation-delay: 0.2s;">
                <?php if (count($productIds) === 0): ?>
                    <div class="glass-effect border border-white/20 rounded-3xl p-8 text-center shadow-lg">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Aucun produit</h3>
                        <p class="text-gray-600">Cette boutique sera bientôt remplie de produits fantastiques.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($productIds as $index => $pid): ?>
                            <article class="group glass-effect border border-white/20 rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-up" style="animation-delay: <?php echo 0.1 * ($index + 3); ?>s;">
                                <!-- Image placeholder avec gradient -->
                                <div class="h-48 bg-gradient-to-br from-yellow-300 via-yellow-400 to-orange-400 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors duration-300"></div>
                                    <div class="absolute bottom-4 right-4 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Contenu -->
                                <div class="p-6">
                                    <div class="text-center">
                                        <h3 class="font-bold text-xl mb-2 text-gray-900">Produit #<?php echo e((string)$pid); ?></h3>
                                        <p class="text-gray-600 mb-4">Découvrez bientôt tous les détails de ce produit exclusif.</p>
                                        
                                        <button class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-2xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 group-hover:shadow-lg">
                                            <span class="font-medium">Découvrir</span>
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Footer élégant -->
            <footer class="mt-16 text-center animate-fade-in" style="animation-delay: 0.5s;">
                <div class="glass-effect rounded-2xl p-6 border border-white/20 shadow-lg">
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Cette boutique est propulsée par 
                        <span class="font-semibold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">Dola</span>
                        <br>
                        <a href="/" class="inline-flex items-center gap-1 mt-2 text-black font-medium hover:text-yellow-600 transition-colors duration-300">
                            Créez votre boutique
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </p>
                </div>
            </footer>
        <?php endif; ?>
    </main>
</body>
</html>