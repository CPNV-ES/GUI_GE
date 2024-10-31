<?php require MODEL_DIR . "/api.php";
$id = $matches[1];
$anime = getAnimeDetails($id);

$isAdmin = true;

$title = 'Anime Detail Card';
$style = "<link rel=\"stylesheet\" href=\"/assets/css/anime_details.css\">";
ob_start();
?>

<body>
	<?php if ($anime != null): ?>
	<div class="detail-card">
		<div class="row">
			<div class="image-placeholder">
				<img src="<?=$anime['coverImage']?>"
					alt="">
				<div class="title-bar">
					<?=$anime['title']['english']?>
				</div>
				</img>
			</div>
			<div class="anime-info">
				<p>Aired
					episodes<br><?=$anime['airedEpisodes']."/".$anime['totalEpisodes']?>
				</p>
				<p>Start
					Date<br><?=$anime['startDate']?>
				</p>
				<p>End
					Date<br><?=$anime['endDate']?>
				</p>
				<p>Season<br><?=$anime['season']?>
				</p>
			</div>
		</div>

		<div class="description">
			<?=$anime['description']?>
		</div>
		<a href="<?=$anime['url']?>"
			class="action-button">Open in AniList</a>
		<a href="/" class="action-button">Return</a>
		<?php if($isAdmin): ?>
		<a href="/edit_anime/<?= $id?>"
			class="action-button">Edit</a>
		<a href="#" class="action-button">Delete</a>
		<?php endif; ?>
	</div>
	<?php else: ?>
	<div class="detail-card">
		<div class="description">
			Anime not found.
		</div>
		<a href="/" class="action-button">Return</a>
		<?php endif; ?>
		<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>