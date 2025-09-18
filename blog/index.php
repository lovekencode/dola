<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Blog Dola</title>
</head>
<body class="bg-gray-50">
<?php include '../components/Navbar.php'; ?>

<section class="pt-32 pb-20">
    <div class="max-w-screen-md mx-auto px-6 lg:px-8 text-center">
        <span class="inline-block text-sm font-semibold uppercase tracking-wider text-yellow-500">Blog</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold text-gray-900">Le blog Dola arrive bientôt</h1>
        <p class="mt-6 text-lg text-gray-500">Nous préparons des guides pratiques, des études de cas et des astuces pour vous aider à vendre vos produits digitaux encore plus facilement.</p>
        <div class="mt-12 bg-white border border-gray-200 rounded-3xl px-8 py-12 shadow-sm">
            <p class="text-sm font-semibold text-yellow-500 uppercase tracking-wider">Coming soon</p>
            <h2 class="mt-4 text-2xl font-extrabold text-gray-900">Soyez les premiers informés</h2>
            <p class="mt-3 text-gray-500">Inscrivez-vous à la plateforme pour recevoir un email dès que le premier article sera publié.</p>
            <a href="/pages/community.php" class="mt-8 inline-flex items-center justify-center px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition-colors duration-300">
                Rejoindre la communauté
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
