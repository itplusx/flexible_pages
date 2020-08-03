<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Page;

class PageType
{
    /**
     * @var int
     */
    private $dokType;

    /**
     * @var string
     */
    private $label;

    /**
     * @var IconSet
     */
    private $iconSet;

    /**
     * @var bool
     */
    private $isDraggableInNewPageDragArea;

    /**
     * PageType constructor.
     * @param int $dokType
     * @param string $label
     * @param IconSet $iconSet
     * @param bool $isDraggableInNewPageDragArea
     */
    public function __construct(int $dokType, string $label, IconSet $iconSet, bool $isDraggableInNewPageDragArea = true)
    {
        $this->dokType = $dokType;
        $this->label = $label;
        $this->iconSet = $iconSet;
        $this->isDraggableInNewPageDragArea = $isDraggableInNewPageDragArea;
    }

    /**
     * @return int
     */
    public function getDokType(): int
    {
        return $this->dokType;
    }

    /**
     * @param int $dokType
     */
    public function setDokType(int $dokType)
    {
        $this->dokType = $dokType;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return IconSet
     */
    public function getIconSet(): IconSet
    {
        return $this->iconSet;
    }

    /**
     * @param IconSet $iconSet
     */
    public function setIconSet(IconSet $iconSet)
    {
        $this->iconSet = $iconSet;
    }

    /**
     * @return bool
     */
    public function isDraggableInNewPageDragArea(): bool
    {
        return $this->isDraggableInNewPageDragArea;
    }

    /**
     * @param bool $isDraggableInNewPageDragArea
     */
    public function setIsDraggableInNewPageDragArea(bool $isDraggableInNewPageDragArea)
    {
        $this->isDraggableInNewPageDragArea = $isDraggableInNewPageDragArea;
    }
}
