<?php
/**
 * @package     downloadmail
 * @filesource  OnShowDownloadListener.php
 * @version     1.0.0
 * @since       20.10.18 - 12:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\Downloadmail\Classes\Listener;

use Contao\Database;
use Esit\Downloadmail\Classes\Events\OnShowDownloadEvent;
use Esit\Downloadmail\Classes\Helper\StringHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnShowDownloadListener
 * @package Esit\Downloadmail\Classes\Listener
 */
class OnShowDownloadListener
{


    protected $stringHelper;


    /**
     * @var Database
     */
    protected $db;


    /**
     * OnManageDownloadListener constructor.
     * @param Database $db
     */
    public function __construct(StringHelper $stringHelper, Database $db = null)
    {
        $this->stringHelper = $stringHelper;
        $this->db           = $db ?: Database::getInstance();
    }


    /**
     * Die RequestTime wird auf den aktuellen Zeitpunkt gesetzt,
     * so dass über den Link wieder runter geladen werden kann.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function resetDownload(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $id     = $event->getId();
        $table  = $event->getTable();
        $reset  = $event->getReset();

        if ($reset) {
            $query = "UPDATE $table set requesttime = " . time() . " WHERE id = $id";
            $this->db->execute($query);
        }
    }


    /**
     * Lädt die Daten des Downloads für die Anzeige.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadData(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $id     = $event->getId();
        $table  = $event->getTable();
        $query  = "SELECT * FROM $table WHERE id = $id";
        $result = $this->db->execute($query);

        if ($result->numRows) {
            $event->setData($result->fetchAssoc());
        }
    }


    /**
     * Erstellt die Infos zur Download-Datei.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertSingleSrc(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data               = $event->getData();
        $data['fileData']   = $this->stringHelper->genFileInfo($data['singleSRC']);

        $event->setData($data);
    }


    /**
     * Lädt die Daten der Download-Seite.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertJumpTo(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data   = $event->getData();
        $query  = "SELECT * FROM tl_page WHERE id = " . $data['jumpto'];
        $resutl = $this->db->execute($query);

        if ($resutl->numRows) {
            $data['jumpto'] = $resutl->fetchAssoc();
        }

        $event->setData($data);
    }


    /**
     * Lädt die Daten des Formulars.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertFromId(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data   = $event->getData();
        $query  = "SELECT * FROM tl_form WHERE id = " . $data['formid'];
        $resutl = $this->db->execute($query);

        if ($resutl->numRows) {
            $data['formid'] = $resutl->fetchAssoc();
        }

        $event->setData($data);
    }


    /**
     * Konvertiert die Daten des abesendeten Formulars aus dem Hook.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertFormData(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data               = $event->getData();
        $data['formdata']   = unserialize($data['formdata']);

        $event->setData($data);
    }


    /**
     * Konvertiert die Daten der Downloads.
     * @param OnShowDownloadEvent      $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertDownloadData(OnShowDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data                   = $event->getData();
        $data['downloaddata']   = deserialize($data['downloaddata'], true);

        $event->setData($data);
    }
}
