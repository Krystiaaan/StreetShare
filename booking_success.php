<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class booking_success extends Page{

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
        <h1>Booking Successful!</h1>
        <p>Your booking has been successfully processed. Thank you for choosing our car sharing service.</p>
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
            $page = new booking_success();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

booking_success::main();