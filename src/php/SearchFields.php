<?php

namespace BCLib\Indipetae;

const FIELD_TITLE = 'title';
const FIELD_GRADE = 'grade';
const FIELD_MODEL = 'jmodel';
const FIELD_LINKS = 'links';
const FIELD_OTHER_NAMES = 'other_name';
const FIELD_DESTINATIONS = 'destinations';
const FIELD_LANGUAGE = 'Language';
const FIELD_CALL_NUMBER = 'call_number';
const FIELD_RECIPIENT = 'recipient';
const FIELD_TRANSCRIPTION = 'transcription';
const FIELD_DATE = 'date';
const FIELD_SENDER = 'sender';
const FIELD_FROM = 'from';
const FIELD_TRANSCRIBED_BY = 'transcribed_by';
const FIELD_ANTERIOR_DESIRE = 'anterior_desire';
const FIELD_NOTES = 'notes';
const FIELD_TO = 'to';
const FIELD_LEFT_FOR_MISSION = 'left_for_mission';
const FIELD_DOCUMENT_ID = 'document_id';
const FIELD_ARCHIVE = 'collection';

const SEARCH_FIELDS = [
    FIELD_TITLE => [
        'id' => '50',
        'dc_label' => 'Title',
        'controlled' => false,
        'range' => false,
    ],
    FIELD_GRADE => [
        'id' => '76',
        'dc_label' => 'Replaces',
        'controlled' => true,
        'values' => 'from_db'
    ],
    FIELD_MODEL => [
        'id' => '49',
        'dc_label' => 'Subject',
        'controlled' => false,
        'range' => false
    ],
    FIELD_LINKS => [
        'id' => '48',
        'dc_label' => 'Source',
        'controlled' => false,
        'range' => false
    ],
    FIELD_OTHER_NAMES => [
        'id' => '46',
        'dc_label' => 'Relation',
        'controlled' => false,
        'range' => false

    ],
    FIELD_DESTINATIONS => [
        'id' => '45',
        'dc_label' => 'Publisher',
        'controlled' => true,
        'values' => 'from_db'
    ],
    FIELD_LANGUAGE => [
        'id' => '44',
        'dc_label' => 'Language',
        'controlled' => false,
        'range' => false
    ],
    FIELD_CALL_NUMBER => [
        'id' => '43',
        'dc_label' => 'Identifier',
        'controlled' => false,
        'range' => false
    ],
    FIELD_RECIPIENT => [
        'id' => '86',
        'dc_label' => 'Audience',
        'controlled' => false,
        'range' => false
    ],
    FIELD_TRANSCRIPTION => [
        'id' => '41',
        'dc_label' => 'Description',
        'controlled' => false,
        'range' => false
    ],
    FIELD_DATE => [
        'id' => '40',
        'dc_label' => 'Year',
        'controlled' => false,
        'range' => true
    ],
    FIELD_SENDER => [
        'id' => '39',
        'dc_label' => 'Creator',
        'controlled' => false,
        'range' => false
    ],
    FIELD_FROM => [
        'id' => '38',
        'dc_label' => 'Coverage',
        'controlled' => false,
        'range' => false
    ],
    FIELD_TRANSCRIBED_BY => [
        'id' => '37',
        'dc_label' => 'Contributor',
        'controlled' => false,
        'range' => false
    ],
    FIELD_ANTERIOR_DESIRE => [
        'id' => '79',
        'dc_label' => 'Medium',
        'controlled' => true,
        'values' => 'from_db'
    ],
    FIELD_NOTES => [
        'id' => '53',
        'dc_label' => 'Abstract',
        'controlled' => false,
        'range' => false
    ],
    FIELD_TO => [
        'id' => '81',
        'dc_label' => 'Spatial Coverage',
        'controlled' => true,
        'values' => 'from_db'
    ],
    FIELD_LEFT_FOR_MISSION => [
        'id' => '60',
        'dc_label' => 'Date Issued',
        'controlled' => true,
        'values' => 'from_db'
    ],
    FIELD_DOCUMENT_ID => [
        'id' => '78',
        'dc_label' => 'Extent',
        'controlled' => false,
        'range' => false
    ],
    FIELD_ARCHIVE => [
        'id' => '111111',
        'dc_label' => 'Collection',
        'controlled' => true,
        'values' => ['New Society (1814-1939']
    ]
];