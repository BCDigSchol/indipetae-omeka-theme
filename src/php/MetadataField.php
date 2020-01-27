<?php

namespace BCLib\Indipetae;

/**
 * A record metadata field for theming
 *
 * @package BCLib\Indipetae
 */
class MetadataField
{
    /**
     * The local label for the field
     *
     * @var string
     */
    public $label;

    /**
     * The DC label for the field
     *
     * @var string
     */
    public $dublin_core_label;

    private $value = null;

    /**
     * The field identifier (e.g. FIELD_SENDER)
     *
     * @var string
     */
    public $field_id;

    /**
     * Is this field linkable?
     *
     * @var bool
     */
    public $is_linked = false;

    public function __construct(array $config)
    {
        $this->label = $config['label'];
        $this->dublin_core_label = $config['dc_label'];
        $this->field_id = $config['id'];
    }

    /**
     * Return empty link
     *
     * By default metadata fields are not searchable, so return an empty link. Override this
     * in descendant classes.
     *
     * @param $value
     * @return null
     */
    public function getSearchLink($value)
    {
        return null;
    }

    /**
     * Set the value for a field
     *
     * @todo this doesn't make sense
     * @param $dublin_core_fields
     */
    public function setValue($dublin_core_fields): void
    {
        $this->value = $dublin_core_fields[$this->dublin_core_label] ?? null;
    }

    /**
     * Get the value of this field for a record
     *
     * @param \Omeka_Record_AbstractRecord|null $record
     * @return mixed
     */
    public function getTexts(?\Omeka_Record_AbstractRecord $record)
    {
        return metadata($record,
            ['Dublin Core', $this->dublin_core_label],
            [
                'all' => true,
                'no_filter' => true
            ]);
    }
}