<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBranch BI - Nusantara Bites</title>

    @vite('resources/css/app.css')

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#F2F5F8] text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <!-- Tempat Sidebar Nanti -->
        @include('components.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Tempat Top Navbar Nanti -->
            @include('components.navbar')

            <!-- Tempat Konten Berubah-ubah (Dashboard, dll) -->
            <main class="w-full grow p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>