<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class Mieten extends Page{

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
        if(isset($_GET['vehicle_id'])){
            $vehicle_id = $_GET['vehicle_id'];
            
            $sql = "SELECT * FROM vehicles WHERE vehicle_id = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->bind_param("i", $vehicle_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $vehicle_info = $result->fetch_assoc();
                echo "<div class= 'car-rent'>";
                echo "<h2>Car Info: </h2>";
                $imageData = base64_encode($vehicle_info['image']); 
                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Car Image' />";
                echo "<h3>" . $vehicle_info['make'] . " " . $vehicle_info['model'] . "</h3>";
                echo "<p>Year: " . $vehicle_info['year'] . "</p>";
                echo "<p>Color: " . $vehicle_info['color'] . "</p>";
                echo "<p>License Plate: " . $vehicle_info['license_plate'] . "</p>";

                echo "<h2>Rent this Car!</h2>";
                echo "<form action= 'process_booking.php' method='post'>";
                echo "<input type='hidden' name='vehicle_id' value= '$vehicle_id'>";
                echo "<label for='start_date'>Start Date:</label><br>";
                echo "<input type='date' id='start_date' name = 'start_date' required><br><br>";
                echo "<label for='end_date'>End Date:</label><br>";
                echo "<input type='date' id='end_date' name='end_date' required><br><br>";
                echo "<input type='submit' value='Rent Car'>";
                echo "</form>";

                echo "</div>";
            } else {
                echo "Car doesnt exit!";
            }
           
        }
        else {
            echo "Car-id isnt there";
        }
        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {
        
    }

    public static function main(): void 
    {
        try{
            $page = new Mieten();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Mieten::main();