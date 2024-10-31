<?php
$title = 'Anime Airing Schedule';
$style = "<link rel=\"stylesheet\" href=\"/assets/css/daylist.css\" />";
ob_start();
?>
<div class="container" id="container">
    <div class="day-container">
        <div class="day-title">Monday 07.10</div>

        <div class="episode-card">
            <a href="#" class="episode-link">
                <div class="episode-image"></div>
                <div class="episode-info">
                    <div class="episode-title">Title</div>
                    <div class="episode-time">Ep 11 aired at 06:00 AM</div>
                </div>
            </a>
            <div class="add-button">+</div>
        </div>
    </div>
    <div class="day-container">
        <div class="day-title">Tuesday 07.11</div>

        <div class="episode-card">
            <a href="#" class="episode-link">
                <div class="episode-image"></div>
                <div class="episode-info">
                    <div class="episode-title">Title</div>
                    <div class="episode-time">Ep 11 aired at 06:00 AM</div>
                </div>
            </a>
            <div class="add-button">+</div>
        </div>
    </div>
    <div class="day-container">
        <div class="day-title">Wednesday 07.11</div>

        <div class="episode-card">
            <a href="#" class="episode-link">
                <div class="episode-image"></div>
                <div class="episode-info">
                    <div class="episode-title">Title</div>
                    <div class="episode-time">Ep 11 aired at 06:00 AM</div>
                </div>
            </a>
            <div class="add-button">+</div>
        </div>
    </div>
    <div class="day-container">
        <div class="day-title">Thurday 07.11</div>

        <div class="episode-card">
            <a href="#" class="episode-link">
                <div class="episode-image"></div>
                <div class="episode-info">
                    <div class="episode-title">Title</div>
                    <div class="episode-time">Ep 11 aired at 06:00 AM</div>
                </div>
            </a>
            <div class="add-button">+</div>
        </div>
    </div>
    <div class="day-container">
        <div class="day-title">Friday 07.11</div>

        <div class="episode-card">
            <a href="#" class="episode-link">
                <div class="episode-image"></div>
                <div class="episode-info">
                    <div class="episode-title">Title</div>
                    <div class="episode-time">Ep 11 aired at 06:00 AM</div>
                </div>
            </a>
            <div class="add-button">+</div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>