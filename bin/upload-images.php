<?php

use Ajaxray\PHPWatermark\Watermark;

require_once __DIR__ . '/../vendor/autoload.php';

const WATERMARK_TEXT = '© Archivum Romanum Societatis Iesu';
const IMG_DIR = __DIR__ . '/../../../letter-images';

$items_file = file_get_contents(__DIR__ . '/../../../items.json');
$items_list = json_decode($items_file, false);

$letters = [];

echo count($items_list) . " items\n";

$skipping = 0;
$uploading = 0;

$skipped = [];

foreach ($items_list as $item) {

    $letter = new Letter();
    $letter->id = $item->id;
    foreach ($item->element_texts as $element_text) {
        if ($element_text->element->id === 68) {
            $matches = [];

            $letter->call_number = $element_text->text;
        }
    }
    // Skip items with files.
    if ($item->files->count > 1) {
        $skipped[] = $letter;
        $skipping++;
        continue;
    }
    $uploading++;

    $letters[$letter->call_number] = $letter;
}

usort($skipped, function ($a, $b) {
    return $a->call_number > $b->call_number;
});

foreach ($skipped as $letter) {
    echo $letter->call_number . "\n";
}

echo "Skipped: $skipping\nUploaded: $uploading\n";

$uploader = new Uploader();

foreach ($letters as $letter) {
    $uploader->uploadFiles($letter);
}

class Uploader
{
    private $success = false;

    public function __construct()
    {
        // Set the autoloader.
        require_once __DIR__ . '/../../../ZendFramework-2.4.13/library/Zend/Loader/StandardAutoloader.php';
        $loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));
        $loader->registerNamespace('ZendService\Omeka', __DIR__ . '/Omeka.php');
        $loader->register();

        require_once __DIR__ . '/Omeka.php';

        // Instantiate the client and set the authentication key.
        $this->omeka = new ZendService\Omeka\Omeka('https://indipetae.bc.edu/api');
        $this->omeka->setKey('6d5f52052ab9373f7344182bf786584ff0efc01a');
    }

    public function uploadFiles(Letter $letter)
    {
        echo "{$letter->id}\n";
        $file = "{$letter->call_number}.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}v.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vv.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vvv.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vvvv.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vvvvv.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vvvvvv.jpg";
        $this->uploadFile($file, $letter->id);
        $file = "{$letter->call_number}vvvvvvv.jpg";
        $this->uploadFile($file, $letter->id);
    }

    private function uploadFile($file, $id)
    {
        $path = IMG_DIR . "/$file";
        if (file_exists($path)) {
            //addWatermark($path);
            $this->success = true;
            echo "\t$file\n";
            $item = json_encode(['item' => ['id' => $id]]);
            $response = $this->omeka->files->post($path, $item);
            rename($path, IMG_DIR . "/_finished/$file");
        }
    }
}

class Letter
{
    public $id;
    public $call_number;
}

/**
 * // POST /files
 * // Note the different signature. You can set callbacks to the client that handle
 * // custom request behavior. This callback is set in the constructor, but others
 * // can be set suing ZendService\Omeka\Omeka::setCallback().
 * $response = $omeka->files->post('/path/to/file', '{"item":{"id":1}}');
 * echo $response->getBody();
 *
 * // PUT /items/:id
 * $response = $omeka->items->put(1, '{}');
 * echo $response->getBody();
 *
 * // DELETE /items/:id
 * $response = $omeka->items->delete(1);
 * echo $response->getBody();
 */

function addWatermark(string $filepath): void
{
    $watermark = new Watermark($filepath);
    $watermark->setFontSize(96)
        ->setFont('/System/Library/Fonts/Times.ttc')
        ->setOpacity(.4)
        ->setPosition(Watermark::POSITION_BOTTOM_LEFT)
        ->withText(WATERMARK_TEXT, $filepath);
}