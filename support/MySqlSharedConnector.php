<?php
/*
 * Created on Thu Dec 19 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support;

use PDO;
use Exception;
use Illuminate\Database\Connectors\MySqlConnector;

class MySqlSharedConnector extends MySqlConnector
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        /**
         * Take OpenCart default connection in order to avoid double connections
         */
        $connection = null;

        if (class_exists('\OpenCore\Framework')) {
            try {
                $connection = \OpenCore\Framework::getInstance()->getRegistry('db')->getConnection();
            } catch (Exception $e) {
                throw new Exception('Bad instance shared instance for MysqlSharedConnector:' . $e->getMessage());
            }
        }

        if (!$connection) {
            // We need to grab the PDO options that should be used while making the brand
            // new connection instance. The PDO options control various aspects of the
            // connection's behavior, and some might be specified by the developers.
            $dsn = $this->getDsn($config);

            $options = $this->getOptions($config);

            $connection = $this->createConnection($dsn, $config, $options);

            if (!empty($config['database'])) {
                $connection->exec("use `{$config['database']}`;");
            }

            $this->configureEncoding($connection, $config);

            // Next, we will check to see if a timezone has been specified in this config
            // and if it has we will issue a statement to modify the timezone with the
            // database. Setting this DB timezone is an optional configuration item.
            $this->configureTimezone($connection, $config);

            $this->setModes($connection, $config);
        }

        return $connection;
    }
}
