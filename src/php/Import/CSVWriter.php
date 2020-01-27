<?php

namespace BCLib\Indipetae\Import;

use BCLib\Indipetae\MetadataMap;
use const BCLib\Indipetae\FIELD_ANTERIOR_DESIRE;
use const BCLib\Indipetae\FIELD_ARCHIVE;
use const BCLib\Indipetae\FIELD_CALL_NUMBER;
use const BCLib\Indipetae\FIELD_CONTRIBUTOR;
use const BCLib\Indipetae\FIELD_DATE;
use const BCLib\Indipetae\FIELD_DAY;
use const BCLib\Indipetae\FIELD_DESTINATIONS;
use const BCLib\Indipetae\FIELD_FOLDER;
use const BCLib\Indipetae\FIELD_FROM;
use const BCLib\Indipetae\FIELD_GRADE;
use const BCLib\Indipetae\FIELD_LANGUAGE;
use const BCLib\Indipetae\FIELD_LEFT_FOR_MISSION;
use const BCLib\Indipetae\FIELD_LINKS;
use const BCLib\Indipetae\FIELD_MODEL;
use const BCLib\Indipetae\FIELD_MONTH;
use const BCLib\Indipetae\FIELD_NOTES;
use const BCLib\Indipetae\FIELD_NUMBER;
use const BCLib\Indipetae\FIELD_OTHER_NAMES;
use const BCLib\Indipetae\FIELD_RECIPIENT;
use const BCLib\Indipetae\FIELD_SENDER;
use const BCLib\Indipetae\FIELD_TO;
use const BCLib\Indipetae\FIELD_TRANSCRIPTION;
use const BCLib\Indipetae\FIELD_TRANSCRIPTION_BACK;
use const BCLib\Indipetae\FIELD_YEAR;

require_once __DIR__ . '/../SearchFields.php';

/**
 * Writes letters to a CSV file
 *
 * @package BCLib\Indipetae\Import
 */
class CSVWriter implements BatchWriter
{

    /** @var string */
    private $csv_filepath;

    /** @var MetadataMap */
    public $metadata_map;

    /** @var string */
    private $img_dir;

    /** @var string[] */
    private const HEADERS = [
        FIELD_SENDER,
        FIELD_GRADE,
        FIELD_DATE,
        FIELD_YEAR,
        FIELD_MONTH,
        FIELD_DAY,
        FIELD_FROM,
        FIELD_TO,
        FIELD_RECIPIENT,
        FIELD_ANTERIOR_DESIRE,
        FIELD_DESTINATIONS,
        FIELD_MODEL,
        FIELD_OTHER_NAMES,
        FIELD_LEFT_FOR_MISSION,
        FIELD_LANGUAGE,
        FIELD_LINKS,
        FIELD_NOTES,
        FIELD_CALL_NUMBER,
        FIELD_ARCHIVE,
        FIELD_FOLDER,
        FIELD_NUMBER,
        FIELD_CONTRIBUTOR,
        FIELD_TRANSCRIPTION_BACK,
        FIELD_TRANSCRIPTION
    ];

    /**
     * CSVWriter constructor.
     *
     * @param string $csv_filepath where to write the CSV file
     * @param string $img_dir where corresponding images are
     */
    public function __construct(string $csv_filepath, string $img_dir)
    {
        $this->csv_filepath = $csv_filepath;
        $this->metadata_map = MetadataMap::getMap();
        $this->img_dir = $img_dir;
    }

    /**
     * Write to the CSV file
     *
     * @param Batch $batch
     */
    public function write(Batch $batch): void
    {
        if (!$handle = fopen($this->csv_filepath, 'wb')) {
            throw new CSVFileException("Could not open {$this->csv_filepath} to write");
        }

        // Write the header
        $header_row = array_map([$this, 'buildHeader'], self::HEADERS);
        $header_row[] = 'file';
        fputcsv($handle, $header_row);

        // Write the body
        foreach ($batch as $letter) {
            echo "Writing {$letter->getCallNumber()}\n";
            fputcsv($handle, $this->buildRow($letter));
        }

        fclose($handle);
    }

    /**
     * Build one CSV row
     *
     * @param Letter $letter
     * @return string[]
     */
    private function buildRow(Letter $letter): array
    {
        return [
            $letter->getSenderOfLetter(),
            $letter->getGradeSJ(),
            $letter->getDateSubmitted(),
            $letter->getYear(),
            $letter->getMonth(),
            $letter->getDay(),
            $letter->getFrom(),
            $letter->getTo(),
            $letter->getRecipient(),
            $letter->getAnteriorDesire(),
            $letter->getDestination(),
            $letter->getModels(),
            $letter->getOtherNames(),
            $letter->getLeftForMissionLands(),
            $letter->getLanguage(),
            $letter->getLinks(),
            $letter->getNote(),
            $letter->getCallNumber(),
            $letter->getArchive(),
            $letter->getFolder(),
            "n. {$letter->getNumber()}",
            $letter->getTranscribedBy(),
            $letter->getBack(),
            $letter->getTranscription(),
            implode(',', $letter->getFiles($this->img_dir))
        ];
    }

    /**
     * Currently doesn't render stats
     *
     * @todo Make CSVWriter render stats
     *
     * @return string
     */
    public function stats(): string
    {
        return '';
    }

    /**
     * Build a single CSV header field
     *
     * @param string $local_metadata_field field ID (e.g. FIELD_SENDER)
     * @return string
     */
    private function buildHeader(string $local_metadata_field): string
    {
        return 'Dublin Core:' . $this->metadata_map->getField($local_metadata_field)->dublin_core_label;
    }
}