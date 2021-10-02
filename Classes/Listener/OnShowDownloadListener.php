<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  OnShowDownloadListener.php
 * @version     1.0.0
 * @since       20.10.18 - 12:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Listener;

use Esit\Downloadmail\Classes\Events\OnShowDownloadEvent;
use Esit\Downloadmail\Classes\Services\Helper\StringHelper;
use Esit\Downloadmail\Classes\Services\Wrapper\Database;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnShowDownloadListener
 * @package Esit\Downloadmail\Classes\Listener
 */
class OnShowDownloadListener
{


    /**
     * @var StringHelper
     */
    protected $stringHelper;


    /**
     * @var Database
     */
    protected $db;


    /**
     * @param StringHelper $stringHelper
     * @param Database     $db
     */
    public function __construct(StringHelper $stringHelper, Database $db)
    {
        $this->stringHelper = $stringHelper;
        $this->db = $db;
    }


    /**
     * Die RequestTime wird auf den aktuellen Zeitpunkt gesetzt,
     * sodass über den Link wieder heruntergeladen werden kann.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function resetDownload(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $id = $event->getId();
        $table = $event->getTable();
        $reset = $event->getReset();

        if (true === $reset) {
            $query = "UPDATE $table set requesttime = " . time() . " WHERE id = $id";
            $this->db->execute($query);
        }
    }


    /**
     * Lädt die Daten des Downloads für die Anzeige.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadData(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $id = $event->getId();
        $table = $event->getTable();
        $query = "SELECT * FROM $table WHERE id = $id";
        $result = $this->db->execute($query);

        if ($result->numRows > 0) {
            $event->setData($result->fetchAssoc());
        }
    }


    /**
     * Erstellt die Infos zur Download-Datei.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertSingleSrc(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $data = $event->getData();
        $data['fileData'] = $this->stringHelper->genFileInfo($data['singleSRC']);

        $event->setData($data);
    }


    /**
     * Lädt die Daten der Download-Seite.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertJumpTo(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $data = $event->getData();
        $query = "SELECT * FROM tl_page WHERE id = " . $data['jumpto'];
        $resutl = $this->db->execute($query);

        if ($resutl->numRows > 0) {
            $data['jumpto'] = $resutl->fetchAssoc();
        }

        $event->setData($data);
    }


    /**
     * Lädt die Daten des Formulars.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertFromId(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $data = $event->getData();
        $query = "SELECT * FROM tl_form WHERE id = " . $data['formid'];
        $resutl = $this->db->execute($query);

        if ($resutl->numRows > 0) {
            $data['formid'] = $resutl->fetchAssoc();
        }

        $event->setData($data);
    }


    /**
     * Konvertiert die Daten des abesendeten Formulars aus dem Hook.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertFormData(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $data = $event->getData();
        $data['formdata'] = unserialize($data['formdata'], [null]);

        $event->setData($data);
    }


    /**
     * Konvertiert die Daten der Downloads.
     * @param OnShowDownloadEvent      $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function convertDownloadData(
        OnShowDownloadEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $data = $event->getData();
        $data['downloaddata'] = unserialize($data['downloaddata'], [null]);

        $event->setData($data);
    }
}
