<?php
/**
 * MySQL Database operations for AttendanceList
 *
 * Opens a db connection using mysqli and retrieves the needed information.
 * This serves as a simple example and uses the database-schema given in the
 * example-folder. The functions return an array containing the 'name' row,
 * despite the SELECT * statement.
 *
 * PHP version 5
 *
 * @package AttendanceList
 * @author  Peter Hense <peter.hense@gmail.com>
 * @version 0.1
 */

require 'DbConfig.php';

class MySqlDB {
    private $db;
    private $sql1 = "SELECT * FROM `members` WHERE `position_id` = 1";
    private $sql2 = "SELECT * FROM `members` WHERE `position_id` = 2";
    private $sql3 = "SELECT * FROM `members` WHERE `position_id` = 3";
    private $sql4 = "SELECT * FROM `members` WHERE `position_id` = 4";

    function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") "
            . $this->db->connect_error;
        }
    }

    /**
     * sends a query to the database
     *
     * Simply puts the given SQL statement and returns and 'array' with the
     * name elements. Since we don't have any user-interaction, the query
     * omitts prepared statements.
     *
     * @param  string $statement SQL Statement
     * @return array 'name' elements of the resulting query
     */
    private function query_statement($statement) {
        if(!$result = $this->db->query($statement)) {
            die('There was an error running the query [' .
                $this->db->error . ']');
        }

        while($row = $result->fetch_assoc()){
            $reval[] = $row['name'];
        }
        $result->free();
        return $reval;
    }

    function __destruct() {
        $this->db->close();
    }

    public function query_statement_1() {
        $result = $this->query_statement($this->sql1);
        return $result;
    }

    public function query_statement_2() {
        $result = $this->query_statement($this->sql2);
        return $result;
    }

    public function query_statement_3() {
        $result = $this->query_statement($this->sql3);
        return $result;
    }

    public function query_statement_4() {
        $result = $this->query_statement($this->sql4);
        return $result;
    }
} // class MySqlDB
?>