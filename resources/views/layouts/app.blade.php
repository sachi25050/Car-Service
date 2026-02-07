<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Car Service Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    @auth
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
         :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
         x-show="sidebarOpen || window.innerWidth >= 1024"
         @click.away="if (window.innerWidth < 1024) sidebarOpen = false">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-car-front text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Car Service</h1>
                    <p class="text-xs text-gray-500">Management System</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('customers.index') }}" 
                   class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people text-lg"></i>
                    <span>Customers</span>
                </a>

                <a href="{{ route('vehicles.index') }}" 
                   class="sidebar-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                    <i class="bi bi-car-front text-lg"></i>
                    <span>Vehicles</span>
                </a>

                <a href="{{ route('appointments.index') }}" 
                   class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check text-lg"></i>
                    <span>Appointments</span>
                </a>

                <a href="{{ route('job-cards.index') }}" 
                   class="sidebar-link {{ request()->routeIs('job-cards.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check text-lg"></i>
                    <span>Job Cards</span>
                </a>

                <a href="{{ route('invoices.index') }}" 
                   class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt text-lg"></i>
                    <span>Invoices</span>
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Settings</p>
                    <a href="{{ route('services.index') }}" 
                       class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                        <i class="bi bi-tools text-lg"></i>
                        <span>Services</span>
                    </a>

                    <a href="{{ route('service-packages.index') }}" 
                       class="sidebar-link {{ request()->routeIs('service-packages.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam text-lg"></i>
                        <span>Packages</span>
                    </a>

                    <a href="{{ route('reports.index') }}" 
                       class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up text-lg"></i>
                        <span>Reports</span>
                    </a>

                    @can('manage_users')
                    <div class="pt-2 mt-2 border-t border-gray-200">
                        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin</p>
                        <a href="{{ route('admin.users.index') }}" 
                           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill text-lg"></i>
                            <span>Users</span>
                        </a>
                        <a href="{{ route('admin.staff.index') }}" 
                           class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge text-lg"></i>
                            <span>Staff</span>
                        </a>
                        <a href="{{ route('admin.roles.index') }}" 
                           class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-check text-lg"></i>
                            <span>Roles</span>
                        </a>
                    </div>
                    @endcan
                </div>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->role->display_name ?? 'No Role' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
         @click="sidebarOpen = false"
         style="display: none;"></div>
    @endauth

    <!-- Main Content -->
    <div class="@auth lg:pl-64 @endauth">
        @auth
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="bi bi-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down text-gray-500"></i>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                             style="display: none;">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="bi bi-person mr-2"></i> Profile
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Main Content Area -->
        <main class="@auth py-6 @else min-h-screen @endauth">
            <div class="@auth mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl @else @endauth">
                <!-- Flash Messages -->
                @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-0.5"></i>
                        <div class="flex-1">
                            <p class="font-medium mb-1">Please fix the following errors:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
