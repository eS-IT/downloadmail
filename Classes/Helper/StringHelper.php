<?php
/**
 * @package     downloadmail
 * @filesource  StringHelper.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Helper;

use Contao\Config;
use Contao\Environment;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Validator;

/**
 * Class StringHelper
 * @package Esit\Downloadmail\Classes\Helper
 */
class StringHelper
{


    /**
     * @param     $strPath
     * @param int $decimals
     * @return string
     */
    public static function humanFilesize($strPath, $decimals = 2)
    {
        $strPath= (substr_count($strPath, TL_ROOT)) ? $strPath : TL_ROOT . '/' . $strPath;
        $bytes  = filesize($strPath);
        $sz     = ' KMGTP';
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
    }


    /**
     * Erstellt die Infos zum Downloadfile.
     * @param $binId
     * @return array|null
     */
    public function genFileInfo($binId)
    {
        $arrFile    = null;
        $binId      = (Validator::isBinaryUuid($binId)) ? $binId : StringUtil::uuidToBin($binId);
        $objFile    = FilesModel::findByPk($binId);

        if ($objFile) {
            $arrFile = array(
                'filepath'  => $objFile->path,
                'filename'  => basename($objFile->path),
                'fileext'   => $objFile->extension,
                'filesize'  => $this->humanFilesize($objFile->path, 0),
                'filehash'  => $objFile->hash,
                'fileuuid'  => StringUtil::binToUuid($binId)
            );
        }

        return $arrFile;
    }


    /**
     * Erzeugt einen Alias.
     * @param $intJumpTo
     * @return mixed|null
     */
    public function getAlias($intJumpTo)
    {
        $disable = Config::get('disableAlias');

        if ($intJumpTo > 0) {
            // Weiterleitungsseite aus dem Form
            $objPage = PageModel::findByPk($intJumpTo);

            if ($objPage) {
                return ($objPage->alias != '' && !$disable) ? $objPage->alias : $objPage->id;
            }
        } else {
            // Weiterleitungsseite aus der Root-Page
            global $objPage;

            $objRoot = PageModel::findByPk($objPage->rootId);

            if ($objRoot && $objRoot->downloadtime) {
                $objPage = PageModel::findByPk($objRoot->jumptodownload);

                if ($objPage) {
                    return ($objPage->alias != '' && !$disable) ? $objPage->alias : $objPage->id;
                }
            }
        }

        // Weiterleitungsseite aus den Einstellungen
        if (Config::get('jumptodownload')) {
            $objPage = PageModel::findByPk(Config::get('jumptodownload'));

            if ($objPage) {
                return ($objPage->alias != '' && !$disable) ? $objPage->alias : $objPage->id;
            }
        }

        return '';
    }


    /**
     * Erzeugt den Link zu einer uebergebenen Id.
     * @param        $intId
     * @param string $strCode
     * @return mixed|string
     */
    public function genLink($intId, $strCode = '')
    {
        $strAlias   = $this->getAlias($intId);
        $strUrl     = Environment::get('url');
        $strUrl    .= TL_PATH;
        $strUrl    .= '/';
        $strUrl    .= $strAlias . $GLOBALS['TL_CONFIG']['urlSuffix'];

        if ($strCode) {
            $strUrl .= '?key=' . $strCode;
        }

        return $strUrl;
    }
}
