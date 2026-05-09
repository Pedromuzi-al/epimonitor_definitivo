<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Sintomas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">

        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">
            Cadastro de Sintomas
        </h1>

        <form>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Nome do Sintoma</label>
                <input type="text"
                       class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Descrição</label>
                <textarea
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition">
                Salvar
            </button>

        </form>

    </div>

</body>
</html>