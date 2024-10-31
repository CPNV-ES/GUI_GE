<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="/anime_details.css">
    <link rel="stylesheet" href="/assets/css/global.css" />
    <link rel="stylesheet" href="/assets/css/menu.css" />
    <link rel="stylesheet" href="/assets/css/fontawesome-free-6.6.0-web/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/fontawesome-free-6.6.0-web/css/fontawesome.min.css" />
    <?= $style ?>
</head>

<body>
<?= $content ?>
<div id="circularMenu" class="circular-menu">
    <a class="floating-btn" onclick="document.getElementById('circularMenu').classList.toggle('active');">
        <i class="fa fa-plus"></i>
    </a>

    <menu class="items-wrapper">
        <a href="#" class="menu-item fa-solid fa-user"></a>
        <a href="/" class="menu-item fa-solid fa-ellipsis-vertical"></a>
        <a href="#" class="menu-item fa-solid fa-gear"></a>
        <a href="/add_anime" class="menu-item fa-solid fa-plus"></a>
    </menu>
</div>
</body>
</html>