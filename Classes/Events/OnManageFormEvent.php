<?php
/**
 * @package     downloadmail
 * @filesource  OnManageFormEvent.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class OnManageFormEvent
 * @package Esit\Downloadmail\Classes\Events
 */
class OnManageFormEvent extends Event
{


    /**
     * Name des Events
     */
    const NAME = 'on.manage.form';


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
    public function setFormData(array $formData)
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
    public function setPostData(array $postData)
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
    public function setFilesData(array $filesData)
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
    public function setDownloadFileInfo(array $downloadFileInfo)
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
    public function setSettingFields(array $settingFields)
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
    public function setSettings(array $settings)
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
    public function setDbData(array $dbData)
    {
        $this->dbData = $dbData;
    }
}
