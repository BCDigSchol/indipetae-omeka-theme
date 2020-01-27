<?php

namespace BCLib\Indipetae\Import;

use Ajaxray\PHPWatermark\Watermark;

/**
 * Processes and builds list of images to output
 *
 * In addition to metadata, the CSV import includes a list of files to be attached to each item. This
 * requires some image processing:
 *
 *   * add a watermark to the first image for each file
 *   * copy the image files to a holding directory
 *
 * Once processed, we can list the files per image.
 *
 * The image files should be in a directory and numbered by letter number. Unnumbered images will be the front
 * (first) image. Later images should have a "v" added for every position verso. All image files should be
 * JPEGs with a 'jpg' file extension.
 *
 * For example:
 *
 *     * 137.jpg
 *     * 137v.jpg
 *     * 137vv.jpg
 *     * 138.jpg
 *     * 139.jpg
 *     * 139v.jpg
 *     * etc...
 *
 * @package BCLib\Indipetae\Import
 */
class ImageListBuilder
{
    /** @var string */
    private $input_dir;

    /** @var string */
    private $output_dir;

    /** @var string */
    private $watermark_text;

    /**
     * ImageListBuilder constructor
     *
     * @param string $input_dir where the image files are now
     * @param string $output_dir where you want the image files to be
     * @param string $watermark_text the text to use to watermark images
     * @throws ImageListException
     */
    public function __construct(
        string $input_dir,
        string $output_dir,
        string $watermark_text = '© Archivum Romanum Societatis Iesu'
    ) {
        if (!is_dir($input_dir)) {
            throw new ImageListException("Could not find image input directory $input_dir");
        }
        $this->input_dir = $input_dir;

        if (!is_dir($output_dir)) {
            throw new ImageListException("Could not find image output directory $output_dir");
        }
        $this->output_dir = $output_dir;
        $this->watermark_text = $watermark_text;
    }

    /**
     * Process the image files and build the list
     *
     * @param string $file_number
     * @return string[]
     */
    public function findFiles(string $file_number): array
    {
        $files = [];

        $full_path = "{$this->input_dir}/{$file_number}.jpg";

        // String containing 'v' characters to indicate verso
        $vs = '';

        while (file_exists($full_path)) {

            // Copy versos. Recto will be copied by watermarking function.
            if ($vs !== '') {
                copy($full_path, "{$this->output_dir}/{$file_number}{$vs}.jpg");
            }

            $files[] = "{$file_number}{$vs}.jpg";

            // Add another 'v' and look for the next verso.
            $vs .= 'v';
            $full_path = "{$this->input_dir}/{$file_number}{$vs}.jpg";
        }

        // The first file should always be watermarked.
        if (count($files) > 0) {
            $this->addWatermark($file_number);
        }

        return $files;
    }

    /**
     * Watermark a file
     *
     * @param string $file_number
     * @todo make font-loading cross-platform
     *
     */
    private function addWatermark(string $file_number): void
    {
        $output_file = "{$this->output_dir}/$file_number.jpg";

        $watermark = new Watermark("{$this->input_dir}/$file_number.jpg");
        $watermark->setFontSize(128)
            ->setFont('/System/Library/Fonts/Times.ttc')
            ->setOpacity(.4)
            ->setPosition(Watermark::POSITION_BOTTOM_LEFT)
            ->withText($this->watermark_text, $output_file);
    }
}