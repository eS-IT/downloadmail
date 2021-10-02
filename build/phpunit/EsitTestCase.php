<?php

declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     CC-BY-SA-4.0
 * @package     Downloadmail
 * @filesource  EsitTestCase.php
 * @version     2.0.0
 * @since       01.10.2021 - 22:24
 */

namespace Esit\Downloadmail;

use Contao\TestCase\ContaoTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class EsitTestCase
 *
 * @internal
 */
class EsitTestCase extends ContaoTestCase
{


    /**
     * File with data.
     * @var string
     */
    protected $strDataProviderFile = '';


    /**
     * DataProvider
     * @var null
     */
    protected $varDataProvider;


    /**
     * EsitTestCase constructor.
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->initializeContao();
    }


    /**
     * setup the environment
     */
    protected function setUp(): void
    {
    }


    /**
     * tear down the environment
     */
    protected function tearDown(): void
    {
    }


    /**
     * Initialisiert Contao
     * @param  string     $tlMode
     * @param  string     $tlScript
     * @throws \Exception
     */
    protected function initializeContao($tlMode = 'TEST', $tlScript = 'EsitTestCase'): void
    {
        $framework = $this->mockContaoFramework();
        $framework->method('initialize');

        if (!\defined('TL_MODE')) {
            \define('TL_MODE', $tlMode);
            \define('TL_SCRIPT', $tlScript);
            $initializePath = CONTAO_ROOT . "/system/initialize.php";

            if (is_file($initializePath)) {
                require $initializePath;
                stream_wrapper_restore('phar'); // reregister stream wrapper for phpunit.phar
            } else {
                throw new \Exception(CONTAO_ROOT . "/system/initialize.php not found!");
            }
        }
    }


    /**
     * Lädt eien Klasse über den DIC.
     * @param $class
     * @return mixed
     */
    protected function getClass($class)
    {
        $dc = \Contao\System::getContainer();

        return $dc->get($class);
    }


    /**
     * Universeller DataProvider
     * @param              $strMethod
     * @param  string      $ext
     * @return array|mixed
     */
    public function uniDataProvider($strMethod, $ext = 'php')
    {
        $arrMethod = explode('::', $strMethod);
        $content = [];

        if (\is_array($arrMethod) && \count($arrMethod)) {
            $strMethod = array_pop($arrMethod);
            $backtrace = debug_backtrace();
            $file = $backtrace[0]['file'];
            $parts = explode('/', $file);
            array_pop($parts);
            $path = implode('/', $parts);
            $providerFile = "$path/data/$strMethod.$ext";

            try {
                if (is_file($providerFile)) {
                    $content = include $providerFile;
                }
            } catch (\Exception $e) {
                self::logToFile($e->getMessage() . ' - ' . $e->getFile() . ' [' . $e->getLine() . ']');

                return [];
            }
        }

        return (\is_array($content) && \count($content)) ? $content : [];
    }


    /**
     * Speichert einen Wert in einer Datei.
     * @param $varValue
     * @param string $strFile
     */
    public static function logToFile($varValue, $strFile = '/tmp/phplogfile.txt'): void
    {
        if ($varValue) {
            $strContent = date('d.m.Y H:i:s') . "\n";

            if (\is_array($varValue)) {
                $strContent .= var_export($varValue, true) . "\n";
            } else {
                $strContent .= $varValue . "\n\n";
            }

            file_put_contents($strFile, $strContent, \FILE_APPEND);
        }
    }


    /**
     * Gibt einen String auf dem Bildschirm aus.
     * @param $varValue
     */
    public static function dumpValue($varValue): void
    {
        echo "\n";
        var_dump($varValue);
        echo "\n";
    }


