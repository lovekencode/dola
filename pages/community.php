<?php
$linksPath = __DIR__ . '/../externlinks.json';
$communityLinks = [];

if (file_exists($linksPath)) {
    $json = file_get_contents($linksPath);
    $data = json_decode($json, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($data['community']) && is_array($data['community'])) {
        $communityLinks = $data['community'];
    }
}

$whatsappLink = $communityLinks['whatsapp'] ?? '#';
$facebookLink = $communityLinks['facebook'] ?? '#';
$telegramLink = $communityLinks['telegram'] ?? '#';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Communauté Dola</title>
</head>
<body class="bg-gray-50">
<?php include '../components/Navbar.php'; ?>

<section class="pt-32 pb-20">
    <div class="max-w-screen-xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block text-sm font-semibold uppercase tracking-wider text-yellow-500">Communauté</span>
            <h1 class="mt-4 text-3xl md:text-4xl font-extrabold text-gray-900">Rejoignez les créateurs Dola</h1>
            <p class="mt-6 text-lg text-gray-500 max-w-3xl mx-auto">Discutez bonnes pratiques, posez vos questions et partagez vos réussites avec la communauté qui vend ses produits digitaux sur Dola.</p>
        </div>
        <div class="grid gap-8 md:grid-cols-3">
            <div class="bg-white border border-gray-200 rounded-3xl p-10 shadow-sm flex flex-col">
                <div class="h-12 w-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                        <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">WhatsApp Creators</h2>
                <p class="mt-3 text-gray-500 flex-1">Accédez à une conversation instantanée avec les créateurs les plus actifs et recevez les annonces produits en priorité.</p>
                <a href="<?php echo htmlspecialchars($whatsappLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="mt-8 inline-flex items-center justify-center rounded-xl border border-green-500 text-green-700 hover:bg-green-500 hover:text-white px-5 py-3 font-medium transition-colors duration-300">
                    Rejoindre WhatsApp
                    <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-10 shadow-sm flex flex-col">
                <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Groupe Facebook</h2>
                <p class="mt-3 text-gray-500 flex-1">Partagez vos lancements, échangez des conseils marketing et profitez des live sessions mensuelles avec l'équipe produit.</p>
                <a href="<?php echo htmlspecialchars($facebookLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="mt-8 inline-flex items-center justify-center rounded-xl border border-blue-500 text-blue-700 hover:bg-blue-500 hover:text-white px-5 py-3 font-medium transition-colors duration-300">
                    Rejoindre Facebook
                    <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-10 shadow-sm flex flex-col">
                <div class="h-12 w-12 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Canal Telegram</h2>
                <p class="mt-3 text-gray-500 flex-1">Recevez les mises à jour produits, les templates à tester et les opportunités de bêta avant tout le monde.</p>
                <a href="<?php echo htmlspecialchars($telegramLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="mt-8 inline-flex items-center justify-center rounded-xl border border-sky-500 text-sky-700 hover:bg-sky-500 hover:text-white px-5 py-3 font-medium transition-colors duration-300">
                    Rejoindre Telegram
                    <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../components/CTA.php'; ?>
<?php include '../components/Footer.php'; ?>
<?php include '../components/MailContact.php'; ?>
<?php include '../components/loginSection.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
