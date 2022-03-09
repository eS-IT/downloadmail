<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @version     2.0.0
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Services\Helper;

use Esit\Downloadmail\Classes\Services\Wrapper\Config;
use Esit\Downloadmail\Classes\Services\Wrapper\Environment;
use Esit\Downloadmail\Classes\Services\Wrapper\FilesModel;
use Esit\Downloadmail\Classes\Services\Wrapper\PageModel;
use Esit\Downloadmail\Classes\Services\Wrapper\StringUtil;
use Esit\Downloadmail\Classes\Services\Wrapper\Validator;

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
     * @var FilesModel
     */
    private $filesModel;

    /**
     * @var PageModel
     */
    private $pageModel;

    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param Config      $config
     * @param Environment $environment
     * @param FilesModel  $filesModel
     * @param PageModel   $pageModel
     * @param StringUtil  $stringUtil
     * @param Validator   $validator
     */
    public function __construct(
        Config $config,
        Environment $environment,
        FilesModel $filesModel,
        PageModel $pageModel,
        StringUtil $stringUtil,
        Validator $validator
    ) {
        $this->config = $config;
        $this->environment = $environment;
        $this->filesModel = $filesModel;
        $this->pageModel = $pageModel;
        $this->stringUtil = $stringUtil;
        $this->validator = $validator;
    }

    /**
     * Gibt einen für Menschen lesbaren String mit der Größe einer Datei zurück.
     * @param  string $strPath
     * @param  int    $decimals
     * @return string
     */
    public function humanFilesize(string $strPath, int $decimals = 2): string
    {
        $strPath = (\substr_count($strPath, TL_ROOT)) ? $strPath : TL_ROOT . '/' . $strPath;
        $bytes = \filesize($strPath);
        $sz = ' KMGTP';
        $factor = (int)\floor((\strlen((string)$bytes) - 1) / 3);

        return \sprintf("%.{$decimals}f", $bytes / \pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
    }

    /**
     * Erstellt die Informationen zum Downloadfile.
     * @param $binId
     * @return array
     */
    public function genFileInfo($binId): array
    {
        $arrFile = [];
        $binId = ($this->validator->isBinaryUuid($binId)) ? $binId : $this->stringUtil->uuidToBin($binId);
        $objFile = $this->filesModel->findBfyPk($binId);

        if ($objFile) {
            $arrFile = [
                'filepath' => $objFile->path,
                'filename' => \basename($objFile->path),
                'fileext' => $objFile->extension,
                'filesize' => $this->humanFilesize($objFile->path, 0),
                'filehash' => $objFile->hash,
                'fileuuid' => $this->stringUtil->binToUuid($binId)
            ];
        }

        return $arrFile;
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
        $strAlias = $this->getAlias($intId);
        $strUrl = (string)$this->environment->get('url');
        $strUrl .= TL_PATH;
        $strUrl .= '/';
        $strUrl .= $strAlias . $GLOBALS['TL_CONFIG']['urlSuffix'];

        if ($strCode) {
            $strUrl .= '?key=' . $strCode;
        }

        return $strUrl;
    }
}
