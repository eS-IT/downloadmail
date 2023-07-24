<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Services\Helper;

use Esit\Downloadmail\Classes\Services\Wrapper\Config;
use Esit\Downloadmail\Classes\Services\Wrapper\Environment;
use Esit\Downloadmail\Classes\Services\Wrapper\PageModel;

class StringHelper
{


    /**
     * @var Config
     */
    private $config;


    /**
     * @var Environment
     */
    private $environment;


    /**
     * @var PageModel
     */
    private $pageModel;


    /**
     * @param Config      $config
     * @param Environment $environment
     * @param PageModel   $pageModel
     */
    public function __construct(
        Config $config,
        Environment $environment,
        PageModel $pageModel
    ) {
        $this->config = $config;
        $this->environment = $environment;
        $this->pageModel = $pageModel;
    }


    /**
     * Erzeugt einen Alias.
     * @param $intJumpTo
     * @return string
     */
    public function getAlias($intJumpTo)
    {
        $disable = (bool)$this->config->get('disableAlias');

        if ($intJumpTo > 0) {
            // Weiterleitungsseite aus dem Formular
            $objPage = $this->pageModel->findByPk($intJumpTo);

            if (null !== $objPage) {
                return ('' !== $objPage->alias && false === $disable) ? (string)$objPage->alias : (string)$objPage->id;
            }
        } else {
            // Weiterleitungsseite aus der Root-Page
            global $objPage;

            $objRoot = $this->pageModel->findByPk($objPage->rootId);

            if (null !== $objRoot && !empty($objRoot->downloadtime)) {
                $objPage = $this->pageModel->findByPk($objRoot->jumptodownload);

                if (null !== $objPage) {
                    return ('' !== $objPage->alias && !$disable) ? (string)$objPage->alias : (string)$objPage->id;
                }
            }
        }

        // Weiterleitungsseite aus den Einstellungen
        if (null !== $this->config->get('jumptodownload')) {
            $objPage = $this->pageModel->findByPk($this->config->get('jumptodownload'));

            if (null !== $objPage) {
                return ('' !== $objPage->alias && !$disable) ? (string)$objPage->alias : (string)$objPage->id;
            }
        }

        return '';
    }


    /**
     * Erzeugt den Link zu einer uebergebenen Id.
     * @param  int    $intId
     * @param  string $strCode
     * @return string
     */
    public function genLink(int $intId, string $strCode = ''): string
    {
        $strAlias   = $this->getAlias($intId);
        $strUrl     = (string)$this->environment->get('url');
        $strUrl    .= TL_PATH;
        $strUrl    .= '/';
        $suffix     = '.html';

        if (true === $this->config->get('setdownladsuffix')) {
            $suffix = (string)$this->config->get('downloadsuffix');
        }

        $strUrl .= $strAlias . $suffix;

        if ($strCode) {
            $strUrl .= '?key=' . $strCode;
        }

        return $strUrl;
    }
}
