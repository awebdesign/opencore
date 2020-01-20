<?php
/*
 * Created on Thu Dec 19 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace DB;

use PDO;
use PDOException;
use Exception;

final class corePDO
{
    private $connection = null;
    private $statement = null;

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        //PDO::ATTR_PERSISTENT => false,
        //PDO::MYSQL_ATTR_SSL_CA => false
    ];

    private $charset = 'utf8mb4'; //original was: utf8
    private $collation = 'utf8mb4_unicode_ci';//original was: utf8_general_ci

    public function __construct($hostname, $username, $password, $database, $port = '3306')
    {
        try {
            $this->connection = new PDO($this->getDsn($hostname, $database, $port), $username, $password, $this->options);
        } catch (PDOException $e) {
            throw new Exception('Failed to connect to database. Reason: \'' . $e->getMessage() . '\'');
        }

        $this->configureEncoding();


        // Next, we will check to see if a timezone has been specified in this config
        // and if it has we will issue a statement to modify the timezone with the
        // database. Setting this DB timezone is an optional configuration item.
        $this->configureTimezone();

        if (defined('DB_MODES')) {
            $this->setCustomModes();
        } elseif (defined('DB_STRICT')) {
            $this->connection->prepare($this->strictMode())->execute();
        } else {
            $this->connection->prepare("set session sql_mode='NO_ENGINE_SUBSTITUTION'")->execute();
        }
    }

    /**
     * Create a DSN string from a configuration.
     *
     * Chooses socket or host/port based on the 'unix_socket' config value.
     *
     * @param  array   $config
     * @return string
     */
    protected function getDsn($hostname, $database, $port)
    {
        return $this->hasSocket()
                            ? $this->getSocketDsn($database)
                            : $this->getHostDsn($hostname, $database, $port);
    }

    /**
     * Determine if the given configuration array has a UNIX socket value.
     *
     * @param  array  $config
     * @return bool
     */
    protected function hasSocket()
    {
        return defined('DB_UNIX_SOCKET') && ! empty(DB_UNIX_SOCKET);
    }

    /**
     * Get the DSN string for a socket configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getSocketDsn($database)
    {
        return "mysql:unix_socket=" . DB_UNIX_SOCKET . ";dbname={$database}";
    }

    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getHostDsn($hostname, $database, $port)
    {
        return isset($port)
                    ? "mysql:host={$hostname};port={$port};dbname={$database}"
                    : "mysql:host={$hostname};dbname={$database}";
    }

    /**
     * Set the connection character set and collation.
     *
     * @return void
     */
    protected function configureEncoding()
    {
        $this->charset = defined('DB_CHARSET') ? DB_CHARSET : $this->charset;
        $this->collation = defined('DB_COLLATION') ? DB_COLLATION : $this->collation;

        $this->connection->prepare("set names '{$this->charset}' collate '{$this->collation}'")->execute();
    }

    /**
     * Set the timezone on the connection.
     *
     * @return void
     */
    protected function configureTimezone()
    {
        if (defined('DB_TIMEZONE')) {
            $this->connection->prepare('set time_zone="' . DB_TIMEZONE . '"')->execute();
        }
    }

    /**
     * Get the query to enable strict mode.
     *
     * @return string
     */
    protected function strictMode()
    {
        if (version_compare($this->connection->getAttribute(PDO::ATTR_SERVER_VERSION), '8.0.11') >= 0) {
            return "set session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'";
        }

        return "set session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'";
    }

    /**
     * Set the custom modes on the connection.
     *
     * @param  array  $config
     * @return void
     */
    protected function setCustomModes()
    {
        $this->connection->prepare("set session sql_mode='" . DB_MODES . "'")->execute();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function prepare($sql)
    {
        $this->statement = $this->connection->prepare($sql);
    }

    public function bindParam($parameter, $variable, $data_type = \PDO::PARAM_STR, $length = 0)
    {
        if ($length) {
            $this->statement->bindParam($parameter, $variable, $data_type, $length);
        } else {
            $this->statement->bindParam($parameter, $variable, $data_type);
        }
    }

    public function execute()
    {
        try {
            if ($this->statement && $this->statement->execute()) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0])) ? $data[0] : array();
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    public function query($sql, $params = array())
    {
        $this->statement = $this->connection->prepare($sql);

        $result = false;

        try {
            if ($this->statement && $this->statement->execute($params)) {
                $data = array();

                while ($row = $this->statement->fetch(PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0]) ? $data[0] : array());
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
        }

        if ($result) {
            return $result;
        } else {
            $result = new \stdClass();
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    public function escape($value)
    {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
    }

    public function countAffected()
    {
        if ($this->statement) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }

    public function isConnected()
    {
        if ($this->connection) {
            return true;
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }
}
