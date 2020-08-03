<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Utilities;

class PageRegistrationUtility
{
    /**
     * Registers a new page type in $PAGES_TYPES.
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

        $GLOBALS['PAGES_TYPES'][$dokType] = [
            'allowedTables' => $allowedTables,
        ];
    }

    /**
     * Returns true if the $dokType is registered already.
     *
     * @param $dokType
     * @return bool
     */
    public static function isRegistered($dokType): bool
    {
        return isset($GLOBALS['PAGES_TYPES'][$dokType]);
    }
}
