<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;
use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Page\IconSet;

class IconSetConfiguration extends AbstractBaseConfiguration
{
    const ICON_TYPE_DEFAULT = 'defaultIcon';
    const ICON_TYPE_HIDE_IN_MENU = 'hideInMenuIcon';
    const ICON_TYPE_ROOT_PAGE = 'rootPageIcon';

    /**
     * @var IconSet
     */
    private $iconSet;

    /**
     * Returns an IconSet instance based on the provided configuration.
     *
     * @return IconSet
     * @throws InvalidConfigurationException
     */
    public function getIconSet(): IconSet
    {
        if (!($this->iconSet instanceof IconSet)) {
            $defaultIcon = $this->getIconByType(self::ICON_TYPE_DEFAULT);

            $hideInMenuIcon = null;
            if ($this->hasIconConfigurationByType(self::ICON_TYPE_HIDE_IN_MENU)) {
                $hideInMenuIcon = $this->getIconByType(self::ICON_TYPE_HIDE_IN_MENU);
            }

            $rootPageIcon = null;
            if ($this->hasIconConfigurationByType(self::ICON_TYPE_ROOT_PAGE)) {
                $rootPageIcon = $this->getIconByType(self::ICON_TYPE_ROOT_PAGE);
            }

            $this->iconSet = new IconSet($defaultIcon, $hideInMenuIcon, $rootPageIcon);
        }

        return $this->iconSet;
    }

    /**
     * @param string $iconType
     * @return Icon
     * @throws InvalidConfigurationException
     */
    public function getIconByType(string $iconType): Icon
    {
        $iconConfiguration = $this->getIconConfigurationByType($iconType);
        // @extensionScannerIgnoreLine
        return $iconConfiguration->getIcon();
    }

    /**
     * Returns the IconConfiguration of the given icon type.
     *
     * @param string $iconType
     * @return IconConfiguration
     * @throws InvalidConfigurationException
     */
    public function getIconConfigurationByType(string $iconType): IconConfiguration
    {
        $iconConfiguration = [];

        if (isset($this->configuration[$iconType])
            && !empty($this->configuration[$iconType])
        ) {
            $iconConfiguration = $this->configuration[$iconType];
        }

        return new IconConfiguration($iconConfiguration);
    }

    /**
     * @param string $iconType
     * @return bool
     */
    public function hasIconConfigurationByType(string $iconType): bool
    {
        if (isset($this->configuration[$iconType])
            && !empty($this->configuration[$iconType])
        ) {
            return true;
        }

        return false;
    }

    public static function validate(array $configuration): ValidationResult
    {
        $validationResult = new ValidationResult();

        if (!isset($configuration[self::ICON_TYPE_DEFAULT])) {
            $validationResult->addError(
                IconSetConfiguration::ICON_TYPE_DEFAULT . ' is missing'
            );
        }

        return $validationResult;
    }
}
