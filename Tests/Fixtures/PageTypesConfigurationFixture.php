<?php
namespace ITplusX\FlexiblePages\Tests\Fixtures;

use ITplusX\FlexiblePages\Configuration\IconSetConfiguration;

class PageTypesConfigurationFixture
{
    public static function getPageTypesConfiguration(): array
    {
        return [
            111 => [
                'label' => 'News',
                'iconSet' => [
                    IconSetConfiguration::ICON_TYPE_DEFAULT => [
                        // Example of an Icon definition without identifier
                        'source' => 'EXT:flexible_pages/Tests/Fixtures/Icon-111.svg',
                    ],
                    IconSetConfiguration::ICON_TYPE_HIDE_IN_MENU => [
                        // Example of an Icon definition with existing identifier
                        'identifier' => 'apps-pagetree-page-content-from-page-hideinmenu',
                    ],
                    IconSetConfiguration::ICON_TYPE_ROOT_PAGE => [
                        // Example of an Icon definition with custom identifier
                        'identifier' => 'custom-icon-identifier',
                        'source' => 'EXT:flexible_pages/Tests/Fixtures/Icon-111.png',
                    ],
                ],
                'isDraggableInNewPageDragArea' => true
            ],
            222 => [
                // Empty label
                'label' => '',
                'iconSet' => [
                    IconSetConfiguration::ICON_TYPE_DEFAULT => [
                        // Example of an Icon definition without identifier and a invalid path
                        'source' => 'EXT:flexible_pages/invalid/path/to/file.svg',
                    ],
                    IconSetConfiguration::ICON_TYPE_HIDE_IN_MENU => [
                        // Example of an Icon definition with existing identifier
                        'identifier' => 'apps-pagetree-page-content-from-page-hideinmenu',
                    ],
                    IconSetConfiguration::ICON_TYPE_ROOT_PAGE => [
                        // Example of an Icon definition with custom identifier
                        'identifier' => 'another-custom-icon-identifier',
                        'source' => 'EXT:flexible_pages/Tests/Fixtures/Icon-222.png',
                    ],
                ],
                'isDraggableInNewPageDragArea' => false
            ],
//            333 => [] // dokType is defined, but configuration is missing
        ];
    }

    public static function getIconConfigurationByDokTypeAndIconType(int $dokType, string $iconType)
    {
        return self::getPageTypesConfiguration()[$dokType]['iconSet'][$iconType];
    }
}
