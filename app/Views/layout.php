<?php
// layout.php - 3 fixed rows: header (4rem tall), footer (4rem tall), centered card in between.
// The card never scrolls. If card is bigger than leftover space, it will be clipped.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <!-- Important for mobile to avoid auto zoom-out -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? 'JA Property Management' ?></title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />

  <script>
    // Configure Tailwind to use dark mode via class
    tailwind.config = { darkMode: 'class' }
  </script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      /* No scrolling - if card is bigger than leftover space, it's clipped */
      overflow: hidden; /* or bg-gray-900 from Tailwind classes */
    }
  </style>
</head>

<body class="bg-gray-900 text-white relative">
  <!-- Toast Container (fixed, top-right) -->
  <div
    id="toast-container"
    class="fixed top-4 right-4 space-y-4 z-[100]"
  ></div>
  <script>
    // Global toast function
    function createToast(message, duration = 5000) {
      const toast = document.createElement('div');
      toast.className = 'bg-red-600 text-white px-4 py-2 rounded shadow-lg flex items-center w-full max-w-xs';
      toast.innerHTML = `
        <span class="flex-1">${message}</span>
        <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove();">
          <i class="fas fa-times"></i>
        </button>
      `;
      document.getElementById('toast-container').appendChild(toast);
      setTimeout(() => {
        toast.remove();
      }, duration);
    }
  </script>

  <!-- If a toast message was set, display it -->
  <?php if (isset($toastMessage) && !empty($toastMessage)): ?>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        createToast("<?= addslashes($toastMessage) ?>");
      });
    </script>
  <?php endif; ?>

  <!-- Fixed Header (4rem tall) -->
  <header class="fixed top-0 left-0 right-0 h-16 bg-black bg-opacity-75 shadow flex items-center z-50">
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex justify-between">
      <div class="flex items-center">
        <i class="fas fa-leaf text-green-500 text-2xl mr-2"></i>
        <span class="text-white text-xl font-bold">JA Property Management</span>
      </div>
    </div>
  </header>

  <!-- Middle area: absolute from top:4rem to bottom:4rem, flex center -->
  <main
    class="absolute left-0 right-0 flex items-center justify-center"
    style="top: 4rem; bottom: 4rem;"
  >
    <?php include $viewFile; ?>
  </main>

  <!-- Fixed Footer (4rem tall) -->
  <footer class="fixed bottom-0 left-0 right-0 h-16 bg-gray-800 flex items-center justify-center z-50">
    <div class="text-sm text-white">
      &copy; 2025 JA Property Management. All rights reserved.
    </div>
  </footer>
</body>
</html>
