<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class process_comment_car extends Page{

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
    protected function processReceivedData(): void
    {
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            if(isset($_POST["model"]) && isset($_POST["comment"]) && isset($_POST["rating"])){
                $comment = $_POST["comment"];
                $model = $_POST["model"];
                $rating = (int)$_POST["rating"];

                $userId = $_SESSION['user_id'];
                
                $vehicleId = $this->getVehicleIdByModel($model);

                $this->insertComment($vehicleId, $userId, $comment, $rating);

                header("Location: success_comment.php");
            }else {
                exit();
            }
            
        } 
    }   
    protected function getVehicleIdByModel(string $model): ?int{
        $sql = "SELECT vehicle_id FROM vehicles WHERE model = ?";
    $stmt = $this->_db->prepare($sql);
    $stmt->bind_param("s", $model);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['vehicle_id'] : null;
    }

    protected function insertComment(int $vehicleId, int $userId, string $commentText, int $rating): void {
        $sql = "INSERT INTO comments (vehicle_id, user_id, comment_text, star_rating, comment_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->_db->prepare($sql);
        $stmt->bind_param("iisi", $vehicleId, $userId, $commentText, $rating);
        $stmt->execute();
    }
    public static function main(): void 
    {
        try{
            $page = new process_comment_car();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

process_comment_car::main();