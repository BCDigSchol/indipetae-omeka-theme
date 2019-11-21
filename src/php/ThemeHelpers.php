<?php

namespace BCLib\Indipetae;

require_once __DIR__ . '/SearchFields.php';

class ThemeHelpers
{
    public static function joinerSelect(\Omeka_View $view, int $i, array $rows): string
    {
        $attributes = [
            'title' => __('Search Joiner'),
            'id' => null,
            'class' => 'advanced-search-joiner'
        ];
        $options = [
            'and' => __('AND'),
            'or' => __('OR'),
        ];
        return $view->formSelect("advanced[$i][joiner]", @$rows['joiner'], $attributes, $options);
    }

    /**
     * Build the field selector drop-down
     *
     * @param \Omeka_View $view the view to add the field to, i.e. $this
     * @param int $i the number of the dropdown in sequence
     * @return string the SELECT element
     */
    public static function fieldSelect(\Omeka_View $view, int $i, array $rows): string
    {
        $attributes = [
            'title' => __('Search Field'),
            'id' => null,
            'class' => 'advanced-search-element'
        ];
        $options = self::fieldSearchSelectorList();
        return $view->formSelect("advanced[$i][element_id]", @$rows['element_id'], $attributes, $options);
    }

    public static function searchTypeSelectBox(\Omeka_View $view, int $i, array $rows): string
    {
        $attributes = [
            'title' => __("Search Type"),
            'id' => null,
            'class' => 'advanced-search-type'
        ];
        $options = label_table_options([
                'contains' => __('contains'),
                'does not contain' => __('does not contain'),
                'is exactly' => __('is exactly'),
                'is empty' => __('is empty'),
                'is not empty' => __('is not empty'),
                'starts with' => __('starts with'),
                'ends with' => __('ends with')
            ]
        );
        return $view->formSelect(
            "advanced[$i][type]",
            @$rows['type'],
            $attributes,
            $options
        );
    }

    public static function searchTextInput(\Omeka_View $view, int $i, array $rows): string
    {
        return $view->formText(
            "advanced[$i][terms]",
            @$rows['terms'],
            [
                'size' => '20',
                'title' => __('Search Terms'),
                'id' => null,
                'class' => 'advanced-search-terms'
            ]
        );
    }

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

        // The "archive" field is a special case, because it is derived from a Collection name instead of an
        // ElementText.
        if ($field_name === FIELD_ARCHIVE) {
            $field_name = 'archive';
            $input_tag = self::advSearchSelect($field, $field_name);
            return self::advancedSearchInputTemplate($field_name, 'Archive', $input_tag);
        }

        if ($field->is_controlled) {
            $input_tag = self::advSearchSelect($field, $field_name);
        } elseif ($field->is_range) {
            $input_tag = self::advSearchRange($field, $field_name);
        } else {
            $input_tag = self::advSearchTextInput($field, $field_name);
        }

        $field_label = __($field->dublin_core_label);

        return self::advancedSearchInputTemplate($field_name, $field_label, $input_tag);
    }

    /**
     * @param array $wanted_elements
     * @param array $dublin_core_metadata
     * @return MetadataField[]
     */
    public static function elementsToDisplay(array $wanted_elements, array $dublin_core_metadata)
    {
        $display_elements = [];
        $metadata_fields = \BCLib\Indipetae\MetadataMap::getMap();
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
     * Build the list to build the field search selector
     *
     * @return array
     */
    private static function fieldSearchSelectorList(): array
    {
        $response = ['' => 'Select Below'];
        foreach (METADATA_FIELDS as $field) {
            $response[$field['id']] = __($field['dc_label']);
        }
        return $response;
    }

    private static function advSearchTextInput(SearchField $field, string $field_name): string
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
        if ($field_name === FIELD_ARCHIVE) {
            $values = ['New Society (1814-1939)'];
        } else {
            $field_id = $field->field_id;
            $values = $field->load_from_db ? self::getElementTextListFromDB($field_id) : $field->values;
        }
        return self::selectBox($field_name, $values);
    }


    /**
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

    private static function advSearchRange($field, string $field_name): string
    {
        $min_field_name = "{$field_name}_min";
        $max_field_name = "{$field_name}_max";

        return <<<TAG
        <div class="advanced-search-field__range-inputs">
<label for="$min_field_name" class="advanced-search-field__range-label" data-point="min" data-field="$field_name">Min.</label>
<input class="advanced-search-field__input--range .advanced-search-field__input" type="text" id="$min_field_name" name="$min_field_name" />
<label for="$max_field_name" class="advanced-search-field__range-label" data-point="max" data-field="$field_name">Max.</label>
<input class="advanced-search-field__input--range .advanced-search-field__input" type="text" id="$max_field_name" name="$max_field_name" />
</div>
TAG;
    }

    private static function advSearchDateRange($field, string $field_name): string
    {
        $min_field_name = "{$field_name}_min";
        $max_field_name = "{$field_name}_max";

        return <<<TAG
       <input class="advanced-search-field__input advanced-search-field__date-range-input" type="text" id="$field_name" name="{$field_name}[][or]" />
TAG;
    }

    /**
     * @param $field_id
     * @return array|false|\Omeka_Record_AbstractRecord
     */
    private static function getElementTextListFromDB($field_id)
    {
        $db = get_db();
        $element_texts = $db->getTable('ElementText');
        $sql = 'element_texts.element_id = ?';
        $values = $element_texts->findBySql($sql, [$field_id]);
        $values = array_map(function ($value) {
            return trim($value->text);
        }, $values);
        sort($values);
        $values = array_unique($values);
        return $values;
    }

    private static function selectOption($value)
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

    public static function getMinMaxYears(): \stdClass
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

        $response = new \stdClass();
        $response->min = $result[0]['min_year'];
        $response->max = $result[0]['max_year'];

        return $response;
    }

    public static function advancedSearchNumberRange()
    {
        $min_field_name = 'number_min';
        $max_field_name = 'number_max';

        $numbers = array_map(static function ($number) {
            return (int)preg_replace('/^.+ +/', '', $number);
        }, self::getElementTextListFromDB(68));

        $min_select_box = self::selectBox($min_field_name, $numbers);
        $max_select_box = self::selectBox($max_field_name, $numbers);

        return <<<TAG
        <div class="advanced-search-field__range-inputs">
<label for="$min_field_name" class="advanced-search-field__range-label" data-point="min" data-field="$field_name">Minimum</label>
$min_select_box
<label for="$max_field_name" class="advanced-search-field__range-label" data-point="max" data-field="$field_name">Maximum</label>
$max_select_box
</div>
TAG;
    }
}
