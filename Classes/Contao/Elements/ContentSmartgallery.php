<?php declare(strict_types = 1);
/**
 * @package     smartgallery
 * @filesource  ContentSmartgallery.php
 * @since       25.06.2020 - 17:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Smartgallery\Classes\Contao\Elements;

use Contao\Config;
use Contao\ContentGallery;
use Contao\FilesModel;
use Contao\Input;
use Symfony\Component\Finder\Finder;

/**
 * Class ContentSmartgallery
 * @package Smartgallery\Classes\Contao\Elements
 */
class ContentSmartgallery extends \ContentElement
{


    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_smartgallery';


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
        $this->Template->wildcard   = '### ContentSmartgallery ###';
    }


    /**
     * Erzeugt die Ausgabe für das Frontend.
     */
    protected function genFeOutput(): void
    {
        $this->Template->content    = 'Bitte wählen Sie eine Galerie aus.';
        $uuid                       = Input::get('gallery');

        if (null !== $uuid) {
            $folder = FilesModel::findByUuid($uuid);

            if (null !== $folder) {
                $filename                 = $folder->name;
                $parts                    = \explode('_', $filename);
                $this->objModel->type     = 'gallery';
                $this->objModel->multiSRC = $this->getImages($folder);
                $cte                      = new ContentGallery($this->objModel);
                $this->Template->headline = $filename; // Fallback

                if (\is_array($parts) && \count($parts)) {
                    $this->Template->headline = \array_pop($parts);
                }

                if (!empty($this->objModel->multiSRC)) {
                    $this->Template->content = $cte->generate();
                }
            }
        }
    }


    /**
     * Gibt das Array mit den Bildern zurück.
     * @param  FilesModel $folder
     * @return array
     */
    protected function getImages(FilesModel $folder): array
    {
        $multiSRC   = [];

        if (null !== $folder) {
            $finder = new Finder();
            $finder->files()->in(TL_ROOT . '/' . $folder->path)->depth(0)->sortByName();

            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $fileModel  = FilesModel::findByPath($file->getRealPath());

                    if (null !== $fileModel && \substr_count(Config::get('validImageTypes'), $fileModel->extension)) {
                        $multiSRC[] = $fileModel->uuid;
                    }
                }
            }
        }

        return $multiSRC;
    }
}
