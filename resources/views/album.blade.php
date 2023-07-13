<html>

<head>
    <title>
        @if($albumData)
        {{ $albumData['collectionName'] }} by {{ $albumData['artistName'] }}
        @else
        Album not found
        @endif
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="{{asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="{{asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

</head>

<body class="bg-zinc-900">
    <div class="p-10">
        @if($albumData)
        <div class="album-details flex flex-col text-center md:flex-row md:text-left items-center justify-start">
            <div class="w-64 h-64">
                <img src="{{ $albumData['artworkUrl400'] }}" alt="Album Cover" class="album-image rounded-md object-cover w-[16rem] h-[16rem] blur-[10px] relative opacity-75 max-w-fit">
                <img src="{{ $albumData['artworkUrl400'] }}" alt="Album Cover" class="album-image rounded-md object-cover w-64 h-64 relative top-[-16rem] border-[1px] border-[#ffffff1c] max-w-fit">
            </div>
            <div class="ml-0 md:ml-10 mt-10 md:mt-0 flex flex-col items-center md:items-start gap-4">
                <h1 class="text-2xl font-bold text-white">
                    {{ $albumData['collectionName'] }}
                </h1>
                <a class="text-xl font-bold text-white" href="{{ $albumData['artistViewUrl'] }}">{{ $albumData['artistName'] }}</a>
                <h3 class="text-xl text-white">
                    {{ $albumData['primaryGenreName'] }} &middot; {{ date('Y', strtotime($albumData['releaseDate'])) }}
                </h3>
                @if($albumData['collectionExplicitness'] == 'explicit')
                <img src="{{ asset('icons/explicit.svg') }}" alt="Explicit" class="w-6 h-6" />
                @endif
                <a class="text-base font-bold inline-block px-4 py-2 bg-neutral-600 text-white rounded-md hover:bg-neutral-500 transition-colors flex items-center cursor-pointer" onclick="playAudioMaster(`{{ $albumData['discs'][1][0]['previewUrl'] }}`, `audioPlayer`, `playIconNumber{{ $albumData['discs'][1][0]['trackNumber'] }}_{{ $albumData['discs'][1][0]['discNumber'] }}`)">
                    Preview
                </a>
            </div>
        </div>
        <hr class="mt-2 opacity-20 md:mt-10 md:mb-5">
        @foreach($albumData['discs'] as $disc)

        @if (count($albumData['discs']) > 1)
        <h1 class="text-neutral-300 text-base opacity-40 mt-4">Disc {{ $loop->iteration }}</h1>
        @endif

        @foreach($disc as $song)
        <div class="song flex flex-row items-center justify-between py-4 px-2 bg-zinc-800 hover:bg-[#0a0a0a] transition-colors border border-zinc-700 rounded-md mt-2" id="songN">
            <div class="flex items-center gap-4">
                <div class="text-sm text-white opacity-50">
                    {{ $song['trackNumber'] }}
                </div>
                <div class="flex flex-col trackName">
                    <h1 class="text-base text-white truncate">{{ $song['trackName'] }}</h1>
                </div>
            </div>
            <div id="play" class="flex items-center gap-4">
                <a target="_blank" class="text-base font-bold inline-block px-4 py-2 bg-neutral-600 text-white rounded-md hover:bg-neutral-500 transition-colors flex items-center cursor-pointer w-24" onclick="playAudioMaster(`{{ $song['previewUrl'] }}`, `audioPlayer`, `playIconNumber{{ $song['trackNumber'] }}_{{ $disc[0]['discNumber'] }}`)">
                    <i class="fa-solid fa-play inline-block w-4 h-4 mr-2" style="color: #ffffff;" id="playIconNumber{{ $song['trackNumber'] }}_{{ $disc[0]['discNumber'] }}"></i>
                    Play</a>
            </div>
        </div>
        @endforeach
        @endforeach

        <div class="fixed bottom-0 left-0 w-full">
            <audio controls id="audioPlayer" class="w-full bg-zinc-900 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 mx-auto rounded-t-md px-2 py-2 drop-shadow-md" hidden>
            </audio>
        </div>
        @endif
    </div>
</body>

</html>