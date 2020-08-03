<?php

namespace ITplusX\FlexiblePages\ViewHelpers\Be;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * This VH is a modified version of TYPO3\CMS\Fluid\ViewHelpers\Be\PageInfoViewHelper!
 *
 * View helper which return page info icon as known from TYPO3 backend modules
 * Note: This view helper is experimental!
 *
 * = Examples =
 *
 * <code>
 * <f:be.pageInfo />
 * </code>
 * <output>
 * Page info icon with context menu
 * </output>
 *
 * <code>
 * <f:be.pageInfo pageUid="2" />
 * </code>
 * <output>
 * Page info icon of page with uid 2 with context menu
 * </output>
 *
 * <code>
 * <f:be.pageInfo pageUid="2" showTitle="true" />
 * </code>
 * <output>
 * Page info icon of page with uid 2 with context menu
 * </output>
 */
class PageInfosViewHelper extends AbstractBackendViewHelper
{

    /**
     * This view helper renders HTML, thus output must not be escaped
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(
            'pageUids',
            'string',
            'Comma-separated list of the target page uids'
        );
        $this->registerArgument(
            'showTitle',
            'bool',
            'Show the page title instead of the UID'
        );
        $this->registerArgument(
            'linkTitle',
            'bool',
            'Link the page title'
        );
        $this->registerArgument(
            'linkModule',
            'string',
            'The module to link to',
            false,
            'web_layout'
        );
        $this->registerArgument(
            'infoDelimiter',
            'string',
            'A delimiter between page infos when selected more than one page uid',
            false,
            ', '
        );
    }

    /**
     * Render javascript in header
     *
     * @return string the rendered page info icon
     * @see \TYPO3\CMS\Backend\Template\DocumentTemplate::getPageInfo() Note: can't call this method as it's protected!
     */
    public function render()
    {
        return static::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ){
        $pageUids = $arguments['pageUids'];
        $showTitle = $arguments['showTitle'];
        $linkTitle = $arguments['linkTitle'];
        $linkModule = $arguments['linkModule'];
        $infoDelimiter = $arguments['infoDelimiter'];

        $ids = (!is_null($pageUids) ? GeneralUtility::trimExplode(',', $arguments['pageUids'], true) : [0 => GeneralUtility::_GP('id')]);
        $icons = '';
        if (isset($ids) && is_array($ids)) {
            $icon = '';
            foreach ($ids as $id) {
                $pageRecord = BackendUtility::readPageAccess($id, $GLOBALS['BE_USER']->getPagePermsClause(1));
                // Add icon with context menu, etc:
                /** @var IconFactory $iconFactory */
                $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
                if ($pageRecord['uid']) {
                    // If there IS a real page
                    $altText = BackendUtility::getRecordIconAltText($pageRecord, 'pages');
                    $icon = '<span title="' . $altText . '">' . $iconFactory->getIconForRecord(
                        'pages',
                        $pageRecord,
                        Icon::SIZE_SMALL
                        )->render() . '</span>';
                    // Make Icon:
                    $icon = BackendUtility::wrapClickMenuOnIcon($icon, 'pages', $pageRecord['uid']);

                    // Setting icon with context menu + uid
                    if ($showTitle) {
                        if ($linkTitle) {
                            $pageRecord['title'] = '<a href="' . BackendUtility::getModuleUrl(
                                $linkModule,
                                ['id' => $id]
                                ) . '">' . $pageRecord['title'] . '</a>';
                        }
                        $icon .= ' <em>' . $pageRecord['title'] . '</em>';
                    } else {
                        $icon .= ' <em>[UID: ' . $pageRecord['uid'] . ']</em>';
                    }
                }

                if ($id != end($ids)) {
                    $icon .= $infoDelimiter;
                }

                $icons .= $icon;
            }
        }
        return $icons;
    }
}
