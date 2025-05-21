<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
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
    <div class="w-5/6 bg-[url('/images/bg-login.jpeg')] bg-cover bg-center flex p-24">
        <h1 class="text-white text-6xl font-bold">Create your<br>Account</h1>
    </div>

    <!-- Kanan: Form -->
    <div class="w-1/2 flex items-center justify-center bg-white">
        <div class="w-3/4 max-w-md">
            
            @if($errors->any())
            <ul class="text-red-500 mb-4">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            
            <form method="POST" action="/register" class="space-y-6">
                <div>
                    <img src="{{ asset('images/logo-1.png') }}" alt="" srcset="">
                </div>
                <h2 class="text-2xl font-bold text-center mb-6 text-custom-purple">Register</h2>
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full rounded-full border border-custom-purple px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-custom-purple">
                </div>

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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full rounded-full border border-custom-purple px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-custom-purple">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" required
                        class="w-full rounded-full border border-custompurple px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-custompurple">
                        <option value="student">Student</option>
                        <option value="mentor">Mentor</option>
                    </select>
                </div>


                <button type="submit"
                    class="w-full bg-custom-purple text-white py-2 rounded-md hover:bg-custom-purple-dark transition-transform transform hover:scale-105">
                    Join us!
                </button>

            </form>

            <p class="mt-4 text-center text-sm">
                Sudah punya akun? <a href="/login" class="text-custom-purple hover:underline">Login</a>
            </p>
        </div>
    </div>

</body>

</html>