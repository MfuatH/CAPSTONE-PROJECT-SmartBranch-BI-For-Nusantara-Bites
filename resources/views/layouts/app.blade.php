<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBranch BI - Nusantara Bites</title>

    @vite('resources/css/app.css')

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Global palette variables */
        :root{
            --primary: #2563EB;
            --success: #22C55E;
            --warning: #FACC15;
            --danger:  #EF4444;
            --accent:  #06B6D4;
            --background-start: #F8FAFC;
            --background-mid: rgba(37,99,235,0.04);
            --background-end: #ECFEFF;
            --bg-angle: 135deg;
            --background: linear-gradient(var(--bg-angle), var(--background-start) 0%, var(--background-end) 60%);
            --card: #FFFFFF;
            --text: #1E293B;
            --muted: #475569;
            --border: #E2E8F0;
        }

        /* Page defaults */
        body { 
            background: var(--background),
                        radial-gradient(circle at 10% 18%, rgba(6,182,212,0.04), transparent 6%),
                        radial-gradient(circle at 88% 82%, rgba(37,99,235,0.03), transparent 8%);
            background-attachment: fixed;
            background-blend-mode: normal, screen, screen;
            color: var(--text) !important;
            -webkit-font-smoothing: antialiased;
        }

        /* Cards and panels */
        .rounded-xl.shadow-sm, .rounded-2xl.shadow-2xl, .card, .panel {
            background: var(--card) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        /* Small muted text */
        .text-gray-500, .text-slate-500 { color: var(--muted) !important; }

        /* Muted backgrounds */
        .bg-gray-50, .bg-slate-50 { background: #F1F5F9 !important; }

        /* Buttons and accent elements */
        .btn-primary { background: var(--primary); color: #fff; }

        /* Utility: ensure modal header contrasts */
        .modal-header { background: var(--background); }

        /* Map legacy gold (#D9A168) utility classes to primary palette */
        [class*="bg-[#D9A168]"] { background-color: var(--primary) !important; }
        [class*="bg-[#D9A168]/10"] { background-color: rgba(37,99,235,0.08) !important; }
        [class*="text-[#D9A168]"] { color: var(--primary) !important; }
        [class*="hover:text-[#D9A168]"]:hover { color: var(--primary) !important; }
        [class*="border-[#D9A168]"] { border-color: var(--primary) !important; }
        [class*="hover:bg-[#D9A168]"]:hover { background-color: var(--primary) !important; }

        /* Small helpers for primary accent usage */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background: var(--primary) !important; }
        .bg-accent { background: var(--accent) !important; }
    </style>
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