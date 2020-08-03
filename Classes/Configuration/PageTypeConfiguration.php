<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;
use ITplusX\FlexiblePages\Page\IconSet;
use ITplusX\FlexiblePages\Page\PageType;

class PageTypeConfiguration extends AbstractBaseConfiguration
{
    /**
     * @var PageType
     */
    private $pageType;

    /**
     * @return PageType
     * @throws InvalidConfigurationException
     */
    public function getPageType(): PageType
    {
        if (!($this->pageType instanceof PageType)) {
            $this->pageType = new PageType(
                $this->getDokType(),
                $this->getLabel(),
                $this->getIconSet(),
                $this->getIsDraggableInNewPageDragArea()
            );
        }

        return $this->pageType;
    }

    /**
     * @return int
     */
    protected function getDokType(): int
    {
        return (int)$this->configuration['dokType'];
    }

    /**
     * @return string
     */
    protected function getLabel(): string
    {
        if (isset($this->configuration['label']) && trim($this->configuration['label']) !== '') {
            return $this->configuration['label'];
        }

        return 'INFO: No label for dokType ' . $this->getDokType();
    }

    /**
     * @return IconSet
     * @throws InvalidConfigurationException
     */
    protected function getIconSet(): IconSet
    {
        $iconSetConfiguration = new IconSetConfiguration($this->configuration['iconSet']);
        return $iconSetConfiguration->getIconSet();
    }

    /**
     * @return bool
     */
    protected function getIsDraggableInNewPageDragArea(): bool
    {
        if (isset($this->configuration['isDraggableInNewPageDragArea'])) {
            return $this->configuration['isDraggableInNewPageDragArea'] === true;
        }

        return false;
    }

    public static function validate(array $configuration): ValidationResult
    {
        $validationResult = new ValidationResult();

        if (!isset($configuration['dokType'])) {
            $message = 'Mandatory field dokType is missing.';
            $validationResult->addError($message);
            return $validationResult;
        }

        $messagePrefix = 'Invalid configuration for dokType ' . $configuration['dokType'] . ' ';

        if (isset($configuration['isDraggableInNewPageDragArea'])) {
            if (!is_bool($configuration['isDraggableInNewPageDragArea'])) {
                $message = 'The key isDraggableInNewPageDragArea must be of type bool';
                $validationResult->addError($messagePrefix . $message);
            }
        }

        return $validationResult;
    }
}
