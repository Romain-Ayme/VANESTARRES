<?php


class UserService
{
    protected $_email;    // using protected so they can be accessed
    protected $_password; // and overidden if necessary

    protected $_db;       // stores the database handler
    protected $_user;     // stores the user data

    public function __construct(PDO $db, $email, $password)
    {
        $this->_db = $db;
        $this->_email = $email;
        $this->_password = $password;
    }

    public function login()
    {
        if (!$user = $this->_checkCredentials()) {
            $user = $this->_checkCredentialsPseudo();
        }
        if ($user) {
            $this->_user = $user; // store it so it can be accessed later (tag 5)
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user_id'] = $user['ID_USER'];
            $_SESSION['email'] = $user['EMAIL'];
            $_SESSION['password'] = $user['PSWD'];
            $_SESSION['pseudo'] = $user['PSEUDO'];
            return $user['ID_USER'];
        }
        return false;
    }

    protected function _checkCredentials()
    {
        $stmt = $this->_db->prepare('SELECT * FROM users WHERE EMAIL=?');
        $stmt->execute(array($this->_email));
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            //$submitted_pass = sha1($user['salt'] . $this->_password);
            $submitted_pass = $_POST['password'];
            if ($submitted_pass == $user['PSWD']) {
                return $user;
            }
        }
        return false;
    }

    protected function _checkCredentialsPseudo()
    {
        $stmt = $this->_db->prepare('SELECT * FROM users WHERE PSEUDO=?');
        $stmt->execute(array($this->_email));
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            //$submitted_pass = sha1($user['salt'] . $this->_password);
            $submitted_pass = $_POST['password'];
            if ($submitted_pass == $user['PSWD']) {
                return $user;
            }
        }
        return false;
    }

    public function getUser()
    {
        return $this->_user;
    }
}