<x-layouts.app>
    <div class="min-h-screen bg-gray-50 flex flex-col">

        <!-- NAVBAR -->
        <header class="w-full max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <h1 class="text-lg font-bold text-green-600">
                ClubSaaS
            </h1>

            <!-- Navegación -->
            <div class="flex items-center gap-3">

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-sm text-gray-600 hover:text-green-600 transition">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            Salir
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm text-gray-600 hover:text-green-600 transition">
                        Ingresar
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-5 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Registrarse
                    </a>
                @endauth

            </div>
        </header>

        <!-- HERO -->
        <main class="flex-1 flex items-center justify-center text-center px-6">
            <div class="max-w-2xl">

                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
                    Gestioná tu club de forma
                    <span class="text-green-600">simple</span>
                </h2>

                <p class="mt-4 text-gray-500 text-lg">
                    Controlá socios, cuotas y pagos desde un solo lugar.
                    Sin planillas, sin complicaciones.(Viene desde Laragon)
                </p>

                <div class="mt-8 flex justify-center gap-4">

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                            Ir al dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                            Empezar gratis
                        </a>

                        <a href="{{ route('login') }}"
                           class="px-6 py-3 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Ya tengo cuenta
                        </a>
                    @endauth

                </div>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="text-center text-sm text-gray-400 py-6">
            © {{ date('Y') }} ClubSaaS — Todos los derechos reservados
        </footer>

    </div>
</x-layouts.app>
