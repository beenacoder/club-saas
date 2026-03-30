<html>

<head>
    <title>Club SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    @auth
        <nav class="bg-white shadow p-4 flex gap-4">
            <a href="/dashboard">Dashboard</a>
            <a href="/socios">Socsios</a>
            <a href="/users">Empleados</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button >
                    Salir
                </button>
            </form>
        </nav>
    @endauth

    <main class="p-6">
        {{ $slot }}
    </main>

</body>

</html>
