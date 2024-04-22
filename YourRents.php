<?php declare(strict_types=1);
require_once "Page.php";

require_once "parts/nav/nav.php";
class YourRents extends Page{

    protected function __construct()
    {
        parent::__construct();
    }
    protected function getViewData(): array
    {
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT b.*, v.*, DATEDIFF(b.booking_end, b.booking_start) AS rental_duration 
        FROM bookings AS b
        INNER JOIN vehicles AS v ON b.vehicle_id = v.vehicle_id
        WHERE b.user_id = ?";

        $stmt = $this->_db->prepare($sql);

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $rents = $result->fetch_all(MYSQLI_ASSOC);

        return $rents;
    }
    protected function generateView(): void{
        $rents = $this->getViewData();
        $this->generatePageHeader("Your Rents");

        echo "<div class='your_rents'>";
        echo "<h2>Your Rents:</h2>";
        if(!empty($rents)){
            echo "<ul>";
            foreach($rents as $rent){
                $imageData = base64_encode($rent['image']); 
                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Car Image' />";
                echo "<h3>" . $rent['make'] . " " . $rent['model'] . "</h3>";
                echo "<p>Year: " . $rent['year'] . "</p>";
                echo "<p>Color: " . $rent['color'] . "</p>";
                echo "<p>License Plate: " . $rent['license_plate'] . "</p>";
                echo "<p>Rental Duration: " . $rent['rental_duration'] . " days</p>";
            }
            echo "</ul>";
        } else {
            echo "<p>No rents found.</p>"; 
        }
        echo "<div class='back-to-menu'>";
        echo '<a href="Account.php"><button>Back to Menu</button></a>';
        echo '</div>';
        $this->generatePageFooter();
    }
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new YourRents();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

YourRents::main();