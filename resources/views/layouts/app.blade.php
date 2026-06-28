<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SERRA - Timber Management</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-[#1e293b] antialiased h-screen flex flex-col">
    <header class="h-16 bg-white border-b border-[#e2e8f0] flex items-center px-6 z-10 shrink-0 justify-between">
        <div class="w-56 flex items-center space-x-3 shrink-0">
            <svg class="h-7 w-7 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7" fill="none"/>
                <path d="M2 12l10 5 10-5" />
                <path d="M2 17l10 5 10-5" />
            </svg>
            <span class="font-bold text-lg tracking-tight text-[#0f172a]">SERRA<span class="text-xs font-semibold text-gray-400 align-super ml-0.5">™</span></span>
        </div>
        <div class="flex-1 flex items-center justify-between pl-6">
            <div class="flex items-center space-x-2">
                <button class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h1 class="font-semibold text-[#0f172a] text-base">@yield('tab_name', 'Dashboard')</h1>
            </div>
            <div class="flex items-center px-2">
                <button class="w-12 h-5 bg-[#e2e8f0] rounded-full p-0.5 flex items-center justify-start focus:outline-none transition relative shadow-inner">
                    <div class="bg-[#1e293b] w-4 h-4 rounded-full shadow flex items-center justify-center overflow-hidden border-2 border-[#1e293b]">
                        <div class="w-2 h-4 bg-white mr-auto"></div>
                    </div>
                </button>
            </div>
        </div>

    </header>
    <div class="flex flex-1 overflow-hidden">
        <aside class="w-64 bg-white border-r border-[#e2e8f0] flex flex-col justify-between p-5 shrink-0">
            <div class="space-y-6">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">El meu espai</div>
                <nav class="space-y-1.5">
                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Fustes mecanitzades</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Comandes</span>
                    </a>
                </nav>
            </div>
            <div class="border-t border-gray-100 pt-5 space-y-1.5">
                <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 rounded-xl transition">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>El meu perfil</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-500 hover:text-red-600 rounded-xl transition">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
            <div class="max-w-[1600px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>