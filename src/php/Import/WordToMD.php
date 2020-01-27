<?php

namespace BCLib\Indipetae\Import;

use Docx2md\Docx2md;

require_once __DIR__ . '/docx2md.php';

/**
 * Convert a Word doc to Markdown
 *
 * Just a wrapper for matb33/docx2md (https://github.com/matb33/docx2md)
 *
 * @package BCLib\Indipetae\Import
 */
class WordToMD
{
    public static function convert(string $word_file_path): string
    {
        $converter = new Docx2md();
        $converter->parseFile($word_file_path);
        return $converter->markdown;
    }
}