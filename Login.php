<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class Login extends Page{

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
        
        <h2>Login:</h2>
        <form action="Login.php" method="post">

        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" minlength="6" required><br><br>

        <input type="submit" value="Log in.">
        <a href="Register.php"><button type="button">You dont have an account? Create one!</button></a>

        <br>
        <br>
EOT;

        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {
        if(isset($_POST['email']) && isset($_POST['password'])){
            $email = $_POST['email'];
            $password = $_POST['password'];


            $sql = "SELECT user_id, email, password FROM users WHERE email = ?";

            $stmt = $this->_db->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];

                if(password_verify($password, $hashed_password)){
                  $_SESSION['user_id'] = $row['user_id'];
                  header("Location: index.php");
                  exit();
                }else {
                    echo "Wrong Password";
                }
            }else {
                echo "User not found!";
            }
        }
    }

    public static function main(): void 
    {
        try{
            $page = new Login();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Login::main();