    /**
     * Erzeugt einen Mock eines Doctrine Query Builders.
     *
     * $query  = $this->getQueryBuilderMock();
     *
     * $query->expects($this->once())
     *       ->method('select')
     *       ->with('*')
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'select', '*');
     *
     * $query->expects($this->once())
     *       ->method('from')
     *       ->with($table)
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'from', $table);
     *
     * $query->expects($this->once())
     *       ->method('where')
     *       ->with("pid = $group")
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'where', "pid = $group");
     *
     * $connect = $this->getConnectionMock($query);
     * @param  array      $return
     * @return MockObject
     */
    protected function getQueryBuilderMock(array $return = []): MockObject
    {
        $result = $this->getMockForAbstractClass(Statement::class);

        $result->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn($return);

        $result->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn($return);

        $query = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(QueryBuilder::class))
            ->getMock();

        $query->method('execute')
            ->willReturn($result);

        return $query;
    }


    /**
     * Konfiguriert den Mock einer Methode.
     *
     * $query  = $this->getQueryBuilderMock();
     *
     * $query->expects($this->once())
     *       ->method('select')
     *       ->with('*')
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'select', '*');
     *
     * $query->expects($this->once())
     *       ->method('from')
     *       ->with($table)
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'from', $table);
     *
     * $query->expects($this->once())
     *       ->method('where')
     *       ->with("pid = $group")
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'where', "pid = $group");
     *
     * $connect = $this->getConnectionMock($query);
     *
     * @param MockObject  $query
     * @param string      $method
     * @param null|string $with
     * @param null        $return
     */
    public function addMethodeMock(MockObject $query, string $method, string $with = null, $return = null): void
    {
        $return = $return ?: $query;

        if (null !== $with) {
            $query->expects($this->once())
                ->method($method)
                ->with($with)
                ->willReturn($return);
        } else {
            $query->expects($this->once())
                ->method($method)
                ->willReturn($return);
        }
    }


    /**
     * Erzeugt ein Mock einer Doctine Dantenbankverbindung.
     * Erhält den Mock eines QueryBuilders.
     *
     * $query  = $this->getQueryBuilderMock();
     *
     * $query->expects($this->once())
     *       ->method('select')
     *       ->with('*')
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'select', '*');
     *
     * $query->expects($this->once())
     *       ->method('from')
     *       ->with($table)
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'from', $table);
     *
     * $query->expects($this->once())
     *       ->method('where')
     *       ->with("pid = $group")
     *       ->willReturn($query);
     *
     * OR ==> $this->addMethodeMock($query, 'where', "pid = $group");
     *
     * $connect = $this->getConnectionMock($query);
     *
     * @param $query
     * @return MockObject
     */
    protected function getConnectionMock($query = null): MockObject
    {
        $connect = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['createQueryBuilder'])
            ->getMock();

        $connect->method('createQueryBuilder')
            ->willReturn($query);

        return $connect;
    }


    /**
     * Gibt den Namen der Klassen und der Methode, sowie die Priorität der Listener eines Events zurück.
     * @param  string $eventName
     * @return array
     */
    protected function getListenerConfig(string $eventName = null): array
    {
        $listener = [];
        $di = \Contao\System::getContainer()->get('event_dispatcher');

        if (null !== $di) {
            $listeners = $di->getListeners($eventName);

            foreach ($listeners as $item) {
                $name = \get_class($item[0]) . '::' . $item[1];
                $listener[$name] = $di->getListenerPriority($eventName, [$item[0], $item[1]]);
            }
        }

        return $listener;
    }


    /**
     * Lädt die JSON-Konfiguration der Listener für das übergebene Event.
     * Dies stellt die erwartete Konfiguration dar und kann mit dem folgenden Befehl experotiert werden:
     * console debug:event-dispatcher --format=json [$eventName] > [$eventName].json
     * @param  string         $eventName
     * @param  string         $dir
     * @return array
     * @throws \JsonException
     */
    protected function getExpectedListenerConfig(string $eventName, string $dir): array
    {
        $json = file_get_contents($dir . '/data/' . $eventName . '.json');

        return json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
    }
}
