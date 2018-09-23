<?php
    class Contain {
        // DB Stuff
        private $conn;
        private $table = 'contain';
            // Foreign table
            private $equipmentTable = 'equipment';
            private $loansTable = 'loans';

        // Contain Properties
        public $idE;
        public $idL;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Loan's equipment
        public function read_equipment() {
            // Create query
            $query = 'SELECT
                        e.id,
                        e.name
                    FROM
                        ' . $this->table . ' AS c,
                        ' . $this->loansTable . ' AS l,
                        ' . $this->equipmentTable . ' AS e
                    WHERE
                        c.idL = ?
                        AND l.id = c.idL
                        AND c.idE = e.id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind param
            $stmt->bindParam(1, $this->idL);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Insert Loan's Equipment
        public function insert_equipment() {
            // Create query
            $query = 'INSERT INTO
                        ' . $this->table . '
                    SET
                        idE = :idE,
                        idL = :idL
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind params
            $stmt->bindParam(':idE', $this->idE);
            $stmt->bindParam(':idL', $this->idL);

            // Execute statement
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Remove Loan's Equipment
        public function remove_equipment() {
            // Create query
            $query = 'DELETE FROM
                        contain
                    WHERE
                        idE = :idE
                    AND
                        idL = :idL
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind params
            $stmt->bindParam(':idE', $this->idE);
            $stmt->bindParam(':idL', $this->idL);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }
    }