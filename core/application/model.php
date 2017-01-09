<?php
namespace Khonsa\Application;

/**
 * Class Model - Base class for models used in the Khonsa framework.
 * 
 * All child classes of Model will share the same database connection. Hence,
 * it is important that the the constructor of this class be called if a child
 * class contains it's own constructor.
**/

class Model
{
    private static $db_connection = null;
    protected $db;
    
    /**
     * Creates connection to the MySQL Database Server. Connection information
     * used is defined in the database.php file in the config folder. All child
     * clases will share the same database connection.
    **/
    function __construct()
    {
        if (is_null(self::$db_connection))
            $this->connect_to_db();
            
        $this->db = self::$db_connection;
    }
    
    /**
     * Establishes a PDO connection to the database. A PDOException is
     * thrown if an error occurs.
    **/
    private function connect_to_db()
    {
        try
        {
            self::$db_connection = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (\PDOEXception $e)
        {
            throw $e;
        }
    }
}