<?php
declare(strict_types = 1);

namespace ITplusX\FlexiblePages\Page;

class IconSet
{
    /**
     * @var Icon
     */
    private $defaultIcon;

    /**
     * @var Icon
     */
    private $hideInMenuIcon;

    /**
     * @var Icon
     */
    private $rootPageIcon;

    /**
     * IconSet constructor.
     * @param Icon $defaultIcon
     * @param Icon $hideInMenuIcon
     * @param Icon $rootPageIcon
     */
    public function __construct(Icon $defaultIcon, Icon $hideInMenuIcon = null, Icon $rootPageIcon = null)
    {
        $this->defaultIcon = $defaultIcon;
        $this->hideInMenuIcon = $hideInMenuIcon;
        $this->rootPageIcon = $rootPageIcon;
    }

    /**
     * @return Icon
     */
    public function getDefaultIcon(): Icon
    {
        return $this->defaultIcon;
    }

    /**
     * @param Icon $defaultIcon
     */
    public function setDefaultIcon(Icon $defaultIcon)
    {
        $this->defaultIcon = $defaultIcon;
    }

    /**
     * @return bool
     */
    public function hasDefaultIcon(): bool
    {
        return $this->defaultIcon instanceof Icon;
    }

    /**
     * @return Icon
     */
    public function getHideInMenuIcon(): Icon
    {
        return $this->hideInMenuIcon;
    }

    /**
     * @param Icon $hideInMenuIcon
     */
    public function setHideInMenuIcon(Icon $hideInMenuIcon)
    {
        $this->hideInMenuIcon = $hideInMenuIcon;
    }

    /**
     * @return bool
     */
    public function hasHideInMenuIcon(): bool
    {
        return $this->hideInMenuIcon instanceof Icon;
    }

    /**
     * @return Icon
     */
    public function getRootPageIcon(): Icon
    {
        return $this->rootPageIcon;
    }

    /**
     * @param Icon $rootPageIcon
     */
    public function setRootPageIcon(Icon $rootPageIcon)
    {
        $this->rootPageIcon = $rootPageIcon;
    }

    /**
     * @return bool
     */
    public function hasRootPageIcon(): bool
    {
        return $this->rootPageIcon instanceof Icon;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $icons = array_merge([],$this->getDefaultIcon()->toArray());

        if ($this->hasHideInMenuIcon()) {
            $icons = array_merge($icons,$this->getHideInMenuIcon()->toArray());
        }

        if ($this->hasRootPageIcon()) {
            $icons = array_merge($icons,$this->getRootPageIcon()->toArray());
        }

        return $icons;
    }
}
