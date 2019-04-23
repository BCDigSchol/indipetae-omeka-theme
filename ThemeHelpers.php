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
}