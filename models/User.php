<?php
    class User {
        // DB Stuff
        private $conn;
        private $table = 'users';

        // User Properties
        public $id;
        public $name;
        public $surname;
        public $email;
        public $password;
        public $department;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Users
        public function read(){
            // Create Query
            $query = 'SELECT 
                        id, 
                        name, 
                        surname, 
                        email, 
                        department 
                    FROM
                        ' . $this->table . '
                    ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single User
        public function read_single() {
            // Create Query
            $query = 'SELECT
                        id,
                        name,
                        surname,
                        email,
                        department
                    FROM
                        ' . $this->table . '
                    WHERE
                        id = ?
                    ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute statement
            $stmt->execute();

            return $stmt;

            // $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // // Set properties
            // $this->name = $row['name'];
            // $this->surname = $row['surname'];
            // $this->email = $row['email'];
            // $this->department = $row['department'];
        }

        // Register New User
        public function register() {
            # Test user
                // Create query
                $query = 'SELECT
                            *
                        FROM
                            ' . $this->table . '
                        WHERE
                            email = ?
                ';

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Bind params
                $stmt->bindParam(1, $this->email);

                // Execute statement
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    // If email already used
                    return false;
                }
            # End


            // Create Query
            $query = 'INSERT INTO
                        ' . $this->table . '
                    SET
                        name = :name,
                        surname = :surname,
                        email = :email,
                        password = :password,
                        department = :department
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->department = htmlspecialchars(strip_tags($this->department));
            
            // Encrypt password
            $this->password = hash('sha256', $this->password);
            
            // Bind params
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':surname', $this->surname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':department', $this->department);

            // Execute statement
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Update User
        public function update() {
            // Create query
            $query = 'UPDATE
                        ' . $this->table . '
                    SET
                        name = :name,
                        surname = :surname,
                        email = :email,
                        password = :password,
                        department = :department
                    WHERE
                        id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->department = htmlspecialchars(strip_tags($this->department));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Encrypt password
            $this->password = hash('sha256', $this->password);

             // Bind params
             $stmt->bindParam(':name', $this->name);
             $stmt->bindParam(':surname', $this->surname);
             $stmt->bindParam(':email', $this->email);
             $stmt->bindParam(':password', $this->password);
             $stmt->bindParam(':department', $this->department);
             $stmt->bindParam(':id', $this->id);

            // Execute statement
            if ($stmt->execute()) {
                return $stmt;
            }

            // Print error if something goes wrong
            printf("Error : %s. \n", $stmt->error);

            return false;
        }

        // Delete User
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

        // LogIn User
        public function login() {
            // Create Query
            $query = 'SELECT
                        id,
                        name,
                        surname
                    FROM
                        ' . $this->table . '
                    WHERE
                        email = :email
                    AND
                        password = :password
                    ';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            
            // Encrypt password
            $this->password = hash('sha256', $this->password);

             // Bind params
             $stmt->bindParam(':email', $this->email);
             $stmt->bindParam(':password', $this->password);

            // Execute statement
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->surname = $row['surname'];

            return true;
        }
    }