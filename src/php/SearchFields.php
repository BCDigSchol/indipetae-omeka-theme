<?php

namespace BCLib\Indipetae;

/**
 * Constants used to build MetadataFields and the MetadataMap
 */

/**
 * MetadataMap keys
 */
const FIELD_TITLE = 'title';
const FIELD_GRADE = 'grade';
const FIELD_MODEL = 'jmodel';
const FIELD_LINKS = 'links';
const FIELD_OTHER_NAMES = 'other_name';
const FIELD_DESTINATIONS = 'destinations';
const FIELD_LANGUAGE = 'language';
const FIELD_ARCHIVE = 'archive';
const FIELD_RECIPIENT = 'recipient';
const FIELD_TRANSCRIPTION = 'transcription';
const FIELD_TRANSCRIPTION_BACK = 'transcription_back';
const FIELD_DATE = 'date';
const FIELD_DATE_RANGE = 'date_range';
const FIELD_SENDER = 'sender';
const FIELD_FROM = 'from';
const FIELD_CONTRIBUTOR = 'transcribed_by';
const FIELD_ANTERIOR_DESIRE = 'anterior_desire';
const FIELD_NOTES = 'notes';
const FIELD_TO = 'to';
const FIELD_LEFT_FOR_MISSION = 'left_for_mission';
const FIELD_DOCUMENT_ID = 'document_id';
const FIELD_COLLECTION = 'coll';
const FIELD_FOLDER = 'folder';
const FIELD_NUMBER = 'number';
const FIELD_YEAR = 'year';
const FIELD_MONTH = 'month';
const FIELD_DAY = 'day';
const FIELD_FROM_INSTITUTION = 'from_institution';

// @todo Immediately instantiate MetadataFields and the MetadataMap instead of creating a config array
const METADATA_FIELDS = [
    FIELD_TITLE => [
        'label' => 'Title',
        'id' => '50',
        'dc_label' => 'Title',
        'search_field' => true,
    ],
    FIELD_TRANSCRIPTION => [
        'id' => '41',
        'label' => 'Transcription',
        'dc_label' => 'Description',
        'search_field' => true,
        'controlled' => false,
        'is_range' => false
    ],
    FIELD_TRANSCRIPTION_BACK => [
        'id' => '78',
        'label' => 'Transcription (back)',
        'dc_label' => 'Extent',
        'search_field' => false,
    ],
    FIELD_SENDER => [
        'id' => '39',
        'label' => 'Sender',
        'dc_label' => 'Creator',
        'search_field' => true,
        'has_facet' => true
    ],
    FIELD_GRADE => [
        'id' => '76',
        'label' => 'Grade S.J.',
        'dc_label' => 'Replaces',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_DATE => [
        'id' => '59',
        'label' => 'Date',
        'dc_label' => 'Date Submitted',
        'search_field' => true,
        'is_range' => true
    ],
    FIELD_DATE_RANGE => [
        'id' => '123456789',
        'label' => 'Date range',
        'dc_label' => 'Date range',
        'search_field' => true,
        'is_date_range' => true
    ],
    FIELD_YEAR => [
        'id' => '40',
        'label' => 'Year',
        'dc_label' => 'Date',
        'search_field' => true,
        'is_range' => true
    ],

    FIELD_MONTH => [
        'id' => '82',
        'label' => 'Month',
        'dc_label' => 'Temporal Coverage',
        'search_field' => true,
        'controlled' => true,
        'values' => [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ],
        'has_facet' => true
    ],

    FIELD_DAY => [
        'id' => '75',
        'label' => 'Day',
        'dc_label' => 'References',
        'search_field' => true,
        'controlled' => false
    ],

    FIELD_FROM => [
        'id' => '38',
        'label' => 'From (City)',
        'dc_label' => 'Coverage',
        'search_field' => true,
        'has_facet' => true,
        'controlled' => true,
        'load_from_db' => true,
    ],
    FIELD_FROM_INSTITUTION => [
        'id' => '70',
        'label' => 'From (Institution)',
        'dc_label' => 'Is Part Of',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_TO => [
        'id' => '81',
        'label' => 'To',
        'dc_label' => 'Spatial Coverage',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_RECIPIENT => [
        'id' => '86',
        'label' => 'Recipient',
        'dc_label' => 'Audience',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_ANTERIOR_DESIRE => [
        'id' => '79',
        'label' => 'Anterior Desire',
        'dc_label' => 'Medium',
        'search_field' => true,
        'controlled' => true,
        'values' => ['Yes', 'No', 'Undetermined']
    ],
    FIELD_DESTINATIONS => [
        'id' => '45',
        'label' => 'Destination(s)',
        'dc_label' => 'Publisher',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_MODEL => [
        'id' => '49',
        'label' => 'Models/Saints/Missionaries',
        'dc_label' => 'Subject',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_OTHER_NAMES => [
        'id' => '46',
        'label' => 'Other Names',
        'dc_label' => 'Relation',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
        'has_facet' => true
    ],
    FIELD_LEFT_FOR_MISSION => [
        'id' => '60',
        'label' => 'Left for mission lands',
        'dc_label' => 'Date Issued',
        'search_field' => true,
        'controlled' => true,
        'values' => ['Yes', 'No', 'To be determined']
    ],
    FIELD_LANGUAGE => [
        'id' => '44',
        'label' => 'Language of the Letter',
        'dc_label' => 'Language',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
    ],
    FIELD_LINKS => [
        'id' => '48',
        'label' => 'Links',
        'dc_label' => 'Source',
        'search_field' => true,
        'has_facet' => true
    ],
    FIELD_NOTES => [
        'id' => '53',
        'label' => 'Notes',
        'dc_label' => 'Abstract',
        'search_field' => true,
    ],
    FIELD_ARCHIVE => [
        'id' => '43',
        'label' => 'Archive',
        'dc_label' => 'Identifier',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
    ],
    FIELD_FOLDER => [
        'id' => '66',
        'label' => 'Folder',
        'dc_label' => 'Has Format',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true,
    ],
    FIELD_NUMBER => [
        'id' => '68',
        'label' => 'Number',
        'dc_label' => 'Has Version',
        'search_field' => true,
        'is_range' => true
    ],
    FIELD_CONTRIBUTOR => [
        'id' => '37',
        'label' => 'Transcriber',
        'dc_label' => 'Contributor',
        'search_field' => true,
        'controlled' => true,
        'load_from_db' => true
    ],
    FIELD_COLLECTION => [
        'id' => '111111',
        'label' => 'Collection',
        'dc_label' => 'Collection',
        'search_field' => true,
        'controlled' => true,
        'values' => ['New Society (1814-1958)']
    ]
];
