<?php require MODEL_DIR . "/api.php";
$id = isset($matches[1]) ? $matches[1] : 0;
$anime = getAnimeDetails($id);

$isAdmin = true;
$title = 'Anime Detail Card';
$style = "<link rel=\"stylesheet\" href=\"/assets/css/anime_details.css\">";
ob_start();
?>
<style>
    .form-container {
        width: 300px;
        background-color: #7BAAC2;
        border-radius: 15px;
        padding: 20px;
        color: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-title {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .form-section {
        font-size: 1.1em;
        font-weight: bold;
        margin-top: 15px;
        margin-bottom: 5px;
    }

    .form-label {
        font-size: 0.9em;
        margin-bottom: 5px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        margin-right: 10px;
        border: none;
        border-radius: 5px;
        font-size: 0.9em;
        box-sizing: border-box;

    }

    .form-textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: none;
        border-radius: 5px;
        font-size: 0.9em;
        height: 80px;
        resize: none;
        box-sizing: border-box;
    }

    .form-select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: none;
        border-radius: 5px;
        font-size: 0.9em;
        background-color: #f5f5f5;
    }

    .save-button {
        width: 100%;
        padding: 10px;
        background-color: #2D3E50;
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 1em;
    }

    .form-file-input {
        display: none;
        /* Hide the default file input */
    }

    .custom-file-label {
        display: inline-block;
        width: 100%;
        padding: 10px;
        background-color: #2D3E50;
        color: white;
        text-align: center;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 0.9em;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    .custom-file-label:hover {
        background-color: #1C2B3C;
    }
</style>
<div class="detail-card">
    <div>
        <div class="form-title">Title</div>

        <label class="form-label">Romaji</label>
        <input type="text" class="form-input" placeholder="Sousou no Frieren"
            value="<?=isset($anime['title']['romaji']) ? $anime['title']['romaji'] : '' ?>" />

        <label class="form-label">English</label>
        <input type="text" class="form-input" placeholder="Frieren: Beyond Journey’s End"
            value="<?=isset($anime['title']['english']) ? $anime['title']['english'] : ''?>" />

        <label class="form-label">Native</label>
        <input type="text" class="form-input" placeholder="葬送のフリーレン"
            value="<?=isset($anime['title']['native']) ? $anime['title']['native'] : ''?>" />

        <div class="form-section">Release Data</div>

        <label class="form-label">Release status</label>
        <select class="form-select">
            <option>Currently releasing</option>
            <option value="FINISHED">Finished</option>
            <option>Not yet released</option>
        </select>

        <label class="form-label">Season</label>
        <select class="form-select">
            <?php
            $seasons = ["Winter", "Spring", "Summer", "Fall"];
$currentYear = date("Y");
$endYear = $currentYear + 3;

for ($year = $endYear; $year >= 2010; $year--) {
    foreach ($seasons as $season) {
        echo "<option>{$season} {$year}</option>";
    }
}
?>
        </select>

        <label class="form-label">Aired Episode count</label>
        <input type="number" class="form-input" placeholder="13"
            value="<?=isset($anime['airedEpisodes']) ? $anime['airedEpisodes'] : ''?>" />
        <label class="form-label">Total Episode count</label>
        <input type="number" class="form-input" placeholder="26"
            value="<?=isset($anime['totalEpisodes']) ? $anime['totalEpisodes'] : ''?>" />

        <label class="form-label">Description</label>
        <textarea class="form-textarea"
            placeholder="Enter description here..."><?=isset($anime['description']) ? $anime['description'] : ''?></textarea>

        <label class="form-label">Start Date</label>
        <input type="date" class="form-input"
            value="<?= isset($anime['startDateN']) ? $anime['startDateN'] : '' ?>" />

        <label class="form-label">End Date</label>
        <input type="date" class="form-input"
            value="<?= isset($anime['endDateN']) ? $anime['endDateN'] : '' ?>" />

        <label class="form-label">Cover image</label>
        <label for="cover-image" class="custom-file-label">Upload Cover Image</label>
        <input type="file" id="cover-image" class="form-file-input" accept="image/*" />

        <a href="#" class="action-button">Save</a>
    </div>
    <script>
        document.getElementById('cover-image').addEventListener('change', function() {
            const label = document.querySelector('.custom-file-label');
            const fileName = this.files[0] ? this.files[0].name : 'Upload Cover Image';
            label.textContent = fileName;
        });
    </script>
</div>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>