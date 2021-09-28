<?php
    class Database {

        private $connection;
        
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

        public function query($query, $params = array()) {
            $qr = $this->connection->prepare($query);
            if($qr->execute($params)) {
                return $qr->fetchAll();
            } else {
                return false;
            }
        }

        public function insert($query, array $data) {        
            $this->connection->prepare($query)->execute($data);     
            return $this->connection->lastInsertId();
        }
    
        public function update($query, array $data) {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            return $stmt->rowCount();       
        }
    
        public function delete($query, array $data) {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            return $stmt->rowCount();       
        }
        
    }
