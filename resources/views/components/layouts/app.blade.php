<html>
<head>
    <title>Club SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <nav class="bg-white shadow p-4 flex gap-4">
        <a href="/dashboard">Dashboard</a>
        <a href="/socios">Socios</a>
    </nav>

    <main class="p-6">
        {{ $slot }}
    </main>

</body>
</html>
