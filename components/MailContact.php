<!-- Container fixe en bas à droite -->
<div class="fixed bottom-6 right-6 flex flex-col items-end gap-3 z-50">

  <!-- Panneau de contact (caché par défaut avec hidden) -->
  <div id="contact-panel" class="hidden w-[300px] bg-white border border-yellow-400 rounded-2xl shadow-xl p-4">
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-lg font-bold text-yellow-500">Contact</h2>
      <button id="close-btn" class="text-black hover:text-yellow-500">✖</button>
    </div>

    <form id="contact-form" class="flex flex-col gap-2">
      <div class="flex justify-center">
        <img src="/images/dola.png" class="h-8 w-18" />
      </div>

      <div class="flex justify-center text-center">
        <p class="text-gray-500">
          Laissez-nous un message et l’équipe Dola vous répondra dans les plus brefs délais.
        </p>
      </div>

      <input
        type="text"
        id="name"
        placeholder="Nom"
        required
        class="border p-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
      />

      <input
        type="email"
        id="email"
        placeholder="Email"
        required
        class="border p-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
      />

      <textarea
        id="message"
        placeholder="Message"
        required
        class="border p-2 rounded-lg h-20 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
      ></textarea>

      <button
        type="submit"
        class="bg-black text-white py-2 rounded-lg text-sm hover:bg-yellow-500 hover:text-black transition"
      >
        Envoyer
      </button>
    </form>

    <p id="status" class="mt-2 text-xs text-gray-600"></p>
  </div>

  <!-- Bouton flottant -->
  <button
    id="toggle-btn"
    class="bg-yellow-500 text-black px-4 py-3 rounded-full shadow-lg hover:bg-black transition hover:text-white"
  >
    📩 Support
  </button>
</div>

<script>
  const panel = document.getElementById("contact-panel");
  const toggleBtn = document.getElementById("toggle-btn");
  const closeBtn = document.getElementById("close-btn");
  const form = document.getElementById("contact-form");
  const statusEl = document.getElementById("status");

  let isOpen = false;

  // Ouvrir / fermer panneau
  toggleBtn.addEventListener("click", () => {
    isOpen = !isOpen;
    panel.classList.toggle("hidden", !isOpen);
  });

  closeBtn.addEventListener("click", () => {
    isOpen = false;
    panel.classList.add("hidden");
  });

  // Soumission formulaire
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const message = document.getElementById("message").value;

    statusEl.textContent = "⏳ Envoi en cours...";

    try {
      const res = await fetch("https://formsubmit.co/ajax/lovekenolustra@gmail.com", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, email, message })
      });

      const data = await res.json();

      if (data.success) {
        statusEl.textContent = "✅ Message envoyé !";
        form.reset();

        // Fermer après un délai
        setTimeout(() => {
          statusEl.textContent = "";
          panel.classList.add("hidden");
          isOpen = false;
        }, 1500);
      } else {
        statusEl.textContent = "❌ Erreur.";
      }
    } catch (err) {
      statusEl.textContent = "⚠️ Impossible d’envoyer le message.";
    }
  });
</script>
