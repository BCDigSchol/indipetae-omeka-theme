<?php

namespace BCLib\Indipetae;

class MetadataField
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $dublin_core_label;

    private $value = null;

    /**
     * @var string
     */
    public $field_id;

    /**
     * @var bool
     */
    public $is_linked = false;

    public function __construct(array $config)
    {
        $this->label = $config['label'];
        $this->dublin_core_label = $config['dc_label'];
        $this->field_id = $config['id'];
    }

    public function getSearchLink($value)
    {
        return null;
    }

    public function setValue($dublin_core_fields)
    {
        $this->value = $dublin_core_fields[$this->dublin_core_label] ?? null;
    }

    public function getTexts($record)
    {
        return metadata($record,
            ['Dublin Core', $this->dublin_core_label],
            [
                'all' => true,
                'no_filter' => true
            ]);
    }
}