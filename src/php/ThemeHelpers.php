<?php

namespace BCLib\Indipetae;

use BCLib\Indipetae\ViewModel\Range;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/SearchFields.php';

/**
 * Helper methods for use in view templates
 *
 * These are a variety of helper methods called from different templates. All methods are static.
 *
 * @package BCLib\Indipetae
 */
class ThemeHelpers
{
    public const THEME_PATH = '/themes/minimalist';
    public const CSS_PATH = self::THEME_PATH . '/css';
    public const JS_PATH = self::THEME_PATH . '/javascripts';
    public const IMG_PATH = self::THEME_PATH . '/img';

    /**
     * Get attributes  advanced search <form> element
     *
     * @param $formAttributes
     * @return string
     */
    public static function advSearchFormAttributes($formAttributes): string
    {
        $formAttributes['action'] = url([
            'controller' => 'elasticsearch',
            'action' => 'search'
        ]);
        $formAttributes['id'] = 'indipetae-advanced-search-form';
        $formAttributes['method'] = 'GET';
        return tag_attributes($formAttributes);
    }

    /**
     * Generate HTML for an Advanced Search input field
     *
     * Given the name of the field (from SearchFields.php), generates the HTML needed to add that element as an
     * available Advanced Search field.
     *
     * @param string $field_name
     * @return string
     */
    public static function advSearchInput(string $field_name): string
    {
        $fields = MetadataMap::getMap();

        /**
         * @var SearchField $field
         */
        $field = $fields->getField($field_name);

        // The "collection" field is a special case, because it is derived from a Collection name instead of an
        // ElementText.
        if ($field_name === FIELD_COLLECTION) {
            $input_tag = self::advSearchSelect($field, $field_name);
            return self::advancedSearchInputTemplate($field_name, 'Collection', $input_tag);
        }

        if ($field->is_controlled) {
            $input_tag = self::advSearchSelect($field, $field_name); // Pre-filled downs
        } elseif ($field->is_range) {
            $input_tag = self::advSearchRange($field_name); // Range fields
        } else {
            $input_tag = self::advSearchTextInput($field_name); // Plain text input
        }

        $field_label = $field->label;

        return self::advancedSearchInputTemplate($field_name, $field_label, $input_tag);
    }

    /**
     * Return a list of viewable fields with data
     *
     * Some records do not have data for all fields. Given an input array of desired fields to
     * display, return an array of fields that have values.
     *
     * @param string[] $wanted_elements array of BCLib\Indipetae\FIELD_* values (see SearchFields.php)
     * @param array $dublin_core_metadata
     * @return MetadataField[]
     */
    public static function elementsToDisplay(array $wanted_elements, array $dublin_core_metadata): array
    {
        $display_elements = [];
        $metadata_fields = MetadataMap::getMap();
        foreach ($wanted_elements as $element) {
            $field = $metadata_fields->getField($element);
            if (isset($dublin_core_metadata[$field->dublin_core_label])) {
                $field->setValue($dublin_core_metadata);
                $display_elements[] = $field;
            }
        }
        return $display_elements;
    }

    /**
     * Advanced search text input field
     *
     * @param string $field_name
     * @return string
     */
    private static function advSearchTextInput(string $field_name): string
    {
        return <<<TAG
<input class="advanced-search-field__input" type="text" id="$field_name" name="{$field_name}[][or]" />
TAG;
    }

    /**
     * Build a <select> for Advanced Search
     *
     * @param SearchField $field
     * @param string $field_name
     * @return string
     */
    private static function advSearchSelect(SearchField $field, string $field_name): string
    {

        // Archives are a special case, since they are derived from a Collection rather than an ElementText.
        if ($field_name === FIELD_COLLECTION) {
            $values = ['New Society (1814-1939)'];
        } else {
            $field_id = $field->field_id;
            $values = $field->is_loadable ? self::getElementTextsFromDB($field_id) : $field->values;
        }
        return self::selectBox($field_name, $values);
    }


