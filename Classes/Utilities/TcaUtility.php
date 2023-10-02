<?php
namespace ITplusX\FlexiblePages\Utilities;

use ITplusX\FlexiblePages\Page\IconSet;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class TcaUtility
{
    /**
     * Add icon for page type
     *
     * @param int $dokType
     * @param IconSet $iconSet
     */
    public static function addPageTypeIconSet(int $dokType, IconSet $iconSet)
    {
        $typeIconClasses = [];

        $keys = [
            $dokType,
            $dokType . '-hideinmenu',
            $dokType . '-root'
        ];

        $icons = array_keys($iconSet->toArray());

        for ($i = 0; $i < count($icons); $i++) {
            $typeIconClasses[$keys[$i]] = $icons[$i];
        }

        ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA']['pages'],
            [
                'ctrl' => [
                    'typeicon_classes' => $typeIconClasses
                ],
            ]
        );
    }

    /**
     * Add a page type to the select field item list.
     *
     * @param int $dokType
     * @param string $label
     * @param string $iconIdentifier
     */
    public static function addPageTypeSelectItem(
        int $dokType,
        string $label,
        string $iconIdentifier
    ) {
        list($table, $field, $groupId) = ['pages', 'doktype', 'flexible'];

        ExtensionManagementUtility::addTcaSelectItemGroup(
            $table,
            $field,
            $groupId,
            'Flexible Pages',
            'after:special'
        );

        ExtensionManagementUtility::addTcaSelectItem(
            $table,
            $field,
            [
                'label' => $label,
                'value' => $dokType,
                'icon' => $iconIdentifier,
                'group' => $groupId
            ]
        );
    }

    /**
     * Get all items of a TCA field of a specified table
     *
     * @param string $table
     * @param string $field
     * @param array $excludeItems The items to be excluded by item type (NOT by array key!). Use Keyword '--div--' for excluding dividers
     * @return array
     */
    public static function getItemsForField($table, $field, $excludeItems = [])
    {
        $items = [];
        if (isset($GLOBALS['TCA'][$table]['columns'][$field]['config']['items'])
            && is_array($GLOBALS['TCA'][$table]['columns'][$field]['config']['items'])
        ) {
            $fieldItems = $GLOBALS['TCA'][$table]['columns'][$field]['config']['items'];
            foreach ($fieldItems as $item) {
                if (!is_array($item) || in_array($item['value'], $excludeItems)) {
                    // Skip non arrays and excluded items
                    continue;
                }
                unset($item['group']);
                $items[] = $item;
            }

            // Remove last item if it is a divider
            if (end($items)['value'] === '--div--') {
                array_pop($items);
            }
        }

        return $items;
    }
}
