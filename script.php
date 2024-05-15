<?php

use PHPHtmlParser\Dom;

include "vendor/autoload.php";
$concertUrl='https://www.auditorium-lyon.com/fr/saison-2023-24/symphonique/tchaikovski-pathetique';
//$concertUrl = $_GET['url'];
$baseURL='https://www.auditorium-lyon.com';

$page = file_get_contents($concertUrl);
$dom = new Dom();
$dom->loadStr($page);

$imageURL = $baseURL . ($dom->find('.Cover-img img'))[0]->getAttribute('src');
$enTete = ($dom->find('.Cover-text .col-sm-8'))[0]->innerHtml();
$infosConcert = ($dom->find('.Aside-section-infos'))[0]->outerHtml();
$mainContent = ($dom->find('.main-content article'))[0]->outerHtml();
$reservationURL = ($dom->find('.Cover-text a'))[0]->getAttribute('href');

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Titre de la page</title>
    <script src="./qrcode.min.js"></script>
    <style type="text/css">
        body {
            print-color-adjust: exact;
            font-size:24px
        }

        .header {
            display: flex;
            color: white;
            position: relative;
        }

        .header img {
            filter: opacity(50%);
        }

        .header-content {
            position: absolute;
            top: 100%;
            transform: translate(0, -100%);
            z-index: 1;
            color: white;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.5)
        }

        h1 {
            font-size: 3em;
        }

        .header .event-date, .header a { display:none}
        .content-wrapper {
            display:flex;
        }
        .main-content {
            width:75%
        }

        /*
            L'iframe concerne les extraits musicaux
         */
        iframe, #popin_tarifs {
            display: none;
        }

        .info-concert {
            margin: 25px;
            margin-top: 100px;
        }

        dt {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 24px;

        }
        h3 {
            font-size: 1.4em
            text-align: center;
        }
        article {
            display:flex;
            flex-flow: row wrap;
        }

        article > p:first-of-type {
            margin-top: 50px;
            border-top: solid black;
            padding-top:25px;
        }

        .accordeon {
            width: 45%;
        }


    </style>
</head>
<body>
<div class="header">
    <img src="<?= $imageURL?>"/>
    <div class="header-content"><?= $enTete ?></div>
</div>
<!-- Le reste du contenu -->
<div class="content-wrapper">
    <div class="info-concert">
        <?= $infosConcert ?>
        <div id="qrcode"></div>
        <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), "<?= $reservationURL?>");
        </script>
    </div>
    <div class="main-content"><?= $mainContent ?></div>
</div>
<script type="text/javascript">
    window.onload = function() { window.print(); }

</script>
</body>
</html>

