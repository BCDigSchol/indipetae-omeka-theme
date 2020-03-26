<?php

use Ajaxray\PHPWatermark\Watermark;

require_once __DIR__ . '/../vendor/autoload.php';

const WATERMARK_TEXT = '© Archivum Romanum Societatis Iesu';
const IMG_DIR = __DIR__ . '/../../../letter-images';

$letters = ['655', '688', '689', '704', '709', '741', '740'];

foreach ($letters as $letter) {
    echo "$letter\n";
    processLetters($letter);
}

function processLetters(string $letter)
{
    $filepath = IMG_DIR . "/$letter.jpg";

    if (!file_exists($filepath)) {
        return;
    }

    echo "\t$letter\n";
    addWatermark($filepath);

    $next_letter = "{$letter}v";
    processLetters($next_letter);
}

function addWatermark(string $filepath): void
{
    $watermark = new Watermark($filepath);
    $watermark->setFontSize(96)
        ->setFont('/System/Library/Fonts/Times.ttc')
        ->setOpacity(.4)
        ->setPosition(Watermark::POSITION_BOTTOM_LEFT)
        ->withText(WATERMARK_TEXT, $filepath);
}