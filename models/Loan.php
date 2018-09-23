<?php
    class Loan {
        // DB Stuff
        private $conn;
        private $table = 'loans';
            // Foreign table
            private $usersTable = 'users';
            private $equipmentTable = 'equipment';

        // Loan Properties
        public $id;
        public $startDate;
        public $endDate;
        public $project;
        public $state;
        public $idU;
            // User Properties
            public $userName;
            public $userSurname;
            public $userEmail;
            public $userDepartment;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Loans
        public function read() {
            // Create query

            $query = 'SELECT
                        l.id,
                        l.startDate,
                        l.endDate,
                        l.project,
                        l.state,
                        u.id as idU,
                        u.name,
                        u.surname,
                        u.email,
                        u.department
                    FROM
                        ' . $this->table . ' AS l,
                        ' . $this->usersTable . ' AS u
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Get Single Loan
        public function read_single() {
            // Create query
            $query = 'SELECT
                        l.id,
                        l.startDate,
                        l.endDate,
                        l.project,
                        l.state,
                        u.id as idU,
                        u.name,
                        u.surname,
                        u.email,
                        u.department
                    FROM
                        ' . $this->table . ' AS l,
                        ' . $this->usersTable . ' AS u
                    WHERE
                        l.id = ?
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind param
            $stmt->bindParam(1, $this->id);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Get User's Loan(s)
        public function read_user() {
            // Create query
            $query = 'SELECT
                        l.id,
                        l.startDate,
                        l.endDate,
                        l.project,
                        l.state,
                        u.id as idU,
                        u.name,
                        u.surname,
                        u.email,
                        u.department
                    FROM
                        ' . $this->table . ' AS l,
                        ' . $this->usersTable . ' AS u
                    WHERE
                        u.id = ?
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind param
            $stmt->bindParam(1, $this->idU);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Create Loan
        public function create() {
            // Create query
            $query = 'INSERT INTO
                        ' . $this->table . '
                    SET
                        startDate = :startDate,
                        endDate = :endDate,
                        project = :project,
                        state = :state,
                        idU = :idU
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->startDate = htmlspecialchars(strip_tags($this->startDate));
            $this->endDate = htmlspecialchars(strip_tags($this->endDate));
            $this->project = htmlspecialchars(strip_tags($this->project));
            $this->state = htmlspecialchars(strip_tags($this->state));
            $this->idU = htmlspecialchars(strip_tags($this->idU));

            // Bind params
            $stmt->bindParam(':startDate', $this->startDate);
            $stmt->bindParam(':endDate', $this->endDate);
            $stmt->bindParam(':project', $this->project);
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':idU', $this->idU);

            // Execute statement
            if ($stmt->execute()) {
                // Get last inserted id
                $lastId = $this->conn->lastInsertId();
                return $lastId;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Update Loan
        public function update() {
            // Create query
            $query = 'UPDATE
                        ' . $this->table . '
                    SET
                        state = :state
                    WHERE
                        id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->state = htmlspecialchars(strip_tags($this->state));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind params
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':id', $this->id);

            // Execute statement
            if ($stmt->execute()) {
                return $stmt;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Delete Loans
        public function delete() {
            // Create query
            $query = 'DELETE FROM
                        ' . $this->table . '
                    WHERE
                        id = ?
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind param
            $stmt->bindParam(1, $this->id);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

    }