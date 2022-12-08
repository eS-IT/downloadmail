<?php

/**
 * @package     downloadmail
 * @since       20.10.18 - 12:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Listener;

use Doctrine\DBAL\Connection;
use Esit\Downloadmail\Classes\Events\OnShowDownloadEvent;
use Esit\Downloadmail\Classes\Services\Helper\FileHelper;

class OnShowDownloadListener
{
    /**
     * @var FileHelper
     */
    protected $fileHelper;

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param FileHelper   $fileHelper
     * @param Connection   $db
     */
    public function __construct(FileHelper $fileHelper, Connection $db)
    {
        $this->fileHelper = $fileHelper;
        $this->db = $db;
    }


    /**
     * Die RequestTime wird auf den aktuellen Zeitpunkt gesetzt,
     * sodass über den Link wieder heruntergeladen werden kann.
     * @param OnShowDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function resetDownload(OnShowDownloadEvent $event): void
    {
        $id = $event->getId();
        $table = $event->getTable();
        $reset = $event->getReset();

        if (true === $reset) {
            $query = $this->db->createQueryBuilder();
            $query->update($table)->set('requesttime', '?')->setParameters([\time()])->where("id = $id")->execute();
        }
    }


    /**
     * Lädt die Daten des Downloads für die Anzeige.
     * @param OnShowDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadData(OnShowDownloadEvent $event): void
    {
        $id     = $event->getId();
        $table  = $event->getTable();
        $query  = $this->db->createQueryBuilder();
        $result = $query->select('*')->from($table)->where("id = $id")->execute();
        $data   = $result->fetchAssociative();

        if (false !== $data) {
            $event->setData($data);
        }
    }


    /**
     * Erstellt die Infos zur Download-Datei.
     * @param OnShowDownloadEvent $event
     * @return void
     */
    public function convertSingleSrc(OnShowDownloadEvent $event): void
    {
        $data               = $event->getData();
        $data['fileData']   = $this->fileHelper->genFileInfo($data['singleSRC']);

        $event->setData($data);
    }


    /**
     * Lädt die Daten der Download-Seite.
     * @param OnShowDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function convertJumpTo(OnShowDownloadEvent $event): void
    {
        $data   = $event->getData();
        $query  = $this->db->createQueryBuilder();

        if (!empty($data['jumpto'])) {
            $result = $query->select('*')->from('tl_page')->where('id = ' . $data['jumpto'])->execute();
            $dbData = $result->fetchAssociative();

            if (false !== $dbData) {
                $data['jumpto'] = $dbData;
            }
        }

        $event->setData($data);
    }


    /**
     * Lädt die Daten des Formulars.
     * @param OnShowDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function convertFromId(OnShowDownloadEvent $event): void
    {
        $data   = $event->getData();
        $query  = $this->db->createQueryBuilder();
        $result = $query->select('*')->from('tl_form')->where('id = ' . $data['formid'])->execute();
        $dbData = $result->fetchAssociative();

        if (false !== $dbData) {
            $data['formid'] = $dbData;
        }

        $event->setData($data);
    }


    /**
     * Konvertiert die Daten des abesendeten Formulars aus dem Hook.
     * @param OnShowDownloadEvent $event
     * @return void
     */
    public function convertFormData(OnShowDownloadEvent $event): void
    {
        /*$data = $event->getData();
        $data['formdata'] = \unserialize($data['formdata'], [null]);

        $event->setData($data);*/
    }


    /**
     * Konvertiert die Daten der Downloads.
     * @param OnShowDownloadEvent $event
     * @return void
     */
    public function convertDownloadData(OnShowDownloadEvent $event): void
    {
        $data = $event->getData();
        $data['downloaddata'] = \unserialize($data['downloaddata'], [null]);

        $event->setData($data);
    }
}
