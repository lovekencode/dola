<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
?>
<!-- Main modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm p-5">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 bg-gray-50 border-b border-gray-50 mb-10">
                <h3 class="text-xl font-semibold text-gray-900 ">
                   Connectez-vous
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-yellow-500 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="text-center mx-5">
              <img src="../images/dola.png" class="h-10 text-center mx-auto" alt="Dola Logo">
              <br>
                <p class="mt-2 text-sm text-gray-600">
                Ca prends moins de 2 minutes pour commencer a vendre vos produits digitaux.
                
                </p>
              </div>

              <div class="mt-5">
                <a href="/auth/login.php" class="login-button w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-modal-hide="authentication-modal">
                  <svg class="w-4 h-auto" width="46" height="47" viewBox="0 0 46 47" fill="none">
                    <path d="M46 24.0287C46 22.09 45.8533 20.68 45.5013 19.2112H23.4694V27.9356H36.4069C36.1429 30.1094 34.7347 33.37 31.5957 35.5731L31.5663 35.8669L38.5191 41.2719L38.9885 41.3306C43.4477 37.2181 46 31.1669 46 24.0287Z" fill="#4285F4"></path>
                    <path d="M23.4694 47C29.8061 47 35.1161 44.9144 39.0179 41.3012L31.625 35.5437C29.6301 36.9244 26.9898 37.8937 23.4987 37.8937C17.2793 37.8937 12.0281 33.7812 10.1505 28.1412L9.88649 28.1706L2.61097 33.7812L2.52296 34.0456C6.36608 41.7125 14.287 47 23.4694 47Z" fill="#34A853"></path>
                    <path d="M10.1212 28.1413C9.62245 26.6725 9.32908 25.1156 9.32908 23.5C9.32908 21.8844 9.62245 20.3275 10.0918 18.8588V18.5356L2.75765 12.8369L2.52296 12.9544C0.909439 16.1269 0 19.7106 0 23.5C0 27.2894 0.909439 30.8731 2.49362 34.0456L10.1212 28.1413Z" fill="#FBBC05"></path>
                    <path d="M23.4694 9.07688C27.8699 9.07688 30.8622 10.9863 32.5344 12.5725L39.1645 6.11C35.0867 2.32063 29.8061 0 23.4694 0C14.287 0 6.36607 5.2875 2.49362 12.9544L10.0918 18.8588C11.9987 13.1894 17.25 9.07688 23.4694 9.07688Z" fill="#EB4335"></path>
                  </svg>
                  Connecter avec Google
                </a>
                </div>
        <div>
        <p class="mt-12 text-sm text-gray-600 text-center">
                En créant un compte, vous acceptez nos 
                <a href="#" class="text-yellow-500 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium">
                    conditions d'utilisation</a> et notre <a href="#" class="text-yellow-500 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium">
                    politique de confidentialité</a>.
                
                </p>


          
        </div>
    </div>
</div> 





<script>
  (function () {
    const loginButton = document.querySelector('.login-button');
    if (!loginButton) return;
    const isAuthenticated = <?php echo $isAuthenticated ? 'true' : 'false'; ?>;
    loginButton.addEventListener('click', function (event) {
      if (isAuthenticated) {
        event.preventDefault();
        window.location.href = '/dashboard/index.php';
      }
    });
  })();
</script>
