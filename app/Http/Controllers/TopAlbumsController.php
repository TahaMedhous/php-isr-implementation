<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TopAlbumsController extends Controller
{
    public function index()
    {
        $apiResponse = Http::get('https://rss.applemarketingtools.com/api/v2/us/music/most-played/25/albums.json');

        if ($apiResponse->ok()) {
            $apiData = $apiResponse->json();
            $albums = $apiData['feed']['results'];
        } else {
            // Handle error if API request fails
            $albums = [];
        }

        return view('top_albums', compact('albums'));
    }
}
