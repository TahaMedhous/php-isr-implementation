<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AlbumController extends Controller
{
    private const ALBUM_CACHE_DURATION = 60; // Cache duration in seconds
    private const ITUNES_API_URL = 'https://itunes.apple.com/lookup';

    public function show(int $id)
    {
        $cacheKey = 'album_' . $id;
        $cachedData = Cache::get($cacheKey) ?? [];
        $albumData = $cachedData['data'] ?? null;
        $timestamp = $cachedData['timestamp'] ?? null;
        $currentTime = time();

        if ($albumData === null && $timestamp === null) {
            // album has never been generated before
            $albumData = $this->fetchAlbumData($id);

            if ($albumData) {
                $albumData['timesGenerated'] = 0;
                $cachedData = ['data' => $albumData, 'timestamp' => $currentTime];
                Cache::put($cacheKey, $cachedData, self::ALBUM_CACHE_DURATION);
            } else {
                // Handle error if API request fails, album not found, etc.
                // throw new \Exception("Failed to fetch album data.");

                return view('404');
            }
        } elseif ($albumData && ($currentTime - $timestamp) >= self::ALBUM_CACHE_DURATION) {
            // album data is stale, so try to fetch new data to see if it has changed
            $newAlbumData = $this->fetchAlbumData($id);

            if ($newAlbumData && $this->hasAlbumDataChanged($albumData, $newAlbumData)) {
                // album data has changed, so update the cache
                $newAlbumData['timesGenerated'] = $albumData['timesGenerated'] + 1;
                $cachedData = ['data' => $newAlbumData, 'timestamp' => $currentTime];
                Cache::put($cacheKey, $cachedData, self::ALBUM_CACHE_DURATION);
                $albumData = $newAlbumData;
            }

            // album data has not changed, so just update the timestamp to keep it fresh
        }

        return view('album', compact('albumData'));
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

    private function hasAlbumDataChanged(array $oldData, array $newData): bool
    {
        $serializedOldData = serialize($oldData);
        $serializedNewData = serialize($newData);
        // converts the array into a string, so that the comparison is easier and precise

        return $serializedOldData !== $serializedNewData;
    }
}
