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

namespace Esit\Downloadmail\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

class OnManageFormEvent extends Event
{


    /**
     * Array mit den Datan des Formulars aus tl_form
     * @var array
     */
    protected $formData = [];


    /**
     * Array mit den Post-Daten
     * @var array
     */
    protected $postData = [];


    /**
     * Array mit den hochgeladenen Dateien
     * @var array
     */
    protected $filesData = [];


    /**
     * Array mit den Infos zur Downloaddatei
     * @var array
     */
    protected $downloadFileInfo = [];


    /**
     * Felder, die es in den Einstellungen, der Rootpage und dem Formular gibt
     * @var array
     */
    protected $settingFields = [
        'downloadtime',
        'mailfrom',
        'mailbcc',
        'mailsubject',
        'mailtext',
        'jumptodownload',
        'redirecttime'
    ];


    /**
     * Array mit den zusammengesetzten Daten aus Einstellungen, Rootpage und dem Formular.
     * @var array
     */
    protected $settings = [];


    /**
     * Datan, die in tl_dm_downloads gespeichert werden sollen.
     * @var array
     */
    protected $dbData = [];


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
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }


    /**
     * @param array $postData
     */
    public function setPostData(array $postData): void
    {
        $this->postData = $postData;
    }


    /**
     * @return array
     */
    public function getFilesData(): array
    {
        return $this->filesData;
    }


    /**
     * @param array $filesData
     */
    public function setFilesData(array $filesData): void
    {
        $this->filesData = $filesData;
    }


    /**
     * @return array
     */
    public function getDownloadFileInfo(): array
    {
        return $this->downloadFileInfo;
    }


    /**
     * @param array $downloadFileInfo
     */
    public function setDownloadFileInfo(array $downloadFileInfo): void
    {
        $this->downloadFileInfo = $downloadFileInfo;
    }


    /**
     * @return array
     */
    public function getSettingFields(): array
    {
        return $this->settingFields;
    }


    /**
     * @param array $settingFields
     */
    public function setSettingFields(array $settingFields): void
    {
        $this->settingFields = $settingFields;
    }


    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }


    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }


    /**
     * @return array
     */
    public function getDbData(): array
    {
        return $this->dbData;
    }


    /**
     * @param array $dbData
     */
    public function setDbData(array $dbData): void
    {
        $this->dbData = $dbData;
    }
}
