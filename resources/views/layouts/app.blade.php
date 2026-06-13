<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>SmartBranch BI - Nusantara Bites</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-[#F2F5F8] text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        @include('components.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            @include('components.navbar')

            <main class="w-full grow p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>