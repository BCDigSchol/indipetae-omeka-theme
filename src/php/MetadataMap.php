<?php

namespace BCLib\Indipetae;

require_once __DIR__.'/MetadataField.php';
require_once __DIR__.'/SearchField.php';

/**
 * Map Indipetae project metadata fields to Omeka's internal DC fields
 *
 * The project does not use the default DC metadata labels ('names' in the omeka_elements table),
 * but it does store data in those fields under different labels. The MetadataMap connects
 * Indipetae fields to the corresponding DC fields.
 *
 * The map's internal structure:
 *
 *     [
 *       Field key 1 => MetadataField 1,
 *       Field key 2 => MetadataField 2,
 *       Field key 3 => MetadataField 3,
 *       ...
 *     ]
 *
 * Where the field keys are strings stored as BCLib\Indipetae\FIELD_* constants (e.g. FIELD_SENDER,
 * FIELD_MONTH). Those constants and the corresponding configuration arrays for the MetadataFields
 * are found in SearchFields.php.
 *
 * @package BCLib\Indipetae
 */
class MetadataMap
{
    /**
     * The MetadataMap is a singleton to ensure that all sources are loading from the same map.
     *
     * @var MetadataMap
     */
    private static $instance = null;

    /** @var MetadataField[] */
    private $fields;

    /**
     * Get the MetadataMap
     *
     * @return MetadataMap
     */
    public static function getMap(): MetadataMap
    {
        if (self::$instance === null) {
            self::$instance = new MetadataMap(METADATA_FIELDS);
        }
        return self::$instance;
    }

    /**
     * Private constructor
     *
     * @param array $list_config
     */
    private function __construct(array $list_config)
    {
        foreach ($list_config as $key => $member_config) {
            $member_config['search_key'] = $key;
            $this->fields[$key] = $member_config['search_field'] ? new SearchField($member_config) : new MetadataField($member_config);
        }
    }

    /**
     * Get a metadata field
     *
     * @param string $key an Indipetae\FIELD_* constant (e.g. Indipetae\FIELD_SENDER)
     * @return MetadataField
     */
    public function getField(string $key): MetadataField
    {
        return $this->fields[$key];
    }
}
