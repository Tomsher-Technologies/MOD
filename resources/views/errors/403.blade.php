<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Adjust based on your build system --}}
</head>
<body class="bg-gray-50 text-gray-800 flex items-center justify-center min-h-screen p-6">
    <div class="bg-white shadow-xl rounded-2xl p-10 max-w-md text-center border-t-4 border-red-500">
        <div class="mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/463/463612.png" alt="Forbidden"
                 class="w-24 h-24 mx-auto opacity-80">
        </div>
        <h1 class="text-5xl font-bold text-red-600 mb-4">403</h1>
        <h2 class="text-xl font-semibold mb-2">Access Denied</h2>
        <p class="text-gray-600 mb-6">You don’t have permission to access this page. Please check your credentials or contact the administrator.</p>

        <button onclick="history.back()"
                class="px-6 py-2 text-white bg-red-500 hover:bg-red-600 rounded-lg transition font-medium">
            ← Go Back
        </button>
    </div>
</body>
</html>
