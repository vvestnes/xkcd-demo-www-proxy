<?php
/**
 * Database Wrapper class
 * 
 */

class Db {
    
    var $mysqli;
    
    /**
     *  Open connection to DB
     */
    public function connect() {
        $mysqli = mysqli_connect("127.0.0.1", "xkcduser1", "xkcdSuperSecret", "xkcd");
        $mysqli->set_charset('utf8mb4');
        // check connection 
        if ($mysqli->connect_error) {
            die("Connect failed: ". $mysqli->connect_error);
        }
        $this->mysqli = $mysqli;
    }
    
    /**
     * Execute a SELECT query
     * 
     * @param type $sql
     * @return type array 
     */
    public function select_query($sql) {

        $db = $this->mysqli;
        
        $rows = [];
        
        //Select queries return a resultset
        $result = $db->query($sql);
        if($result){
             // Cycle through results
            while ($row = $result->fetch_array()){
                $rows[] = $row;
            }
             // Free result set
             $result->close();
        }
        else {
            die($db->error);        
        }
        return $rows;
    }
    
    /**
     * 
     * Execute the query and returns the result.
     * 
     * @param type $sql string The query to execute
     * @return type mixed The result, or false on failure.
     */
    public function query($sql) {
        return mysqli_query($this->mysqli, $sql);
    }
    
    /**
     * Get the sql error from the last sql operation.
     * 
     * @return type string 
     */
    public function sqlstate() {
        return $this->mysqli->sqlstate;
    }
    
    /**
     * Safely escape strings
     * 
     * @param type $str string String to be escaped
     * @return type string Returns the escaped string.
     */
    public function escape($str) {
        return $this->mysqli->real_escape_string($str);
    }    

    /**
     * Close the database connection.
     */
    public function close() {
        if($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
