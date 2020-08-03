<?php

namespace ITPlusX\FlexiblePages\Hooks;

use ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility;
use ITplusX\FlexiblePages\Utilities\TcaUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Userfunc to render alternative label for media elements
 */
class ItemsProcFunc
{
    /**
     * @var ExtensionConfigurationUtility $extensionConfigurationUtility
     */
    protected $extensionConfigurationUtility;

    public function __construct()
    {
        $this->extensionConfigurationUtility = GeneralUtility::makeInstance(ExtensionConfigurationUtility::class);
    }

    /**
     * Itemsproc function to extend the selection of doktypes.
     * TODO: Make excludes configurable
     *
     * @param array &$config configuration array
     */
    public function user_doktypes(array &$config)
    {
        $doktypes = TcaUtility::getItemsForField(
            'pages',
            'doktype',
            [
                (string)PageRepository::DOKTYPE_BE_USER_SECTION,
                (string)PageRepository::DOKTYPE_SPACER,
                (string)PageRepository::DOKTYPE_SYSFOLDER,
                (string)PageRepository::DOKTYPE_RECYCLER,
                '--div--'
            ]
        );

        if (isset($doktypes) && is_array($doktypes)) {
            foreach ($doktypes as $doktype) {
                array_push($config['items'], $doktype);
            }
        }
    }

    /**
     * Itemsproc function to extend the selection of templates
     *
     * @param array $config
     */
    public function user_template(array &$config)
    {
        $cType = $config['flexParentDatabaseRow']['CType'];
        $pageId = $this->getPageId($config['flexParentDatabaseRow']['pid']);

        if ($pageId > 0) {
            $extensionConfiguration = $this->extensionConfigurationUtility->getMergedExtensionConfiguration(
                $pageId,
                ExtensionConfigurationUtility::EXTKEY
            );

            if (isset($extensionConfiguration[$cType]['templates'])
                && is_array($extensionConfiguration[$cType]['templates'])
            ) {
                foreach ($extensionConfiguration[$cType]['templates'] as $templateKey => $title) {
                    $templateKey = (is_numeric($templateKey) ? GeneralUtility::underscoredToLowerCamelCase($title) : $templateKey);

                    array_push(
                        $config['items'],
                        [
                            htmlspecialchars($this->getLanguageService()->sL($title)),
                            $templateKey
                        ]
                    );
                }
            }
        }
    }

    /**
     * Itemsproc function to extend the selection of orderBy
     *
     * @param array $config
     */
    public function user_orderBy(array &$config)
    {
        $cType = $config['flexParentDatabaseRow']['CType'];
        $pageId = $this->getPageId($config['flexParentDatabaseRow']['pid']);

        if ($pageId > 0) {
            $extensionConfiguration = $this->extensionConfigurationUtility->getMergedExtensionConfiguration(
                $pageId,
                ExtensionConfigurationUtility::EXTKEY
            );

            if (isset($extensionConfiguration[$cType]['orderBy'])
                && is_array($extensionConfiguration[$cType]['orderBy'])
            ) {
                foreach ($extensionConfiguration[$cType]['orderBy'] as $templateKey => $title) {
                    array_push(
                        $config['items'],
                        [
                            htmlspecialchars($this->getLanguageService()->sL($title)) . ' ⬆',
                            $templateKey . ' ASC'
                        ],
                        [
                            htmlspecialchars($this->getLanguageService()->sL($title)) . ' ⬇',
                            $templateKey . ' DESC'
                        ]
                    );
                }
            }
        }
    }

    /**
     * Get page id, if negative, then it is a "after record"
     *
     * @param int $pid
     * @return int
     */
    protected function getPageId($pid)
    {
        $pid = (int)$pid;
        if ($pid > 0) {
            return $pid;
        }
        $row = BackendUtility::getRecord('tt_content', abs($pid), 'uid,pid');
        return $row['pid'];
    }

    /**
     * Returns LanguageService
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
