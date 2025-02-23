<?php
// layout.php - Global layout that wraps your individual card view.
// $viewFile should be defined before including this layout.
// Optionally, a $toastMessage variable can be set by the controller.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? 'JA Property Management' ?></title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script>
    // Configure Tailwind to use dark mode via class
    tailwind.config = { darkMode: 'class' }
  </script>
  <style>
    /* Global styles */
    body {
      overflow: hidden;
      user-select: none;
    }
  </style>
</head>
<body class="bg-gray-900 text-white">
  <!-- Toast Container (fixed top-right) -->
  <div id="toast-container" class="fixed top-4 right-4 space-y-4 z-[100]"></div>
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

  <!-- Fixed Header -->
  <header class="fixed top-0 left-0 right-0 bg-black bg-opacity-75 shadow backdrop-blur-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <div class="flex items-center">
        <i class="fas fa-leaf text-green-500 text-2xl mr-2"></i>
        <a href="http://www.japropertysc.com" class="text-white text-xl font-bold">JA Property Management</a>
      </div>
    </div>
  </header>

  <!-- Main Content Area (only the card is included here) -->
  <div class="flex items-center justify-center min-h-screen">
    <?php include $viewFile; ?>
  </div>

  <!-- Fixed Footer -->
  <footer class="fixed bottom-0 left-0 right-0 bg-gray-800 py-4 z-50">
    <div class="max-w-7xl mx-auto text-center text-white text-sm">
      &copy; 2025 JA Property Management. All rights reserved.
    </div>
  </footer>
</body>
</html>
