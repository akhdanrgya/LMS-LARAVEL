<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Noting - King Akhdan 👑</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <x-navbar />

    <main class="p-6 max-w-4xl mx-auto">
        {{ $slot }}
    </main>
</body>
</html>
