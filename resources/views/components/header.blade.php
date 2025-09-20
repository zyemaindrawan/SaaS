<header class="flex justify-between items-center py-6">
    <div class="flex items-center space-x-2">
        <span class="font-bold text-lg">{{env('APP_NAME')}}</span>
    </div>
    <nav class="hidden md:flex items-center space-x-8">
        <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="{{route('templates.index')}}">Pilih Tema</a>
        <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="#">Fitur</a>
        <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="#">Portofolio</a>
    </nav>
    <div class="flex items-center space-x-4">
        @auth
            <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="#">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary">Logout</button>
            </form>
        @else
            <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="{{route('login')}}">Login</a>
            <a class="text-subtext-light dark:text-subtext-dark hover:text-primary dark:hover:text-primary" href="{{route('register')}}">Register</a>
        @endauth
        <a href="{{route('templates.index')}}"><button class="bg-primary text-white px-6 py-2 rounded-lg font-semibold">Pilih Tema</button></a>
    </div>
    
    <!-- Mobile menu button -->
    <div class="md:hidden">
        <button id="mobile-menu-btn" class="text-subtext-light dark:text-subtext-dark hover:text-primary">
            <span class="material-icons">menu</span>
        </button>
    </div>
</header>

<!-- Mobile menu -->
<div id="mobile-menu" class="hidden md:hidden bg-background-light dark:bg-background-dark border-t border-border-light dark:border-border-dark">
    <nav class="px-4 py-4 space-y-2">
        <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="{{route('templates.index')}}">Pilih Tema</a>
        <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="#">Fitur</a>
        <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="#">Portofolio</a>
        @auth
            <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="#">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="text-subtext-light dark:text-subtext-dark hover:text-primary py-2">Logout</button>
            </form>
        @else
            <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="{{route('login')}}">Login</a>
            <a class="block text-subtext-light dark:text-subtext-dark hover:text-primary py-2" href="{{route('register')}}">Register</a>
        @endauth
    </nav>
</div>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>