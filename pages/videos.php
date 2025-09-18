<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Vidéos Dola</title>
</head>
<body class="bg-gray-50">
<?php include '../components/Navbar.php'; ?>

<section class="pt-32 pb-20">
    <div class="max-w-screen-lg mx-auto px-6 lg:px-8 text-center">
        <span class="inline-block text-sm font-semibold uppercase tracking-wider text-yellow-500">Vidéos</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold text-gray-900">Les vidéos Dola arrivent bientôt</h1>
        <p class="mt-6 text-lg text-gray-600">Nous préparons une série de présentations, tutoriels et walkthroughs pour vous guider de la création de produits à la connexion des paiements.</p>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Présentation Dola</h2>
                <p class="mt-3 text-sm text-gray-500">Découvrez l'écosystème Dola et la manière la plus simple de vendre vos contenus digitaux.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Guide produits</h2>
                <p class="mt-3 text-sm text-gray-500">Ajoutez vos PDFs, masterclass ou templates et personnalisez votre vitrine en quelques minutes.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Paiements & automatisations</h2>
                <p class="mt-3 text-sm text-gray-500">Connectez Stripe, configurez vos emails automatiques et suivez vos revenus en direct.</p>
            </div>
        </div>

        <div class="mt-12">
            <p class="text-sm text-gray-500">Inscrivez-vous pour être informé de la mise en ligne.</p>
            <a href="/auth/login.php" class="mt-3 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-black text-white font-semibold hover:bg-yellow-500 hover:text-black transition">
                Être alerté dès la sortie
            </a>
        </div>
    </div>
</section>

<?php include '../components/Footer.php'; ?>
<?php include '../components/MailContact.php'; ?>
<?php include '../components/loginSection.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
