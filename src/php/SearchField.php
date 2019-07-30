<?php

namespace BCLib\Indipetae;

class SearchField extends MetadataField
{

    public $is_range = false;
    public $is_controlled = false;
    public $load_from_db = false;
    public $values;
    public $search_key;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->is_controlled = $config['controlled'] ?? $this->is_controlled;
        $this->load_from_db = $config['load_from_db'] ?? $this->load_from_db;
        $this->is_range = $config['is_range'] ?? $this->is_range;
        $this->values = $config['values'] ?? $this->values;
        $this->search_key = $config['search_key'];
        $this->is_linked = $this->label !== 'Call Number';
    }

    public function getSearchLink($value)
    {
        $text_value = html_escape($value);
        return "/elasticsearch/search?{$this->search_key}=$text_value";
    }
}