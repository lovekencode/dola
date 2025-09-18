<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__, 2);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

$store = null;
$appDomain = null;

try {
    if ($user) {
        if (file_exists($projectRoot . '/vendor/autoload.php')) {
            require_once $projectRoot . '/vendor/autoload.php';
        }
        require_once $projectRoot . '/database/dbconnect.php';
        require_once $projectRoot . '/database/storeSchema.php';

        if (class_exists(Dotenv\Dotenv::class)) {
            Dotenv\Dotenv::createMutable($projectRoot)->safeLoad();
        }

        $appDomain = env('APP_DOMAIN', sprintf('%s://%s',
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'] ?? 'localhost'
        ));

        $pdo = getConnection();
        if (function_exists('createStoreSchema')) {
            createStoreSchema($pdo);
        }

        $stmt = $pdo->prepare('SELECT id, username, name, description, sales_count, is_pro_store, created_at FROM stores WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => (int) ($user['id'] ?? 0)]);
        $store = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
} catch (Throwable $e) {
    // Fail quietly on the UI, errors can be logged server-side if needed.
}
?>

<section class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm">
    <h2 class="text-2xl font-extrabold text-gray-900">Votre boutique</h2>
    <p class="mt-2 text-gray-600">Gérez votre boutique et personnalisez la vitrine.</p>

    <?php if ($store): ?>
        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                <dl class="mt-4 space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Nom</dt>
                        <dd id="store-info-name"><?php echo htmlspecialchars($store['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Username</dt>
                        <dd id="store-info-username"><?php echo htmlspecialchars($store['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Ventes</dt>
                        <dd><?php echo (int) ($store['sales_count'] ?? 0); ?></dd>
                    </div>
                </dl>
            </div>
            <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Lien de votre boutique</h3>
                <?php $publicUrl = rtrim((string)$appDomain, '/') . '/s/' . urlencode((string)($store['username'] ?? '')); ?>
                <div class="mt-3 flex items-center gap-3">
                    <input id="store-public-link" type="text" class="w-full border border-black rounded-xl px-3 py-2 text-sm bg-white text-black" value="<?php echo htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8'); ?>" readonly />
                    <button type="button" id="copy-store-link" class="inline-flex items-center px-4 py-2 rounded-xl bg-black text-white font-medium border border-black hover:bg-yellow-500 hover:text-black transition">Copier</button>
                </div>
                <p class="mt-2 text-xs text-gray-500">Partagez ce lien avec vos clients.</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div class="border border-gray-200 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900">Modifier la boutique</h3>
                <form id="edit-store-form" class="mt-4 space-y-5" novalidate>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de la boutique</label>
                        <input id="edit-store-name" name="name" type="text" maxlength="25" required class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" value="<?php echo htmlspecialchars($store['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        <p class="mt-1 text-xs text-gray-500">25 caractères maximum.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                        <input id="edit-store-username" name="username" type="text" maxlength="20" required class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" value="<?php echo htmlspecialchars($store['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        <p id="edit-username-help" class="mt-1 text-xs text-gray-500">20 caractères max. Lettres, chiffres et tirets (a-z, 0-9, -).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                        <textarea id="edit-store-description" name="description" rows="3" class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="Décrivez votre boutique"><?php echo htmlspecialchars($store['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition">Enregistrer</button>
                        <span id="edit-form-status" class="text-sm"></span>
                    </div>
                </form>
            </div>
            <div class="border border-red-200 rounded-2xl p-6 bg-red-50">
                <h3 class="text-lg font-semibold text-red-800">Supprimer la boutique</h3>
                <p class="mt-2 text-sm text-red-700">Cette action est irréversible. Vos données de boutique seront supprimées.</p>
                <button id="delete-store-btn" type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-800 hover:bg-red-100">Supprimer la boutique</button>
            </div>
        </div>

        <div class="mt-8 border border-dashed border-gray-300 rounded-2xl p-8 text-center text-gray-500">
            <h3 class="text-lg font-semibold text-gray-900">Prévisualisation</h3>
            <p class="mt-2">L'aperçu en direct de votre boutique sera accessible une fois vos produits ajoutés.</p>
        </div>
    <?php else: ?>
        <div id="store-create-container" class="mt-8 border border-dashed border-gray-300 rounded-2xl p-8">
            <h3 class="text-lg font-semibold text-gray-900">Créer votre boutique</h3>
            <p class="mt-2 text-sm text-gray-600">Vous n'avez pas encore de boutique. Créez-la en quelques secondes.</p>

            <form id="create-store-form" class="mt-6 space-y-5" novalidate>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom de la boutique</label>
                    <input id="store-name" name="name" type="text" maxlength="25" required class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="Ex: Ma boutique" />
                    <p class="mt-1 text-xs text-gray-500">25 caractères maximum.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                    <input id="store-username" name="username" type="text" maxlength="20" required class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="ex: mon-store" />
                    <p id="username-help" class="mt-1 text-xs text-gray-500">20 caractères max. Lettres, chiffres et tirets (a-z, 0-9, -).</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                    <textarea id="store-description" name="description" rows="3" class="mt-1 w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="Décrivez votre boutique"></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition">
                        Créer la boutique
                    </button>
                    <span id="form-status" class="text-sm"></span>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>

<script>
    (function () {
        const copyBtn = document.getElementById('copy-store-link');
        if (copyBtn) {
            copyBtn.addEventListener('click', async () => {
                const input = copyBtn.previousElementSibling;
                if (input && input.value) {
                    try {
                        await navigator.clipboard.writeText(input.value);
                        copyBtn.textContent = 'Copié!';
                        setTimeout(() => copyBtn.textContent = 'Copier', 1500);
                    } catch (_) {}
                }
            });
        }

        // Edit existing store
        const editForm = document.getElementById('edit-store-form');
        const editNameEl = document.getElementById('edit-store-name');
        const editUsernameEl = document.getElementById('edit-store-username');
        const editDescEl = document.getElementById('edit-store-description');
        const editHelp = document.getElementById('edit-username-help');
        const editStatus = document.getElementById('edit-form-status');
        const infoName = document.getElementById('store-info-name');
        const infoUsername = document.getElementById('store-info-username');
        const publicLinkInput = document.getElementById('store-public-link');

        let currentUsername = <?php echo json_encode((string)($store['username'] ?? '')); ?>;
        const basePublic = <?php echo json_encode(rtrim((string)$appDomain, '/')); ?>;

        function setEditHelp(msg, ok) {
            if (!editHelp) return;
            editHelp.textContent = msg;
            editHelp.className = 'mt-1 text-xs ' + (ok ? 'text-green-600' : 'text-red-600');
        }

        async function checkUsernameExclude(value) {
            const q = new URLSearchParams({ username: value, exclude: currentUsername }).toString();
            try {
                const res = await fetch('/dashboard/api/store_username_check.php?' + q, { credentials: 'same-origin' });
                const data = await res.json();
                if (data && data.ok) {
                    setEditHelp(data.message, !!data.available);
                    return !!data.available;
                }
            } catch (_) {}
            setEditHelp('Erreur de vérification. Réessayez.', false);
            return false;
        }

        if (editUsernameEl) {
            let t2;
            editUsernameEl.addEventListener('input', () => {
                let v = (editUsernameEl.value || '').toLowerCase().replace(/[^a-z0-9-]/g, '');
                if (v.length > 20) v = v.slice(0, 20);
                if (v !== editUsernameEl.value) editUsernameEl.value = v;
                if (!v) {
                    setEditHelp('20 caractères max. Lettres, chiffres et tirets (a-z, 0-9, -).', false);
                    return;
                }
                clearTimeout(t2);
                t2 = setTimeout(() => { checkUsernameExclude(v); }, 300);
            });
        }

        if (editForm) {
            editForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                editStatus.textContent = '';
                editStatus.className = 'text-sm';

                const name = (editNameEl?.value || '').trim();
                let username = (editUsernameEl?.value || '').trim();
                const description = (editDescEl?.value || '').trim();

                if (!name || !username) {
                    editStatus.textContent = 'Nom et nom d\'utilisateur sont requis';
                    editStatus.classList.add('text-red-600');
                    return;
                }
                if (name.length > 25) {
                    editStatus.textContent = 'Le nom doit faire 25 caractères maximum';
                    editStatus.classList.add('text-red-600');
                    return;
                }
                if (username.length > 20) {
                    editStatus.textContent = 'Le nom d\'utilisateur doit faire 20 caractères maximum';
                    editStatus.classList.add('text-red-600');
                    return;
                }

                if (username !== currentUsername) {
                    const available = await checkUsernameExclude(username);
                    if (!available) {
                        editStatus.textContent = 'Veuillez choisir un autre nom d\'utilisateur';
                        editStatus.classList.add('text-red-600');
                        return;
                    }
                }

                const fd = new FormData();
                fd.append('name', name);
                fd.append('username', username);
                if (description) fd.append('description', description);

                try {
                    const res = await fetch('/dashboard/api/store_update.php', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd, credentials: 'same-origin' });
                    const data = await res.json();
                    if (data && data.ok && data.store) {
                        // Reflect changes in UI
                        if (infoName) infoName.textContent = data.store.name || '';
                        if (infoUsername) infoUsername.textContent = data.store.username || '';
                        currentUsername = data.store.username || currentUsername;
                        if (publicLinkInput) publicLinkInput.value = basePublic + '/s/' + encodeURIComponent(currentUsername);
                        editStatus.textContent = 'Modifications enregistrées';
                        editStatus.classList.add('text-green-600');
                    } else {
                        editStatus.textContent = (data && data.error) ? data.error : 'Erreur. Réessayez.';
                        editStatus.classList.add('text-red-600');
                    }
                } catch (_) {
                    editStatus.textContent = 'Erreur. Réessayez.';
                    editStatus.classList.add('text-red-600');
                }
            });
        }

        const deleteBtn = document.getElementById('delete-store-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async () => {
                if (!confirm('Supprimer définitivement la boutique ?')) return;
                try {
                    const res = await fetch('/dashboard/api/store_delete.php', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    const data = await res.json();
                    if (data && data.ok) {
                        // Simplest: reload to show the create form
                        window.location.reload();
                    } else {
                        alert(data && data.error ? data.error : 'Erreur. Réessayez.');
                    }
                } catch (_) {
                    alert('Erreur. Réessayez.');
                }
            });
        }

        const uname = document.getElementById('store-username');
        const help = document.getElementById('username-help');
        const form = document.getElementById('create-store-form');
        const statusEl = document.getElementById('form-status');

        let t;
        function setHelp(msg, ok) {
            if (!help) return;
            help.textContent = msg;
            help.className = 'mt-1 text-xs ' + (ok ? 'text-green-600' : 'text-red-600');
        }

        function sanitizeUsername(v) {
            v = (v || '').toLowerCase();
            v = v.replace(/[^a-z0-9-]/g, '');
            if (v.length > 20) v = v.slice(0, 20);
            return v;
        }

        async function checkUsername(value) {
            const q = new URLSearchParams({ username: value }).toString();
            try {
                const res = await fetch('/dashboard/api/store_username_check.php?' + q, { credentials: 'same-origin' });
                const data = await res.json();
                if (data && data.ok) {
                    setHelp(data.message, !!data.available);
                    return !!data.available;
                }
            } catch (_) {}
            setHelp('Erreur de vérification. Réessayez.', false);
            return false;
        }

        if (uname) {
            uname.addEventListener('input', () => {
                let v = sanitizeUsername(uname.value);
                if (v !== uname.value) uname.value = v;
                if (!v) {
                    setHelp('20 caractères max. Lettres, chiffres et tirets (a-z, 0-9, -).', false);
                    return;
                }
                clearTimeout(t);
                t = setTimeout(() => { checkUsername(v); }, 300);
            });
        }

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                statusEl.textContent = '';
                statusEl.className = 'text-sm';

                const nameEl = document.getElementById('store-name');
                const descEl = document.getElementById('store-description');
                const name = (nameEl?.value || '').trim();
                let username = sanitizeUsername(uname?.value || '');
                const description = (descEl?.value || '').trim();

                if (!name || !username) {
                    statusEl.textContent = 'Nom et nom d\'utilisateur sont requis';
                    statusEl.classList.add('text-red-600');
                    return;
                }
                if (name.length > 25) {
                    statusEl.textContent = 'Le nom doit faire 25 caractères maximum';
                    statusEl.classList.add('text-red-600');
                    return;
                }
                if (username.length > 20) {
                    statusEl.textContent = 'Le nom d\'utilisateur doit faire 20 caractères maximum';
                    statusEl.classList.add('text-red-600');
                    return;
                }

                const available = await checkUsername(username);
                if (!available) {
                    statusEl.textContent = 'Veuillez choisir un autre nom d\'utilisateur';
                    statusEl.classList.add('text-red-600');
                    return;
                }

                const fd = new FormData();
                fd.append('name', name);
                fd.append('username', username);
                if (description) fd.append('description', description);

                try {
                    const res = await fetch('/dashboard/api/store_create.php', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd,
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    if (data && data.ok && data.store) {
                        const publicUrl = '<?php echo htmlspecialchars(rtrim((string)$appDomain, "/"), ENT_QUOTES, "UTF-8"); ?>/s/' + encodeURIComponent(data.store.username);
                        const container = document.getElementById('store-create-container');
                        const successHtml = `
                            <h3 class="text-lg font-semibold text-gray-900">Votre boutique</h3>
                            <p class="mt-2 text-sm text-gray-600">Votre boutique a été créée avec succès.</p>
                            <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-6">
                                <p class="text-gray-900 font-medium">Lien de votre boutique</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <input type="text" class="w-full border border-black rounded-xl px-3 py-2 text-sm bg-white text-black" value="${publicUrl}" readonly />
                                    <button type="button" class="inline-flex items-center px-4 py-2 rounded-xl bg-black text-white font-medium border border-black hover:bg-yellow-500 hover:text-black transition" onclick="navigator.clipboard.writeText('${publicUrl}')">Copier</button>
                                </div>
                            </div>`;
                        if (container) {
                            container.innerHTML = successHtml;
                        } else {
                            form.outerHTML = successHtml;
                        }
                    } else {
                        statusEl.textContent = (data && data.error) ? data.error : 'Erreur. Réessayez.';
                        statusEl.classList.add('text-red-600');
                    }
                } catch (e) {
                    statusEl.textContent = 'Erreur. Réessayez.';
                    statusEl.classList.add('text-red-600');
                }
            });
        }
    })();
</script>
