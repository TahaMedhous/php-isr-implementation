<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ISRController;

class AlbumController extends Controller
{
    private const ALBUM_CACHE_DURATION = 60; // Cache duration in seconds
    private const ITUNES_API_URL = 'https://itunes.apple.com/lookup';

    public function show(int $id)
    {
        $dataCallback = function ($id) {
            return $this->fetchAlbumData($id);
        };

        return (new ISRController)->GetData($id, $dataCallback, self::ALBUM_CACHE_DURATION, 'album', 'albumData');
    }

    private function fetchAlbumData(int $id)
    {
        $apiResponse = Http::get(self::ITUNES_API_URL, ['id' => $id, 'entity' => 'album,song']);

        if ($apiResponse->ok() && $apiResponse->json()['resultCount'] > 0) {
            $apiData = $apiResponse->json();
            $albumData = $apiData['results'][0];

            $albumData['songs'] = [];

            foreach ($apiData['results'] as $result) {
                if ($result['wrapperType'] === 'track') {
                    $albumData['songs'][] = $result;
                }
            }

            $discs = [];
            foreach ($albumData['songs'] as $song) {
                $discs[$song['discNumber']][] = $song;
            }
            $albumData['discs'] = $discs;

            foreach ($albumData['discs'] as $discNumber => $disc) {
                usort($albumData['discs'][$discNumber], function ($a, $b) {
                    return $a['trackNumber'] <=> $b['trackNumber'];
                });
            }
            $albumData['previewUrl'] = $albumData['discs'][1][0]['previewUrl'];
            unset($albumData['songs']);
            // get rid of the songs array since we don't need it anymore

            $albumData['artworkUrl400'] = str_replace('100x100', '400x400', $albumData['artworkUrl100']);

            return $albumData;
        }

        return null;
    }
}
