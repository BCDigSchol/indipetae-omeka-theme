<?php

namespace BCLib\Indipetae;

/**
 * A metadata field that is searchable
 *
 * @package BCLib\Indipetae
 */
class SearchField extends MetadataField
{
    /**
     * Is this field a numeric or date range?
     *
     * @var bool
     */
    public $is_range = false;

    /**
     * Does this field have a controlled vocabulary?
     *
     * @var bool
     */
    public $is_controlled = false;

    /**
     * Should we load values for this field automatically?
     *
     * @var bool
     */
    public $is_loadable = false;

    public $values;
    public $search_key;

    /**
     * Does this field correspond to a facet?
     *
     * @var bool
     */
    public $has_facet = false;

    public function __construct(array $config)
    {
        parent::__construct($config);

        // Any searchable field should work as a clickable link except for call number,
        // since call numbers are unique.
        $this->is_linked = $this->label !== 'Call Number';

        $this->is_controlled = $config['controlled'] ?? $this->is_controlled;
        $this->is_loadable = $config['load_from_db'] ?? $this->is_loadable;
        $this->is_range = $config['is_range'] ?? $this->is_range;
        $this->values = $config['values'] ?? $this->values;
        $this->search_key = $config['search_key'];
        $this->has_facet = $config['has_facet'] ?? $this->has_facet;
    }

    public function getSearchLink($value): string
    {
        $key = $this->has_facet ? "facet_{$this->search_key}" : $this->search_key;
        return "/elasticsearch/search?$key=$value";
    }
}
