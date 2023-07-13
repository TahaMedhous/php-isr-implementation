<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album not found</title>
</head>

<body class="bg-zinc-900">
    <div class="flex flex-col items-center justify-center h-screen space-y-4">
        <h1 class="text-2xl font-bold text-white">
            Album not found
        </h1>
        <a class="text-base font-bold inline-block px-4 py-2 bg-neutral-600 text-white rounded-md hover:bg-neutral-500 transition-colors flex items-center cursor-pointer" href="{{ route('top-albums') }}">
            Go back
        </a>
    </div>
</body>

</html>