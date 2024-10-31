<?php
require_once MODEL_DIR . "/api.php";
$upcomingAnime = getAnimeAiringNext7Days();
$upcomingAnime = groupAnimeByAiringDate($upcomingAnime);

$title = 'Anime Airing Schedule';
$style = "<link rel=\"stylesheet\" href=\"/assets/css/daylist.css\" />";
ob_start();
?>
<div class="container" id="container">
    <?php
$nextSevenDays = getNextSevenDays();
foreach ($nextSevenDays as $date) {
    ?>
    <div class="day-container">
        <div class="day-title"><?=$date?></div>
        <?php
            foreach (isset($upcomingAnime[$date]) ? $upcomingAnime[$date] : [] as $anime) {
                ?>
        <div class="episode-card">
            <a href="/anime/<?= $anime['id'] ?>" class="episode-link">
                <img
                    src="<?=$anime['image']?>"
                    alt=""
                    class="episode-image">
                <div class="episode-info">
                    <div class="episode-title">
                        <?=$anime['title']['romaji']?>
                    </div>
                    <div class="episode-time">Ep
                        <?=$anime['episode']?>
                        aired at
                        <?=$anime['time']?>
                    </div>
                </div>
            </a>
            <div class="add-button"><a href="/edit_anime/<?= $anime['id'] ?>">+</a></div>
        </div>
        <?php
            }
    ?>
    </div>
    <?php
}
?>
</div>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>