<?php

use Ajaxray\PHPWatermark\Watermark;

require_once __DIR__ . '/../vendor/autoload.php';

CONST WATERMARK_TEXT = '© Archivum Romanum Societatis Iesu';
CONST IMG_DIR = __DIR__.'/../../../files/thumbnails';

$dsn = 'mysql:dbname=indipetae;host=127.0.0.1';
$user = 'indipetae';
$password = trim($argv[1]);

try {
    $dbh = new PDO($dsn, $user, $password);
    echo "Connected\n";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$sql = <<<SQL
SELECT omeka_files.filename, omeka_files.original_filename
FROM omeka_files
WHERE omeka_files.original_filename LIKE '%v.jpg'
ORDER BY added ASC
SQL;

$stmt = $dbh->query($sql);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $entry) {
    echo "Updating {$entry['filename']}\n";
    addWatermark(IMG_DIR."/{$entry['filename']}");
}


function addWatermark(string $filepath): void
{
    $watermark = new Watermark($filepath);
    $watermark->setFontSize(32)
        ->setFont('/System/Library/Fonts/Times.ttc')
        ->setOpacity(.4)
        ->setPosition(Watermark::POSITION_BOTTOM_LEFT)
        ->withText(WATERMARK_TEXT, $filepath);
}