<?php
    class Equipment {
        // DB stuff
        private $conn;
        private $table = 'equipment';

        // Equipment Properties
        public $id;
        public $name;
        public $description;
        public $category;
        public $image;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get All Equipment
        public function read() {
            // Create query
            $query = 'SELECT
                        *
                    FROM
                        ' . $this->table . '
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Equipment
        public function read_single() {
            // Create query
            $query = 'SELECT
                        *
                    FROM
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

        // Get Equipment From Category
        public function read_category() {
            // Create query
            $query = 'SELECT
                        *
                    FROM
                        ' . $this->table . '
                    WHERE
                        category = ?
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind param
            $stmt->bindParam(1, $this->category);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Create Equipment
        public function create() {
            // Create Query
            $query = 'INSERT INTO
                        ' . $this->table . '
                    SET
                        name = :name,
                        description = :description,
                        category = :category,
                        image = :image
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->image = htmlspecialchars(strip_tags($this->image));

            // Bind params
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':image', $this->image);

            // Execute statement
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Update Equipment
        public function update() {
            // Create query
            $query = 'UPDATE
                        ' . $this->table . '
                    SET
                        name = :name,
                        description = :description,
                        category = :category,
                        image = :image
                    WHERE
                        id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->image = htmlspecialchars(strip_tags($this->image));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind params
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':id', $this->id);

            // Execute statement
            if ($stmt->execute()) {
                return $stmt;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Delete equipment
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