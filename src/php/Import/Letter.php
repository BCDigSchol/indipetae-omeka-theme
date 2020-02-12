<?php

namespace BCLib\Indipetae\Import;

use Michelf\Markdown;
use PHPUnit\Runner\Exception;

const NUMBER_TO_MONTH = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

/**
 * Maps labels that might occur in a Markdown file to their corresponding parameter
 */
const INPUT_TO_PARAM_MAP = [
    'sender of the letter' => 'sender_of_letter',
    'sender' => 'sender_of_letter',
    'grade s.j.' => 'grade_SJ',
    'date submitted' => 'date_submitted',
    'date' => 'date_submitted',
    'from' => 'from',
    'to' => 'to',
    'recipient' => 'recipient',
    'anterior desire' => 'anterior_desire',
    'destination(s)' => 'destination',
    'destinations' => 'destination',
    'destination' => 'destination',
    'models/saints/missionaries' => 'models',
    'other names' => 'other_names',
    'left for mission lands' => 'left_for_mission_lands',
    'language' => 'language',
    'links' => 'transcription',
    'note' => 'note',
    'call number' => 'call_number',
    'transcribed by' => 'transcribed_by',
    'back' => 'back',
    'bibliography' => 'transcription'
];

/**
 * Represents a letter for import purposes
 *
 * @package BCLib\Indipetae\Import
 */
class Letter
{
    private $sender_of_letter;
    private $grade_SJ;
    private $date_submitted;
    private $from;
    private $to;
    private $recipient;
    private $anterior_desire;
    private $destination;
    private $models;
    private $other_names;
    private $left_for_mission_lands;
    private $language;
    private $links;
    private $note;
    private $call_number;
    private $transcribed_by;
    private $back;
    private $transcription;

    /** @var string[] */
    private $files;

    /**
     * Fields that need to retain their formatting
     */
    private const FORMATTED_FIELDS = ['transcription', 'back'];

    /**
     * Does this string represent an actual field?
     *
     * Used to check input from a Markdown field to see if is an actual field
     *
     * @param string|null $potential_field
     * @return bool
     */
    public function isField(?string $potential_field): bool
    {
        $potential_field = strtolower($potential_field);
        return array_key_exists($potential_field, INPUT_TO_PARAM_MAP);
    }

    /**
     * Set a value for a field
     *
     * @param string $field a field label from a Markdown file
     * @param $value
     */
    public function set(string $field, $value): void
    {
        $field = strtolower($field);
        if (!$this->isField($field)) {
            throw new BadImportFieldException("$field is not a valid letter field");
        }

        // Determine the correct property to set
        $property = INPUT_TO_PARAM_MAP[$field];

        if (!property_exists($this, $property)) {
            throw new BadImportFieldException("$property is not a valid letter property");
        }

        // Various string cleanup.
        $value = str_replace('****', '', $value);
        $value = trim($value);

        // Multi-line markdown fields should be joined by two newlines to render them as
        // paragraphs. Otherwise, connect lines by spaces.
        $joiner = $this->isFormattedField($property) ? "\n\n" : ' ';

        if ($this->$property === null) {
            $this->$property = $value;
        } else {
            $this->$property .= $joiner . $value;
        }

    }

    public function getSenderOfLetter()
    {
        return $this->sender_of_letter;
    }

    public function getGradeSJ()
    {
        return $this->grade_SJ;
    }

    public function getDateSubmitted()
    {
        return $this->date_submitted;
    }

    public function getYear()
    {
        // Extract year from full date.
        preg_match('/(\d\d\d\d)/', $this->getDateSubmitted(), $matches);
        return $matches[1] ?? '';
    }

    public function getMonth()
    {
        // Extract month from full date.
        preg_match('/\d\d\d\d\/\d?\d\/(\d?\d)/', $this->getDateSubmitted(), $matches);
        if (!isset($matches[1]) || !$matches[1]) {
            return '';
        }
        $month_num = (int)$matches[1];

        if (!isset(NUMBER_TO_MONTH[$month_num])) {
            throw new BadImportFieldException("$month_num is not a valid month number");
        }

        return NUMBER_TO_MONTH[$month_num];
    }

    public function getDay()
    {
        // Extract day from full date.
        preg_match('/\d\d\d\d\/(\d?\d)\/\d?\d/', $this->getDateSubmitted(), $matches);
        return isset($matches[1]) ? $matches[1] ?? '' : '';
    }

    public function getFrom()
    {
        return $this->cleanMultiValues($this->from);
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getAnteriorDesire()
    {
        return $this->anterior_desire;
    }

    public function getDestination()
    {
        return $this->cleanMultiValues($this->destination);
    }

    public function getModels()
    {
        return $this->cleanMultiValues($this->models);
    }

    public function getOtherNames()
    {
        return $this->cleanMultiValues($this->other_names);
    }

    public function getLeftForMissionLands()
    {
        return $this->left_for_mission_lands;
    }

    public function getLanguage()
    {
        return $this->cleanMultiValues($this->language);
    }

    public function getLinks()
    {
        return $this->cleanMultiValues($this->links);
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getCallNumber()
    {
        return $this->call_number;
    }

    public function getTranscribedBy()
    {
        return $this->transcribed_by;
    }

    public function getBack(bool $as_html = true): string
    {
        return $as_html ? Markdown::defaultTransform($this->back) : $this->back;
    }

    public function getTranscription(bool $as_html = true): string
    {
        return $as_html ? Markdown::defaultTransform($this->transcription) : $this->transcription;
    }

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @param string $directory
     * @return array
     */
    public function getFiles(string $directory = ''): array
    {
        if (is_array($directory)) {
            throw new Exception("It's an array!");
        }

        return preg_filter('/^/', $directory . '/', $this->files);
    }

    public function getNumber(): string
    {
        // Number does not include the starting "n. ".
        preg_match('/n\. ?(\d+)/', $this->call_number, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        throw new \RuntimeException("Could not find number in {$this->call_number}");
    }

    public function getArchive()
    {
        // Archive is extracted from the call number.
        preg_match('/^(.*), ?AIT/', $this->call_number, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        throw new \RuntimeException("Could not find archive in {$this->call_number}");
    }

    public function getFolder()
    {
        // Folder is extracted from the call number.
        preg_match('/(AIT \d) n\. ?\d+/', $this->call_number, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        throw new \RuntimeException("Could not find folder in {$this->call_number}");
    }

    /**
     * Convert single-line fields to multiple value fields
     *
     * The Omeka CSV import differentiates multiple-values in a field using vertical pipes.
     * Letter transcribers usually use a semi-colon. Make sure to only run this function
     * on fields that will not contain natural semi-colons.
     *
     * @param string|null $value
     * @return string
     */
    private function cleanMultiValues(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        $cleaned = preg_replace('/ *; */', '|', $value);
        return trim($cleaned);
    }

    /**
     * Does this field need to be formatted?
     *
     * @param $prop
     * @return bool
     */
    private function isFormattedField($prop): bool
    {
        return in_array($prop, self::FORMATTED_FIELDS, true);
    }
}