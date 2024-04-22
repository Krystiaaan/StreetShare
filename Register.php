<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";
class Register extends Page{

    protected function __construct()
    {
        parent::__construct();
    }
    protected function getViewData(): array
    {
        return array();
    }
    protected function generateView(): void{
        $data = $this->getViewData();
        $this->generatePageHeader("Car Sharing");
        echo <<< EOT
        
        <h2>Register:</h2>
        <form action="Register.php" method="post">

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" minlength="6" required><br><br>

        <label for="address">Adress:</label><br>
        <input type="text" id="address" name="address" required><br><br>

        <label for="phone_number">Phone number:</label><br>
        <input type="number" id="phone_number" name="phone_number" pattern="[0-9]*" inputmode="numeric" minlength="5" required><br><br>


        <input type="submit" value="Create your Account!">
        <a href="login.php"><button type="button">You already have an Account? Log in!</button></a>
        EOT;
        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['address']) && isset($_POST['phone_number'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $address = $_POST['address'];
            $phone_number = $_POST['phone_number'];

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $check_query = "SELECT email FROM users WHERE email =?";
            $check_stmt = $this->_db->prepare($check_query);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if($check_stmt->num_rows > 0){
                echo "E-mail adress already in use.";
                $check_stmt->close();
                return;
            }
            $check_stmt->close();

            $sql = "INSERT INTO users(username, email, password, address, phone_number) VALUES(?,?,?,?,?)";

            $stmt = $this->_db->prepare($sql);
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $address, $phone_number);

            if($stmt->execute() === TRUE) {
                echo "You were Registred!";
            }else {
                echo "Error: ". $stmt->error;
            }
            $stmt->close();

        }
    }

    public static function main(): void 
    {
        try{
            $page = new Register();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Register::main();