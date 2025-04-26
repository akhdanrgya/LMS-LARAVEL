<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    .bg-custom-purple {
      background-color: #766BD8;
    }
    .text-custom-purple {
      color: #766BD8;
    }
    .border-custom-purple {
      border-color: #766BD8;
    }
    .hover\:bg-custom-purple-dark:hover {
      background-color: #685fc7;
    }
  </style>
</head>
<body class="h-screen flex">

  <!-- Kiri: Welcome Text -->
  <div class="w-1/2 bg-custom-purple flex items-center justify-center">
    <h1 class="text-white text-4xl font-bold">Hello,<br> welcome!</h1>
  </div>

  <!-- Kanan: Form -->
  <div class="w-1/2 flex items-center justify-center bg-white">
    <div class="w-3/4 max-w-md">
      @if($errors->any())
        <p class="text-red-500 mb-4">{{ $errors->first() }}</p>
      @endif

      <form method="POST" action="/login" class="space-y-6">
        @csrf
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" name="email" id="email" required
            class="w-full rounded-full border border-custom-purple px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-custom-purple">
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full rounded-full border border-custom-purple px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-custom-purple">
        </div>

        <div class="text-right">
          <a href="/forgot-password" class="text-sm text-custom-purple hover:underline">Forgot Password?</a>
        </div>

        <div class="flex space-x-4">
          <button type="submit"
            class="flex-1 bg-custom-purple text-white py-2 rounded-md hover:bg-custom-purple-dark">Login</button>
          <a href="/register"
            class="flex-1 text-custom-purple border border-custom-purple py-2 rounded-md text-center hover:bg-purple-50">Sign up</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
