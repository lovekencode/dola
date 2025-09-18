<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Centre d'aide - Dola</title>
</head>
<body class="bg-gray-50">
<?php include '../components/Navbar.php'; ?>

<section class="pt-32 pb-20">
    <div class="max-w-screen-md mx-auto px-6 lg:px-8 text-center">
        <span class="inline-block text-sm font-semibold uppercase tracking-wider text-yellow-500">Centre d'aide</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold text-gray-900">Nous préparons la meilleure expérience support</h1>
        <p class="mt-6 text-lg text-gray-500">Notre base de connaissances est en cours de rédaction pour répondre à toutes vos questions sur la vente de produits digitaux avec Dola.</p>
        <div class="mt-12 bg-white border border-gray-200 rounded-3xl px-8 py-12 shadow-sm">
            <p class="text-sm font-semibold text-yellow-500 uppercase tracking-wider">Coming soon</p>
            <h2 class="mt-4 text-2xl font-extrabold text-gray-900">Encore un peu de patience</h2>
            <p class="mt-3 text-gray-500">Nous travaillons sur des guides détaillés, des tutoriels vidéo et des exemples concrets. Inscrivez-vous pour être notifié de la mise en ligne.</p>
            <a href="/pages/community.php" class="mt-8 inline-flex items-center justify-center px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition-colors duration-300">
                Être averti du lancement
</a>
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
