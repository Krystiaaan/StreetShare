<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class success_comment extends Page{

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
        ECHO <<< SUCCESS
        <h1>Commenting Successful!</h1>
        <p>Your Comment has been successfully posted. Thank you for choosing our car sharing service.</p>
        <p><a href="index.php">Go back to homepage</a></p>
        SUCCESS;
        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new success_comment();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

success_comment::main();