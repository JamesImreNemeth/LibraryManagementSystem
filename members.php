<?php
class Members {
    private $conn;
    public $email;
    public $password;
    public $firstname;
    public $lastname;
    public $MemberID;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function loginUser() {
        $sql = "SELECT MemberID, FirstName, LastName FROM members WHERE Email = ? AND Password = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->email, $this->password]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->MemberID = $row['MemberID'];
            $this->firstname = $row['FirstName'];
            $this->lastname = $row['LastName'];
            return true;
        } else {
            return false;
        }
    }
}
?>
