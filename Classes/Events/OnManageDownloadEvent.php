<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:54
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Events;

use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Symfony\Contracts\EventDispatcher\Event;

class OnManageDownloadEvent extends Event
{


    /**
     * Spachvariablen
     * @var array
     */
    protected $feFormLang = [];


    /**
     * Schlüssel für den angeforderten Downlaod
     * @var string
     */
    protected $downloadKey = '';


    /**
     * Id des aufrufenden Moduls
     * @var string
     */
    protected $modulId = '';


    /**
     * Daten des Moduls
     * @var ModuleModel
     */
    protected $modul;


    /**
     * Die Daten des Downloads aus der Datenbank
     * @var array
     */
    protected $dlFromDb = [];


    /**
     * FrontendTemplate für die Ausfabe
     * @var FrontendTemplate
     */
    protected $tamplate;


    /**
     * Daten der angeforderten Datei.
     * @var FilesModel
     */
    protected $fileData;


    /**
     * Datan des Formulars.
     * @var array
     */
    protected $formData = [];


    /**
     * Zeit die ein Download gültig ist.
     * @var string
     */
    protected $downloadtime = '';


    /**
     * Link um den Download erneut anzufordern
     * @var string
     */
    protected $requestLink = '';


    /**
     * @var string
     */
    protected $requestTime = '';


    /**
     * Ist die aktuelle Zeit innerhalb der zulässigen Downloadzeit?
     * @var bool
     */
    protected $isDownloadTimeOkay = false;


    /**
     * @return string
     */
    public function getDownloadKey(): string
    {
        return $this->downloadKey;
    }


    /**
     * @param string $downloadKey
     */
    public function setDownloadKey(string $downloadKey): void
    {
        $this->downloadKey = $downloadKey;
    }


    /**
     * @return string
     */
    public function getModulId(): string
    {
        return $this->modulId;
    }


    /**
     * @param string $modulId
     */
    public function setModulId(string $modulId): void
    {
        $this->modulId = $modulId;
    }


    /**
     * @return ModuleModel
     */
    public function getModul(): ModuleModel
    {
        return $this->modul;
    }


    /**
     * @param ModuleModel $modul
     */
    public function setModul(ModuleModel $modul): void
    {
        $this->modul = $modul;
    }


    /**
     * @return array
     */
    public function getFeFormLang(): array
    {
        return $this->feFormLang;
    }


    /**
     * @param array $feFormLang
     */
    public function setFeFormLang(array $feFormLang): void
    {
        $this->feFormLang = $feFormLang;
    }


    /**
     * @return array
     */
    public function getDlFromDb(): array
    {
        return $this->dlFromDb;
    }


    /**
     * @param array $dlFromDb
     */
    public function setDlFromDb(array $dlFromDb): void
    {
        $this->dlFromDb = $dlFromDb;
    }


    /**
     * @return FilesModel
     */
    public function getFileData(): FilesModel
    {
        return $this->fileData;
    }


    /**
     * @param FilesModel $fileData
     */
    public function setFileData(FilesModel $fileData): void
    {
        $this->fileData = $fileData;
    }


    /**
     * @return array
     */
    public function getFormData(): array
    {
        return $this->formData;
    }


    /**
     * @param array $formData
     */
    public function setFormData(array $formData): void
    {
        $this->formData = $formData;
    }


    /**
     * @return FrontendTemplate
     */
    public function getTamplate(): FrontendTemplate
    {
        return $this->tamplate;
    }


    /**
     * @param FrontendTemplate $tamplate
     */
    public function setTamplate(FrontendTemplate $tamplate): void
    {
        $this->tamplate = $tamplate;
    }


    /**
     * @return string
     */
    public function getDownloadtime(): string
    {
        return $this->downloadtime;
    }


    /**
     * @param string $downloadtime
     */
    public function setDownloadtime(string $downloadtime): void
    {
        $this->downloadtime = $downloadtime;
    }


    /**
     * @return string
     */
    public function getRequestLink(): string
    {
        return $this->requestLink;
    }


    /**
     * @param string $requestLink
     */
    public function setRequestLink(string $requestLink): void
    {
        $this->requestLink = $requestLink;
    }


    /**
     * @return bool
     */
    public function isDownloadTimeOkay(): bool
    {
        return $this->isDownloadTimeOkay;
    }


    /**
     * @param bool $isDownloadTimeOkay
     */
    public function setIsDownloadTimeOkay(bool $isDownloadTimeOkay): void
    {
        $this->isDownloadTimeOkay = $isDownloadTimeOkay;
    }


    /**
     * @return string
     */
    public function getRequestTime(): string
    {
        return $this->requestTime;
    }


    /**
     * @param string $requestTime
     */
    public function setRequestTime(string $requestTime): void
    {
        $this->requestTime = $requestTime;
    }
}
