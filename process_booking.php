<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class process_booking extends Page{

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

        $this->generatePageFooter();
    }
    protected function calculateTotalCost($start_date, $end_date): int{
        $daily_rate = 50;

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days = $interval->days;

        $total_cost = $days * $daily_rate;

        return $total_cost;
    }
    protected function processReceivedData(): void
    {
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $vehicle_id = $_POST["vehicle_id"];
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];

            $user_id = $_SESSION["user_id"];
            $total_cost = $this->calculateTotalCost($start_date, $end_date);

            $sqlUpdateAvailability = "UPDATE vehicles SET availability = 'not available' WHERE vehicle_id = ?";
            $stmtUpdateAvailability = $this->_db->prepare($sqlUpdateAvailability);
            $stmtUpdateAvailability->bind_param("i", $vehicle_id);
            $stmtUpdateAvailability->execute();


            $sql = "INSERT INTO bookings (user_id, vehicle_id, booking_start, booking_end, total_cost) VALUES (?,?,?,?,?)";

            $stmt = $this->_db->prepare($sql);
            $stmt->bind_param("iisss", $user_id, $vehicle_id, $start_date, $end_date, $total_cost);
            $stmt->execute();

            if($stmt->affected_rows > 0 ){
                header("Location: booking_success.php");
                exit();
            }else {
                exit();
            }
        }
    }

    public static function main(): void 
    {
        try{
            $page = new process_booking();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

process_booking::main();