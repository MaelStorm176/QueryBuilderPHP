<?php
use DevCoder\QueryBuilder;

require_once 'class/autoload.php';
require_once 'db/MyPDO.php';

$pdo = MyPDO::getInstance();

$tracks = QueryBuilder::select("track.*", "album.title as AT", "genre.Name as GN", "mediatype.Name as MN")
    ->from('Track')
    ->innerJoin("album", "genre", "mediatype")
    ->where("track.AlbumId = album.AlbumId", "track.GenreId = genre.GenreId", "track.MediaTypeId = mediatype.MediaTypeId")
    ->where('Milliseconds > 30000')
    ->where('Milliseconds < 40000')
    ->where('Composer IS NOT NULL')
    ->orderBy('Milliseconds')
    ->limit(10)
    ->get($pdo);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Query Builder</title>

    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- PICO CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
</head>
<body>
    <main class="container">
        <table role="grid">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Album</th>
                    <th scope="col">MediaType</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Size</th>
                    <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($tracks as $track){
                    $track->Milliseconds = date("i:s", $track->Milliseconds);
                    echo <<<HTML
                    <tr>
                        <th scope='row'>$track->TrackId</th>
                        <td>$track->Name</td>
                        <td>$track->AT</td>
                        <td>$track->MN</td>
                        <td>$track->GN</td>
                        <td>$track->Milliseconds</td>
                        <td>$track->Bytes</td>
                        <td>$track->UnitPrice</td>
                    </tr>
                    HTML;
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
