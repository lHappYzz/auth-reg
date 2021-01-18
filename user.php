<?php


class user
{
    private $email;
    private $password;
    private $hashedPassword;
    private $hashSalt = 'digiants';

    private $mysqli;

    public function __construct($email, $password) {
        $this->email = htmlspecialchars(trim($email));
        $this->password = $password;
        $this->mysqli = $this->setDatabaseConnection();
    }
    function setDatabaseConnection() {
        $mysqli = new mysqli('localhost', 'root', '', 'digiants');

        if ($mysqli->connect_errno) {
            exit("MySQL connection error: " . $mysqli->connect_error);
        }

        return $mysqli;
    }

    function register() {
        $this->hashPassword();
        $stmt = $this->mysqli->prepare("INSERT INTO user(email, password) VALUES(?, ?)");
        $stmt->bind_param('ss', $this->email, $this->hashedPassword);
        $stmt->execute();
        $stmt->close();
    }

    function getAllUsers() {
        $result = $this->mysqli->query("SELECT * from user");
        $usersList = [];
        if ($result->num_rows >= 1) {
            $usersList = $result->fetch_all(MYSQLI_ASSOC);
        }
        return $usersList;
    }
    /**
     * @param $id
     * @throws Exception
     */
    function getUser($id) {
        $result = $this->mysqli->query("SELECT * FROM user WHERE id = '{$id}'");
        if ($result->num_rows == 1) {
            $resultRow = $result->fetch_assoc();
            $this->__construct($resultRow['email'], '');
            return;
        }
        throw new Exception('Bad credentials.');
    }

    /**
     * @throws Exception
     */
    function login() {
        $result = $this->mysqli->query("SELECT * FROM user WHERE email = '{$this->email}'");
        if ($result->num_rows == 1) {
            $resultRow = $result->fetch_assoc();
            if (password_verify($this->password . $this->hashSalt, $resultRow['password'])) {
                $_SESSION['userEmail'] = $this->email;
                $_SESSION['userId'] = $resultRow['id'];

                return;
            }
        }
        throw new Exception('Wrong email or password.');
    }

    function hashPassword() {
        $this->hashedPassword = password_hash($this->password . $this->hashSalt, PASSWORD_BCRYPT);
    }

    /**
     * @throws Exception
     */
    function validate() {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->isEmailUnique();
        } else {
            throw new Exception('Wrong email.');
        }
        if (empty($this->password) || mb_strlen($this->password) > 255) {
            throw new Exception('Wrong password.');
        }
    }

    /**
     * @throws Exception
     */
    function  isEmailUnique() {
        $resultNumRows = $this->mysqli->query("SELECT email FROM user WHERE user.email = '{$this->email}'")->num_rows;
        if($resultNumRows >= 1) {
            throw new Exception('Email should be unique.');
        }
    }
}