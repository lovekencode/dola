<section class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Paiements</h2>
            <p class="mt-2 text-gray-600">Connectez Stripe pour encaisser vos ventes et suivez vos virements.</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold uppercase tracking-wide rounded-full border border-yellow-400 text-yellow-600">Bientôt</span>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Status de connexion</h3>
            <p class="mt-2 text-gray-500">Stripe n'est pas encore connecté. Vous pourrez bientôt lier votre compte et recevoir vos fonds automatiquement.</p>
            <button disabled class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white font-medium opacity-60 cursor-not-allowed">
                Connecter Stripe
            </button>
        </div>
        <div class="border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-900">Rapport des paiements</h3>
            <p class="mt-2 text-gray-500">Lorsque vous encaissez, un récapitulatif détaillé apparaîtra ici : montants, devises, fees, et état de transfert.</p>
            <a href="/pages/help-center.php" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-yellow-600 hover:text-yellow-500">
                En savoir plus sur les paiements
            </a>
        </div>
    </div>
</section>
