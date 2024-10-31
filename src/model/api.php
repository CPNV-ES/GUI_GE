<?php
function getAnimeDetails($id) {
    $url = 'https://graphql.anilist.co';

    // GraphQL query to get anime details by ID
    $query = '
        query ($id: Int) {
            Media(id: $id, type: ANIME) {
                title {
                    romaji
                    english
                    native
                }
                episodes
                episodesAired: nextAiringEpisode {
                    episode
                }
                startDate {
                    year
                    month
                    day
                }
                endDate {
                    year
                    month
                    day
                }
                season
                seasonYear
				status
				coverImage {
                	extraLarge
					medium
				}
				siteUrl
				description
            }
        }
    ';

    // Variables for the GraphQL query
    $variables = [
        "id" => $id
    ];

    // Initialize cURL
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'query' => $query,
        'variables' => $variables
    ]));

    // Disable SSL verification (only for development)
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if(curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Extract and format the details if available
    if (isset($data['data']['Media'])) {
        $media = $data['data']['Media'];

        // Extract title
        $title = $media['title']['romaji'] ?? $media['title']['english'] ?? $media['title']['native'];

        // Extract episodes count
        $totalEpisodes = $media['episodes'] ?? 'Unknown';
        // Calculate aired episodes based on status and nextAiringEpisode
        $airedEpisodes = 0;
        if ($media['status'] === 'RELEASING' && isset($media['nextAiringEpisode']['episode'])) {
            // If it's currently airing, calculate aired episodes
            $airedEpisodes = $media['nextAiringEpisode']['episode'] - 1;
        } elseif ($media['status'] === 'FINISHED') {
            // If the anime has finished airing, all episodes are aired
            $airedEpisodes = $totalEpisodes;
        }

        // Format start and end dates
        $startDate = isset($media['startDate']) ? formatDate($media['startDate']) : "Unknown";
        $endDate = isset($media['endDate']) ? formatDate($media['endDate']) : "Unknown";
		
        $startDateN = isset($media['startDate']) ? formatDateN($media['startDate']) : null;
        $endDateN = isset($media['endDate']) ? formatDateN($media['endDate']) : null;
        // Format season information
        $season = $media['season'] ? ucfirst(strtolower($media['season'])) . ' ' . $media['seasonYear'] : 'Unknown';

        // Return the details as an associative array
        return [
            'title' => [
				'romaji' => $media['title']['romaji'],
				'english' => $media['title']['english'],
				'native' => $media['title']['native'],
			],
            'airedEpisodes' => $airedEpisodes,
            'totalEpisodes' => $totalEpisodes,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startDateN' => $startDateN,
            'endDateN' => $endDateN,
            'season' => $season,
			'coverImage' => $media['coverImage']['extraLarge'],
			'coverThumbnail' => $media['coverImage']['medium'],
			'url' => $media['siteUrl'],
			'description' => $media['description'],
			'status' => $media['status']
        ];
    } else {
        return null;
    }
}
function formatDate($date) {
    if (isset($date['year']) && isset($date['month']) && isset($date['day'])) {
        $dateString = sprintf("%d-%02d-%02d", $date['year'], $date['month'], $date['day']);
        $dateTime = DateTime::createFromFormat('Y-m-d', $dateString);
        return $dateTime ? $dateTime->format('j M Y') : "Unknown";
    }
    return "Unknown";
}

function formatDateN($date) {
    if (isset($date['year']) && isset($date['month']) && isset($date['day'])) {
        $dateString = sprintf("%d-%02d-%02d", $date['year'], $date['month'], $date['day']);
        $dateTime = DateTime::createFromFormat('Y-m-d', $dateString);
        return $dateTime ? $dateTime->format('Y-m-d') : null;
    }
    return null;
}