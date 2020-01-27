<?php

namespace BCLib\Indipetae\Import;

/**
 * Transforms Indipetae markdown into an importable CSV
 *
 * The letter transcriptions in the Word Docs have metadata at the top. The metadata field labels
 * are in bold, followed by a colon and then the field value. Each field is on its own line. For
 * example:
 *
 *     **Sender of the letter**: Luigi Maria Fiore
 *
 *     **Grade S.J.:**Undetermined
 *
 *     **Date Submitted**: 1847/12/17
 *
 *     **From**: Naples
 *
 *     **To**: Rome
 *
 *     **Recipient:**Roothaan
 *
 *     **Anterior Desire:** undetermined
 *
 *     **Destination(s):**America
 *
 *     **Models/Saints/Missionaries:** Father Basile; Father Elet; Mary
 *
 * Note that the colons may be on the inside or outside of the Markdown punctuation.
 *
 * Following the metadata fields is the letter transcription. The transcription (front and back)
 * should preserve formatting (italics, paragraphs, etc).
 *
 * @package BCLib\Indipetae\Import
 */
class InputParser
{
    /**
     * Strings that don't have meaning but can fool our regexes
     */
    private const SKIP_STRINGS = [
        '__',
        '//'
    ];

    /**
     * Split a markdown file into individual letters
     *
     * @param string $markdown the markdown as a string
     * @param bool $save_markdown save a copy of each letter as a separate markdown file?
     * @param string|null $parts_dir if saving pages, where to put them
     * @return Batch
     */
    public static function buildLetters(string $markdown, bool $save_markdown = false, ?string $parts_dir = null): Batch
    {
        $letters = self::split($markdown);

        if ($save_markdown) {
            self::saveLetterMarkdown($letters, $parts_dir);
        }

        $batch = new Batch();

        foreach ($letters as $letter_markdown) {
            $batch->add(self::markdownToLetter($letter_markdown));
        }

        return $batch;
    }

    /**
     * Split into individual letters
     *
     * @param string $full_string
     * @return array
     */
    private static function split(string $full_string): array
    {
        // Look for a string that starts each record, and split the markdown on it.
        $delimiter = 'Transcribed by';
        $letters = explode($delimiter, $full_string);

        // The first entry is always empty
        array_shift($letters);

        // Add the delimiter back to each page, since explode strips it away.
        foreach ($letters as $i => $page) {
            $letters[$i] = $delimiter . $page;
        }

        return $letters;
    }

    /**
     * Convert letter markdown into a Letter object
     *
     * @param $letter_markdown
     * @return Letter
     */
    private static function markdownToLetter($letter_markdown): Letter
    {
        $letter = new Letter();

        $current_field = '';
        $potential_field = 'notafield';

        $matches = [];

        // Look at each line one by one.
        $lines = explode("\n", $letter_markdown);

        foreach ($lines as $line) {
            $value = $line;

            // If this looks like a metadata header line, extract its contents
            preg_match('/^\*\*([^:]+):?\*\*:?(.*)$/', $line, $matches);

            if (count($matches) > 0) {
                [$whole, $potential_field, $value] = $matches;
            }

            // If the field label looks legit, assign it to the current field.
            if ($letter->isField($potential_field)) {
                $current_field = $potential_field;
            }

            // Skip non-fields that might happen to have double-asterisks.
            if (in_array($current_field, self::SKIP_STRINGS, true)) {
                continue;
            }

            // Populate the letter.
            $letter->set($current_field, $value);
        }
        return $letter;
    }

    /**
     * Save the letter markdown files
     *
     * @param array $letters_markdown an array of markdown strings
     * @param string $parts_dir where to save them
     */
    private static function saveLetterMarkdown(array $letters_markdown, string $parts_dir): void
    {
        if (!mkdir($parts_dir) && !is_dir($parts_dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $parts_dir));
        }

        foreach ($letters_markdown as $i => $page) {
            file_put_contents("$parts_dir/letter-$i.md", $page);
        }
    }
}