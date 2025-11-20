<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <script src="{{ asset('js/app.js') }}" defer></script>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <main class="w-full lg:max-w-4xl max-w-[335px] flex-grow">
            <div class="text-center">
                <!-- Logo/Brand -->
                <div class="mb-8">
                    <h1 class="text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Clearit
                    </h1>
                    <div class="w-20 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"></div>
                </div>

                <!-- Welcome Message -->
                <div class="mb-12">
                    <h2 class="text-3xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                        Welcome to the PHP Exam MVP
                    </h2>
                    <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 leading-relaxed max-w-2xl mx-auto">
                        You are accessing a functional MVP (Minimum Viable Product) developed for the 
                        <strong>Clearit PHP Technical Examination</strong>. This application demonstrates 
                        modern Laravel development practices and features a complete ticket management system.
                    </p>
                </div>

                <!-- Features Overview -->
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                        <div class="text-2xl mb-3">🎫</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Ticket Management</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">Complete CRUD operations for transport tickets</p>
                    </div>
                    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                        <div class="text-2xl mb-3">👥</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Role-Based Access</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">User and Agent roles with different permissions</p>
                    </div>
                    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                        <div class="text-2xl mb-3">🔒</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Authentication</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">Secure login system with Laravel Breeze</p>
                    </div>
                </div>

                <!-- Login Section -->
                <div class="bg-[#dbdbd7] dark:bg-[#161615] p-8 rounded-lg">
                    <h3 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Get Started
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                        Access the application with one of the demo accounts or create your own
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                class="inline-block px-5 py-2 bg-[#1b1b18] hover:bg-black text-white dark:bg-[#eeeeec] dark:hover:bg-white dark:text-[#1C1C1A] font-medium rounded-sm transition-all">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                class="inline-block px-5 py-2 bg-[#1b1b18] hover:bg-black text-white dark:bg-[#eeeeec] dark:hover:bg-white dark:text-[#1C1C1A] font-medium rounded-sm transition-all">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                    class="inline-block px-5 py-2 border border-[#19140035] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] font-medium rounded-sm transition-all">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Demo Credentials -->
                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <p class="font-medium mb-4">Demo Accounts:</p>
                        <div class="grid sm:grid-cols-2 gap-4 max-w-lg mx-auto">
                            <div class="bg-white dark:bg-[#3E3E3A] p-4 rounded border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Agent Account</p>
                                <p class="text-[13px] leading-[20px]">Email: agent@clearit.com</p>
                                <p class="text-[13px] leading-[20px]">Password: 123456</p>
                            </div>
                            <div class="bg-white dark:bg-[#3E3E3A] p-4 rounded border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">User Account</p>
                                <p class="text-[13px] leading-[20px]">Email: user@clearit.com</p>
                                <p class="text-[13px] leading-[20px]">Password: 123456</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
