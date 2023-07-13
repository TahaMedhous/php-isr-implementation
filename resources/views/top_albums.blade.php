<!-- resources/views/top_albums.blade.php -->
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Top 10 Albums</title>
</head>

<body class="bg-zinc-900 p-4">
    <h1 class="text-2xl font-bold text-white text-center">Top 10 Albums</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4 p-2">
        @foreach($albums as $album)
        <div class="album flex flex-col items-center justify-center bg-zinc-800 rounded-md p-4">
            <img src="{{ $album['artworkUrl100'] }}" alt="Album Cover" class="rounded-md object-cover border-[1px] border-[#ffffff1c] max-w-fit">
            <h1 class="text-xl font-bold text-white mt-4">{{ $album['name'] }}</h1>
            <a class="text-base font-bold inline-block px-4 py-2 bg-neutral-600 text-white rounded-md hover:bg-neutral-500 transition-colors flex items-center cursor-pointer" href="{{ route('album', ['id' => $album['id']]) }}">
                View Album
            </a>
        </div>
        @endforeach
    </div>

</body>

</html>