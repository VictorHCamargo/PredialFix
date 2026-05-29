<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acesso negado - PredialFix SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { senai: { red: '#E3000F', dark: '#1a1a1a' } },
                    fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 font-sans">
    @auth
        <x-navbar />
    @endauth

    <main class="mx-auto flex w-full max-w-2xl flex-1 items-center px-6 py-12">
        <div class="w-full rounded border border-gray-200 bg-white p-8 text-center shadow">
            <p class="mb-2 text-sm font-semibold uppercase text-red-600">403</p>
            <h1 class="mb-3 text-2xl font-bold text-gray-800">Acesso negado</h1>
            <p class="mb-6 text-gray-600">{{ $message ?? 'Seu nivel de acesso nao permite acessar este recurso.' }}</p>

            <a href="{{ route('dashboard') }}" class="inline-block rounded bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">
                Voltar ao dashboard
            </a>
        </div>
    </main>

    @auth
        <x-footer />
    @endauth
</body>
</html>
