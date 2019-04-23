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
                'title' => __("Search Terms"),
                'id' => null,
                'class' => 'advanced-search-terms'
            ]
        );
    }

    public static function advSearchFormAttributes($formActionUri, $formAttributes): string
    {
        if (!empty($formActionUri)):
            $formAttributes['action'] = $formActionUri;
        else:
            $formAttributes['action'] = url([
                'controller' => 'items',
                'action' => 'browse'
            ]);
        endif;
        $formAttributes['id'] = 'indipetae-advanced-search-form';
        $formAttributes['method'] = 'GET';
        return tag_attributes($formAttributes);
    }

    public static function advSearchInput(string $field_name): string
    {
        if (!isset(SEARCH_FIELDS[$field_name])) {
            throw new \InvalidArgumentException("$field_name is not a search field name");
        }
        $field = SEARCH_FIELDS[$field_name];

        if ($field['controlled']) {
            $input_tag = self::advSearchSelect($field, $field_name);
        } else {
            $input_tag = self::advSearchTextInput($field, $field_name);
        }

        $field_label = __($field['dc_label']);

        return <<<HTML
            <div class="advanced-search-field">
                <label class="advanced-search-field__label" for="$field_name">$field_label</label>
                $input_tag
            </div>
HTML;
    }


    /**
     * Build the list to build the field search selector
     *
     * @return array
     */
    private static function fieldSearchSelectorList(): array
    {
        $response = ['' => 'Select Below'];
        foreach (SEARCH_FIELDS as $field) {
            $response[$field['id']] = __($field['dc_label']);
        }
        return $response;
    }

    /**
     * @param string $field_name
     * @param $field_id
     * @return string
     */
    private static function advSearchTextInput(array $field, string $field_name): string
    {
        return <<<TAG
<input class="advanced-search-field__input" type="text" id="$field_name" name="{$field['id']}" />
TAG;
    }

    private static function advSearchSelect(array $field, string $field_name): string
    {
        $field_id = $field['id'];

        $values = ($field['values'] === 'from_db') ? self::getElementTextListFromDB($field_id) : $field['values'];

        $options = array_map( self::class.'::selectOption', $values);

        array_unshift($options,'<option value="" class="advanced-search-field__please_select" selected disabled hidden >Select</option>');
        $options_tags = implode("\n", $options);

        return <<<TAG
<select class="advanced-search-field__select" type="text" id="$field_name" name="$field_id">
        $options_tags
</select>
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