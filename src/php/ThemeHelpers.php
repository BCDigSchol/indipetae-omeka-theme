<?php

namespace BCLib\Indipetae;

use function JmesPath\search;

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

    public static function advSearchInput(string $field_name): string
    {
        $fields = MetadataMap::getMap();

        /**
         * @var SearchField $field
         */
        $field = $fields->getField($field_name);

        if ($field->is_controlled) {
            $input_tag = self::advSearchSelect($field, $field_name);
        } elseif ($field->is_range) {
            $input_tag = self::advSearchRange($field, $field_name);
        } else {
            $input_tag = self::advSearchTextInput($field, $field_name);
        }

        $field_label = __($field->dublin_core_label);

        return <<<HTML
<template id="adv-search__{$field_name}_template" style="display: none">
    <div class="advanced-search-field adv-search-field--{$field_name} row">
        <div class="col-md-2 advanced-search-field__label-row">
            <label class="advanced-search-field__label" for="$field_name">$field_label</label>
        </div>
        <div class="col-md-10">
             $input_tag
             <button class="advanced-search-field__delete-button"  type="button" data-field="$field_name">Remove $field_label</button>
        </div>
    </div>
</template>
HTML;
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
<input class="advanced-search-field__input" type="text" id="$field_name" name="$field_name" />
TAG;
    }

    private static function advSearchSelect(SearchField $field, string $field_name): string
    {
        $field_id = $field->field_id;

        $values = $field->load_from_db ? self::getElementTextListFromDB($field_id) : $field->values;

        $options = array_map(self::class . '::selectOption', $values);

        array_unshift($options,
            '<option value="" class="advanced-search-field__please_select" selected disabled hidden >Select</option>');
        $options_tags = implode("\n", $options);

        return <<<TAG
<select class="advanced-search-field__select" type="text" id="$field_name" name="$field_name">
        $options_tags
</select>
TAG;
    }

    private static function advSearchRange(array $field, string $field_name): string
    {
        $min_field_name = "{$field_name}_min";
        $max_field_name = "{$field_name}_max";

        return <<<TAG
        <div class="advanced-search-field__range-inputs">
<label for="$inn_field_name" class="advanced-search-field__range-label" data-point="min" data-field="$field_name">Minimum</label>
<input class="advanced-search-field__input--range" type="text" id="$min_field_name" name="$min_field_name" />
<label for="$max_field_name" class="advanced-search-field__range-label" data-point="max" data-field="$field_name">Maximum</label>
<input class="advanced-search-field__input--range" type="text" id="$max_field_name" name="$max_field_name" />
</div>
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
}