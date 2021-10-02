<?php
    class Database {

        private $connection;
        
        /** 
        * Create a database connection
        * @param string $host - database server
        * @param string $username - database username
        * @param string $password - database password
        * @param string $dbname - database name
        * @return null
        */
        public function openConnection($host, $username, $password, $dbname) {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, array(PDO::ATTR_PERSISTENT => true));
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
        }

        public function getConnection() {
            return $this->connection;
        }
    
        public function closeConnection() {
            $this->connection = null;
        }

        /**
        * Perform SELECT query
        * @param string $query - SQL query
        * @param Array $params - parameter supplied to SELECT query
        * @return array - return query output
        */
        public function query($query, $params = array()) {
            $qr = $this->connection->prepare($query);
            if($qr->execute($params)) {
                return $qr->fetchAll();
            } else {
                return false;
            }
        }

        /**
        * Insert query
        * @param String $query - SQL query
        * @param Array $data - Parameter for $query
        * @return Array - return results for query
        */
        public function insert($query, array $data) {        
            $this->connection->prepare($query)->execute($data);     
            return $this->connection->lastInsertId();
        }
    
        /**
        * Update query
        * @param String $query - SQL query
        * @param Array $data - Parameter for $query
        * @return Array - return rowcount
        */
        public function update($query, array $data) {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            return $stmt->rowCount();       
        }
    
        /**
        * Delete query
        * @param String $query - SQL query
        * @param Array $data - Parameter for $query
        * @return Array - return rowcount
        */
        public function delete($query, array $data) {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            return $stmt->rowCount();       
        }        
    }