    /**
     * Generate an advanced search <select> tag and <option>s
     *
     * @param string $field_name
     * @param $values
     * @return string
     */
    private static function selectBox(string $field_name, $values): string
    {
        // Build the <option> elements.
        $options = array_map(self::class . '::selectOption', $values);
        array_unshift($options,
            '<option value="" class="advanced-search-field__please_select" selected disabled hidden >Select</option>');
        $options_tags = implode("\n", $options);

        return <<<TAG
<select class="advanced-search-field__select" type="text" id="$field_name" name="{$field_name}[][or]">
        $options_tags
</select>
TAG;
    }

    /**
     * Advanced search min max range input pair
     *
     * @param string $field_name
     * @return string
     */
    private static function advSearchRange(string $field_name): string
    {
        $min_field_name = "{$field_name}_min";
        $max_field_name = "{$field_name}_max";

        return <<<TAG
        <div class="advanced-search-field__range-inputs"  data-field="$field_name">
<label for="$min_field_name" class="advanced-search-field__range-label" data-point="min" data-field="$field_name">Min.</label>
<input class="advanced-search-field__input--range advanced-search-field__input--range__min" type="text" id="$min_field_name" name="{$min_field_name}" />
<label for="$max_field_name" class="advanced-search-field__range-label" data-point="max" data-field="$field_name">Max.</label>
<input class="advanced-search-field__input--range advanced-search-field__input--range__max" type="text" id="$max_field_name" name="{$max_field_name}" />
</div>
TAG;
    }

    /**
     * Get all the element text values for a given field
     *
     * @param $field_id
     * @return string[]
     */
    private static function getElementTextsFromDB($field_id): array
    {
        // Load all the element texts
        $table = get_db()->getTable('ElementText');
        $select = new \Omeka_Db_Select();
        $select->distinct()
            ->from(['t' => 'omeka_element_texts'], ['t.text'])
            ->join(['i' => 'omeka_items'], 't.record_id = i.id', [])
            ->where('t.element_id = ?', $field_id)
            ->where('i.public = ?', 1);
        $values = $table->fetchObjects($select);

        // Strip leading and trailing spaces.
        $values = array_map(static function ($value) {
            return trim($value->text);
        }, $values);

        // Return a sorted list of unique values.
        sort($values);
        return array_unique($values);
    }

    private static function selectOption($value): string
    {
        return "<option>$value</option>";

    }

    /**
     * Render an Advanced Search input template
     *
     * @param string $name
     * @param string $label
     * @param string $input_tag
     * @return string
     */
    private static function advancedSearchInputTemplate(string $name, string $label, string $input_tag): string
    {
        return <<<HTML
<div id="adv-search__{$name}_template" style="display: none">
    <div class="advanced-search-field adv-search-field--{$name} row">
        <div class="col-md-2 advanced-search-field__label-row">
            <label class="advanced-search-field__label" for="$name">$label</label>
        </div>
        <div class="col-md-10">
             $input_tag
             <button class="advanced-search-field__delete-button"  type="button" data-field="$name">Remove $label</button>
        </div>
    </div>
</div>
HTML;
    }

    /**
     * Get the minimum and maximum years for all texts in the database.
     *
     * @return Range
     */
    public static function getMinMaxYears(): Range
    {
        $db = get_db();
        $select_sql = <<<SQL
SELECT MIN(omeka_element_texts.text) as min_year,
       MAX(omeka_element_texts.text) as max_year
FROM omeka_element_texts
WHERE omeka_element_texts.element_id = 40
AND omeka_element_texts.text <> '';
SQL;
        $result = $db->getTable('ElementText')->fetchAll($select_sql);
        return new Range($result[0]['min_year'], $result[0]['max_year']);
    }

    /**
     * Return the text of the page title
     *
     * @param string|null $title
     * @return string
     */
    public static function pageTitle(?string $title = null): string
    {
        if (isset($title)) {
            $titleParts[] = strip_formatting($title);
        }
        $titleParts[] = option('site_title');
        return implode(' &middot; ', $titleParts);
    }

    /**
     * Is the current page Advanced Search?
     *
     * @param string|null $url
     * @return bool|null
     */
    public static function isAdvancedSearch(string $url = null): ?bool
    {
        $url = $url ?? current_url();
        return $url === '/items/search';
    }

    /**
     * Are we on the homepage?
     *
     * @return bool true if current page is homepage
     */
    public static function isHomePage(): bool
    {
        return is_current_url('') || is_current_url('index.php');
    }
}
