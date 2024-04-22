<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class comment_car extends Page{

    protected function __construct()
    {
        parent::__construct();
    }
    protected function getViewData(): array
    {
        return array();
    }
    protected function generateView(): void{
        $vehicleModels = $this->getAvailableVehicleModels();

    $this->generatePageHeader("Add Comment to Car");

    
    echo <<<HTML
    <div class="comment-form">
        <h2>Add Comment to Car</h2>
        <form action="process_comment_car.php" method="post">
            <label for="model">Select Car Model:</label><br>
            <select id="model" name="model" required>
                <option value="" selected disabled>Select Car Model</option>
HTML;

    foreach ($vehicleModels as $model) {
        echo "<option value='{$model}'>{$model}</option>";
    }

    echo <<<HTML
            </select><br>
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br>
            <label for="rating">Rating:</label>
            <select id="rating" name="rating" required>
                <option value="" selected disabled>Select Star Rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select><br>
            <input type="submit" value="Submit">
        </form>
    </div>
    <div class="back-to-account">
        <a href="Account.php"><button>Back to Account</button></a>
    </div>
HTML;

    $this->generatePageFooter();
    }
    protected function getAvailableVehicleModels(): array {
        
        $sql = "SELECT DISTINCT model FROM vehicles";
        $stmt = $this->_db->query($sql);
    
       
        $models = [];
        while ($row = $stmt->fetch_assoc()) {
            $models[] = $row['model'];
        }
    
        return $models;
    }
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new comment_car();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

comment_car::main();