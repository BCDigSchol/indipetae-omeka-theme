<?php

namespace BCLib\Indipetae;

require_once __DIR__.'/MetadataField.php';
require_once __DIR__.'/SearchField.php';

const METADATA_MAP = [
    'Title' => 'Title',
    'Transcription' => 'Description',
    'Transcription (back)' => 'Extent',
    'Sender' => 'Creator',
    'Grade S.J.' => 'Replaces',
    'Year' => 'Date',
    'Month' => 'Temporal Coverage',
    'Date' => 'Date Submitted',
    'Day' => 'References',
    'From (City)' => 'Coverage',
    'From (Institution)' => 'Is Part Of',
    'To' => 'Spatial Coverage',
    'Recipient' => 'Audience',
    'Anterior desire' => 'Medium',
    'Destination(s)' => 'Publisher',
    'Models/Saints/Missionaries' => 'Subject',
    'Other names' => 'Relation',
    'Left for mission lands' => 'Date Issued',
    'Language of the Letter' => 'Language',
    'Links' => 'Source',
    'Notes' => 'Abstract',
    'Archive' => 'Identifier',
    'Collection' => 'Has Format',
    'Sub-collection' => 'Has Part',
    'Number' => 'Has Version',
    'Contributor' => 'Contributor'
];

class MetadataMap
{

    /**
     * @var MetadataMap
     */
    private static $instance = null;

    /**
     * @var MetadataField[]
     */
    private $fields;

    /**
     * @return MetadataMap
     */
    public static function getMap()
    {
        if (self::$instance === null) {
            self::$instance = new MetadataMap(METADATA_FIELDS);
        }
        return self::$instance;
    }

    private function __construct(array $list_config)
    {
        foreach ($list_config as $key => $member_config) {
            $member_config['search_key'] = $key;
            $this->fields[$key] = $member_config['search_field'] ? new SearchField($member_config) : new MetadataField($member_config);
        }
    }

    public function getField(string $key): MetadataField
    {
        return $this->fields[$key];
    }
}