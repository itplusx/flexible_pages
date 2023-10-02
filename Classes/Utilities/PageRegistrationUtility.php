<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Utilities;

use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageRegistrationUtility
{
    /**
     * Registers a new page type with PageDoktypeRegistry.
     *
     * @param int $dokType
     * @param bool $overwriteExisting
     * @param string $allowedTables
     */
    public static function registerDokTypeInPagesTypes(
        int $dokType,
        bool $overwriteExisting = false,
        string $allowedTables = '*'
    ) {
        if (!$overwriteExisting && self::isRegistered($dokType)) {
            return;
        }
        $dokTypeRegistry = self::getDoktypeRegistry();
        $dokTypeRegistry->add(
            $dokType,
            [
                'type' => 'web',
                'allowedTables' => $allowedTables,
            ]
        );
    }

    /**
     * Returns true if the $dokType is registered already.
     *
     * @param $dokType
     * @return bool
     */
    public static function isRegistered($dokType): bool
    {
        return in_array($dokType, self::getDoktypeRegistry()->getRegisteredDoktypes());
    }

    protected static function getDoktypeRegistry()
    {
        return GeneralUtility::makeInstance(PageDoktypeRegistry::class);
    }
}
