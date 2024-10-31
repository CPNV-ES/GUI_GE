<?php
function getAnimeDetails($id) {
    $url = 'https://graphql.anilist.co';

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

    $variables = [
        "id" => $id
    ];

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

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['data']['Media'])) {
        $media = $data['data']['Media'];

        $title = $media['title']['romaji'] ?? $media['title']['english'] ?? $media['title']['native'];

        $totalEpisodes = $media['episodes'] ?? 'Unknown';
        $airedEpisodes = 0;
        if ($media['status'] === 'RELEASING' && isset($media['nextAiringEpisode']['episode'])) {
            $airedEpisodes = $media['nextAiringEpisode']['episode'] - 1;
        } elseif ($media['status'] === 'FINISHED') {
            $airedEpisodes = $totalEpisodes;
        }

        $startDate = isset($media['startDate']) ? formatDate($media['startDate']) : "Unknown";
        $endDate = isset($media['endDate']) ? formatDate($media['endDate']) : "Unknown";
		
        $startDateN = isset($media['startDate']) ? formatDateN($media['startDate']) : null;
        $endDateN = isset($media['endDate']) ? formatDateN($media['endDate']) : null;
        $season = $media['season'] ? ucfirst(strtolower($media['season'])) . ' ' . $media['seasonYear'] : 'Unknown';

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


function getAnimeAiringNext7Days() {
    $airingSoon = [];
	for ($i = 1; $i <= 3; $i++) {
		$airingSoon = getAnimeAiringPage($i);
	}

    return $airingSoon;
}

function getAnimeAiringPage($page) {
    $url = 'https://graphql.anilist.co';

    $currentDate = time();
    $next7Days = $currentDate + (7 * 24 * 60 * 60);

    $query = '
            query ($page: Int) {
                Page(page: $page, perPage: 50) {
                    media(type: ANIME, status: RELEASING, sort: POPULARITY_DESC) {
						id
                        title {
                            romaji
                            english
                            native
                        }
                        nextAiringEpisode {
                            episode
                            airingAt
                        }
						coverImage {
							medium
						}
                    }
                    pageInfo {
                        hasNextPage
                    }
                }
            }
        ';

	$variables = [
		"$page" => $page
	];

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

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    $airingSoon = [];
    if (isset($data['data']['Page']['media'])) {
        foreach ($data['data']['Page']['media'] as $anime) {
            if (isset($anime['nextAiringEpisode']['airingAt']) &&
                $anime['nextAiringEpisode']['airingAt'] >= $currentDate &&
                $anime['nextAiringEpisode']['airingAt'] <= $next7Days) {
                $airingDate = date('Y-m-d H:i:s', $anime['nextAiringEpisode']['airingAt']);
				$airingTime =  date('g:i A', $anime['nextAiringEpisode']['airingAt']);
                $airingSoon[] = [
					'title' => [
					'romaji' => $anime['title']['romaji'],
					'english' => $anime['title']['english'],
					'native' => $anime['title']['native'],
					],
                    'episode' => $anime['nextAiringEpisode']['episode'],
                    'airingAt' => $airingDate,
					'time' => $airingTime,
					'id' => $anime['id'],
					'image' => $anime['coverImage']['medium']
                ];
            }
        }
    }

    return $airingSoon;
}
function groupAnimeByAiringDate($animeList) {
    $groupedAnime = [];

    foreach ($animeList as $anime) {
        $airingDate = date('l j M', strtotime($anime['airingAt']));
        if (!isset($groupedAnime[$airingDate])) {
            $groupedAnime[$airingDate] = [];
        }
        $groupedAnime[$airingDate][] = $anime;
    }

    return $groupedAnime;
}

function getNextSevenDays() {
    $dates = [];
    for ($i = 0; $i < 7; $i++) {
        $date = date('l j M', strtotime("+$i days"));
        $dates[] = $date;
    }

    return $dates;
}