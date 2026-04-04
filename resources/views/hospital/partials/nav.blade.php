<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="LifeLink - Blood Donation System">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('hospital.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition">
                    <i class="fas fa-envelope"></i>
                </a>
                <div style="width: 1px; height: 24px; background-color: #999;"></div>
                <div class="relative pl-0">
                    <button id="menuDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                        <i class="fas fa-bars"></i>Menu
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="menuDropdownContent" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                        <a href="{{ route('hospital.requests.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-list mr-2"></i>My Requests
                        </a>
                        <a href="{{ route('hospital.requests.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-plus-circle mr-2"></i>New Request
                        </a>
                        <a href="{{ route('hospital.inventory.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-boxes mr-2"></i>Blood Inventory
                        </a>
                        <a href="{{ route('hospital.drives.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-calendar-alt mr-2"></i>Blood Drives
                        </a>
                        <a href="{{ route('hospital.reports.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-chart-bar mr-2"></i>Analytics & Reports
                        </a>
                        <hr class="border-gray-200">
                        <form action="{{ route('logout') }}" method="POST" style="display: block;">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    const menuDropdown = document.getElementById('menuDropdown');
    const menuDropdownContent = document.getElementById('menuDropdownContent');

    if (menuDropdown) {
        menuDropdown.addEventListener('click', function(e){
            e.stopPropagation();
            menuDropdownContent.classList.toggle('hidden');
        });
    }

    document.addEventListener('click', function(e){
        if (!menuDropdown.contains(e.target) && !menuDropdownContent.contains(e.target)) {
            menuDropdownContent.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') {
            menuDropdownContent.classList.add('hidden');
        }
    });
</script>