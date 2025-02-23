<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - JA Property Management</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script>
    // Configure Tailwind to use dark mode via class
    tailwind.config = { darkMode: 'class' }
  </script>
  <style>
    /* Prevent body scrolling */
    body {
      overflow: hidden;
      user-select: none;
    }
  </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 space-y-4 z-50"></div>

<script>
  // Function to create a toast message
  function createToast(message, duration = 5000) {
    const toast = document.createElement('div');
    toast.className = 'bg-red-600 text-white px-4 py-2 rounded shadow-lg flex items-center';
    toast.innerHTML = `
      <span class="flex-1">${message}</span>
      <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove();">
        <i class="fas fa-times"></i>
      </button>
    `;
    document.getElementById('toast-container').appendChild(toast);
    // Auto remove toast after the specified duration
    setTimeout(() => {
      toast.remove();
    }, duration);
  }

  // Immediately create a toast with your message
  createToast("Unable to process your request at this time. Please contact support if the issue persists.");
</script>
  <!-- Fixed Header -->
  <header class="fixed top-0 left-0 right-0 bg-black bg-opacity-75 shadow backdrop-blur-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <div class="flex items-center">
        <i class="fas fa-leaf text-green-500 text-2xl mr-2"></i>
        <a href="http://www.japropertysc.com" class="text-white text-xl font-bold">JA Property Management</a>
      </div>
    </div>
  </header>

  <!-- Centered Login Card -->
  <div class="fixed inset-0 flex items-center justify-center ">
    <div class="w-full mx-4 sm:max-w-md bg-gray-800 p-8 sm:rounded-lg shadow-lg z-40">
      <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
      <form class="space-y-4">
        <div>
          <label class="block text-gray-300">Email Address</label>
          <input type="email" placeholder="you@example.com" required
            class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div>
          <label class="block text-gray-300">Password</label>
          <input type="password" placeholder="********" required
            class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input type="checkbox" id="remember" class="mr-2">
            <label for="remember" class="text-gray-300 text-sm">Remember Me</label>
          </div>
          <button type="button" class="text-green-500 text-sm hover:underline" onclick="openModal()">Forgot Password?</button>
        </div>
        <button type="submit"
          class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-full transition duration-300">
          <i class="fas fa-sign-in-alt mr-2"></i> Login
        </button>
      </form>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div id="forgot-password-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden z-50">
    <div class="bg-gray-800 p-6 w-full max-w-sm sm:rounded-lg shadow-lg">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Reset Password</h3>
        <button type="button" class="text-gray-300" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form class="space-y-4">
        <div>
          <label class="block text-gray-300">Email Address</label>
          <input type="email" placeholder="you@example.com" required
            class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <button type="submit"
          class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-full transition duration-300">
          Send Reset Link
        </button>
      </form>
    </div>
  </div>

  <!-- Fixed Footer -->
  <footer class="fixed bottom-0 left-0 right-0 bg-gray-800 py-4 z-50">
    <div class="max-w-7xl mx-auto text-center text-white text-sm">
      &copy; 2025 JA Property Management. All rights reserved.
    </div>
  </footer>

  <script>
    function openModal() {
      document.getElementById('forgot-password-modal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('forgot-password-modal').classList.add('hidden');
    }
  </script>
</body>
</html>
