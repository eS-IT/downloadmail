<?php
/**
 * @package     downloadmail
 * @filesource  OnShowDownloadEvent.php
 * @version     2.0.0
 * @since       20.10.2018 - 12:20
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class OnShowDownloadEvent
 * @package Esit\Downloadmail\Classes\Events
 */
class OnShowDownloadEvent extends Event
{

    const NAME = 'on.show.download';


    /**
     * Id des anzuzeigenden Datensatzes
     * @var int
     */
    protected $id = 0;


    /**
     * @var string
     */
    protected $table = 'tl_dm_downloads';


    /**
     * Bei true wird die Anfrage zurückgesetzt. Die RequestTime wird auf den aktuellen Zeitpunkt gesetzt,
     * so dass über den Link wieder runter geladen werden kann.
     * @var bool
     */
    protected $reset = false;


    /**
     * Daten des anzuzeigenden Datensatzes
     * @var array
     */
    protected $data = [];


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }


    /**
     * @param string $table
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }


    /**
     * @return bool
     */
    public function getReset(): bool
    {
        return $this->reset;
    }


    /**
     * @param bool $reset
     */
    public function setReset(bool $reset)
    {
        $this->reset = $reset;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

}
