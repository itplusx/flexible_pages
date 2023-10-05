<?php
defined('TYPO3') or die();

$init = function () {
    /**
     * @param string $title The title of the content element
     * @param string $identifier The content element type (identifier)
     * @param string $iconIdentifier The identifier of the icon (needs to be registered before)
     * @param array $showColumns Columns to show. Custom/new columns need to be added here and configured in $costumColumn
     * @param string $relativeToField The content element the new content element should be positioned to
     * @param string $relativePosition The position of the new content element. ('after', 'before')
     * @param array $customColumns Array of complete column configurations for new columns
     */
    $addPlugin = function(
        string $title,
        string $identifier,
        string $iconIdentifier,
        array $showColumns = [],
        string $relativeToField = '',
        string $relativePosition = '',
        array $customColumns = []
    )
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                $title,
                $identifier,
                $iconIdentifier
            ],
            $relativeToField,
            $relativePosition
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'tt_content',
            $customColumns
        );

        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$identifier] = $iconIdentifier;

        $frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';
        $coreLanguageFilePrefix = 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:';
        $langLanguageFilePrefix = 'LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:';

        $GLOBALS['TCA']['tt_content']['types'][$identifier]['showitem'] = '
            --div--;' . $coreLanguageFilePrefix . 'general,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.headers;headers,
            --div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.frames;frames,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.appearanceLinks;appearanceLinks,
            --div--;' . $coreLanguageFilePrefix . 'language,
                --palette--;;language,
            --div--;' . $coreLanguageFilePrefix . 'access,
                --palette--;;hidden,--palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
            --div--;' . $coreLanguageFilePrefix . 'categories,
            --div--;' . $langLanguageFilePrefix . 'sys_category.tabs.category,
                categories,
            --div--;' . $coreLanguageFilePrefix . 'notes,
                rowDescription,
            --div--;' . $coreLanguageFilePrefix . 'extended';

        foreach ($showColumns as $column) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                $column['type'],
                $identifier,
                $column['position']
            );

            if($column['type'] == 'pi_flexform') {
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                    '*',
                    'FILE:EXT:flexible_pages/Configuration/FlexForms/' . $identifier . '.xml',
                    $identifier
                );
            }
        }
    };

    $addPlugin(
        'LLL:EXT:flexible_pages/Resources/Private/Language/locallang_db.xlf:CType.tx_flexiblepages_pagelist.title',
        'tx_flexiblepages_pagelist',
        'tx-flexiblepages-pagelist',
        [
            [
                'type' => 'pages',
                'position' => 'after:header'
            ],
            [
                'type' => 'pi_flexform',
                'position' => 'after:pages'
            ],
            [
                'type' => 'recursive',
                'position' => 'after:pi_flexform'
            ],
        ],
        'menu_related_pages',
        'after'
    );
};

$init();
unset($init);

