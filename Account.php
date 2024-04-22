<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class Account extends Page{

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
        echo "<div class='menu'>";
        echo "<ul>";
        echo "<li><a href='YourRents.php'>Your Rents</a></li>";
        echo "<li><a href='comment_car.php'>Add Comment to Car</a></li>";
        echo "<li><a href='comment_website.php'>Add Comment to Website</a></li>";
        echo "<li><a href='Logout.php'>Logout</a></li>";
        echo "</ul>";
        echo "</div>";

        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new Account();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Account::main();