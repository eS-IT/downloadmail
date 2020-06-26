<?php declare(strict_types = 1);
/**
 * @package     smartgallery
 * @filesource  ContentSmartgalleryMenu.php
 * @since       25.06.2020 - 17:51
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Smartgallery\Classes\Contao\Elements;

use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\Finder\Finder;

/**
 * Class ContentSmartgalleryMenu
 * @package Smartgallery\Classes\Contao\Elements
 */
class ContentSmartgalleryMenu extends \ContentElement
{


    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_smartgallery_menu';


    /**
     * Generate the content element
     */
    protected function compile(): void
    {
        if ('BE' === TL_MODE) {
            $this->genBeOutput();
        } else {
            $this->genFeOutput();
        }
    }


    /**
     * Erzeugt die Ausgabe für das Backend.
     */
    protected function genBeOutput(): void
    {
        $this->strTemplate          = 'be_wildcard';
        $this->Template             = new \BackendTemplate($this->strTemplate);
        $this->Template->title      = $this->headline;
        $this->Template->wildcard   = '### ContentSmartgalleryMenu ###';
    }


    /**
     * Erzeugt die Ausgabe für das Frontend.
     */
    protected function genFeOutput(): void
    {
        $startFolder    = StringUtil::binToUuid($this->startfolder);
        $start          = FilesModel::findByUuid($startFolder);

        if (null !== $start) {
            $folderList = [];
            $finder     = new Finder();
            $finder->directories()->in(TL_ROOT . '/' . $start->path)->depth(0)->sortByName();

            foreach ($finder as $folder) {
                $uuid     = FilesModel::findByPath($folder->getRealPath());
                $filename = $folder->getFilename();
                $parts    = \explode('_', $filename);
                if (\is_array($parts) && \count($parts)) {
                    $filename = \array_pop($parts);
                }

                if (!empty($filename) && null !== $uuid && !empty($uuid->uuid)) {
                    $folderList[$filename] = StringUtil::binToUuid($uuid->uuid);
                }
            }

            if (!empty($this->jumpto)) {
                $this->Template->linkToList = PageModel::findById($this->jumpto)->alias . '.html';
            } else {
                global $objPage;
                $this->Template->linkToList = $objPage->alias . '.html';
            }

            $this->Template->folderList = $folderList;
        }
    }
}
