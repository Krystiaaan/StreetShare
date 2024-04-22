<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";

class Index extends Page{

    protected function __construct()
    {
        parent::__construct();
    }
    protected function getViewData(): array
    {
    $sqlSelect = "SELECT v.*, c.comment_text, c.star_rating, u.email 
    FROM vehicles v
    LEFT JOIN comments c ON v.vehicle_id = c.vehicle_id
    LEFT JOIN users u ON c.user_id = u.user_id
    GROUP BY v.vehicle_id";
            
    $recordSet = $this->_db->query($sqlSelect);
    $vehicles = [];
    
    while ($record = $recordSet->fetch_assoc()) {
        $vehicles[] = $record;
    }
    $recordSet->close();

    foreach ($vehicles as &$vehicle) {
        $sqlRandomComment = "SELECT comment_text, star_rating, email 
            FROM comments 
            JOIN users ON comments.user_id = users.user_id 
            WHERE vehicle_id = {$vehicle['vehicle_id']} 
            ORDER BY RAND() 
            LIMIT 1";
        $result = $this->_db->query($sqlRandomComment);
        
        if ($randomComment = $result->fetch_assoc()) {
            $vehicle['comment_text'] = $randomComment['comment_text'];
            $vehicle['star_rating'] = $randomComment['star_rating'];
            $vehicle['email'] = $randomComment['email'];
        } else {
            $vehicle['comment_text'] = "No comments available";
            $vehicle['email'] = "";
        }
    }

    return $vehicles;
    }
    protected function generateView(): void{
        $data = $this->getViewData();
        $this->generatePageHeader("Car Sharing");
        ECHO <<< INFO
        <section id="info" class='info-section'>
        
            <div class='info-container'>
                <h2>Willkommen bei Street Share - deiner neuen Plattform für einfaches und bequemes Carsharing!</h2>
                <p>Street Share revolutioniert die Art und Weise, wie du von A nach B kommst. Keine langen Wartezeiten mehr an Mietwagenstationen oder teure Taxifahrten. Mit Street Share hast du jederzeit Zugang zu einer Vielzahl von Fahrzeugen in deiner Nähe, ganz bequem über dein Smartphone oder deinen Computer.</p>
       
                <p>Unsere Plattform bietet eine breite Auswahl an Fahrzeugen für jede Gelegenheit - sei es für kurze Fahrten in der Stadt, Wochenendausflüge oder längere Reisen. Vom kompakten Stadtauto über geräumige Familienwagen bis hin zu umweltfreundlichen Elektroautos - bei Street Share findest du das passende Fahrzeug für deine Bedürfnisse.</p>
               
                <p>Das Buchen eines Fahrzeugs ist denkbar einfach. Melde dich einfach auf unserer Webseite an, wähle das gewünschte Fahrzeug aus und reserviere es für den gewünschten Zeitraum. Du kannst sogar den Abhol- und Rückgabeort flexibel wählen, so dass du immer in deiner Nähe ein Auto zur Verfügung hast.</p>
                <p>Mit Street Share zahlst du nur für die tatsächliche Nutzung des Fahrzeugs. Keine versteckten Kosten, keine langfristigen Verpflichtungen. Und das Beste: Unsere Fahrzeuge sind rund um die Uhr verfügbar, so dass du dich jederzeit auf uns verlassen kannst, egal ob für eine spontane Shoppingtour oder einen geplanten Wochenendausflug.</p>

                <p>Also, worauf wartest du noch? Registriere dich noch heute bei Street Share und entdecke eine neue Art der Mobilität!</p>
            </div>
            <div class='icon-container'>
        <ul>
            <li><p><i class="fa-solid fa-phone"></i></p></li>
            <li><p><i class="fa-solid fa-inbox"></i></p></li>
            <li><p><i class="fa-brands fa-facebook"></i></p></li>
            <li><p><i class="fa-brands fa-whatsapp"></i></p></li>
        </ul>
    </div>
        </section>
INFO;
        $loggedIn = isset($_SESSION['user_id']);

        echo "<section id ='cars'class = 'car-section'>";
        echo "<div class= 'car-container'>";
        foreach($data as $item){
            echo "<div class= 'car-column'>";
            $imageData = base64_encode($item['image']); 
            echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Car Image' />";
            echo "<h3>" . $item['make'] . " " . $item['model'] . "</h3>";
            echo "<p>Year: " . $item['year'] . "</p>";
            echo "<p>Color: " . $item['color'] . "</p>";
            echo "<p>License Plate: " . $item['license_plate'] . "</p>";
            echo "<p>Availability: ";
            if ($item['availability'] === 'available') {
                echo "Available";
            } else {
                echo "Not Available";
            }
            echo "</p>";
            echo "<br>";
            echo "<p>Your opinions on our Cars! </p>";
            echo "<p>User: " . $item['email'] . "</p>";
            echo "<p>Star Rating: ";
            for($i = 1; $i <= 5; $i++){
                if($i <= $item['star_rating']){
                    echo "<i class='fa-solid fa-star' style='color: #ff9f44;''></i>";
                }else {
                    echo "<i class='fa-regular fa-star'style='color: #ff9f44;'></i>";
                }
            }
            echo "</p>";
            echo "<p>Comment: " . $item['comment_text'] . "</p>";
            
            if ($item['availability'] === 'available') {
                if($loggedIn){
                    echo "<a href='Mieten.php?vehicle_id=" . $item['vehicle_id'] . "'><button>Rent this Car!</button></a>";
                } else {
                    echo "<p>You need to <a href='login.php'>login</a> to rent this car.</p>";
                    echo "<button disabled>Rent this Car!</button>";
                }
            } else {   
                echo "<button disabled>Rent this Car!</button>";
            }
            echo "</div>";
        }

        echo "</div>";
        echo "</section>";
        echo "<section id='comment' class='comment-section'>";
        echo "<h2>Your Comments on our Service!</h2>";
        echo "<div class='comment-container'>";
        
        $randomComments = $this->getRandomComments(6);
        
        foreach($randomComments as $comment){
            echo "<div class='comment-column'>";
            echo "<div class='comment'>";
            echo "<p>Username: {$comment['username']}</p>";
            echo "<p>Date: {$comment['comment_date']}</p>";
            echo "<p>Rating:";
            for($i = 1; $i <= 5; $i++){
                if($i <= $comment['star_rating']){
                    echo "<i class='fa-solid fa-star' style='color: #ff9f44;'></i>";
                } else {
                    echo "<i class='fa-regular fa-star' style='color: #ff9f44;'></i>";
                }
            }
            echo "</p>";
            echo "<p>{$comment['comment_text']}</p>";
            echo "</div>"; 
            echo "</div>"; 
        }
        
        echo "</div>"; 
        echo "</section>";

        echo "<section id='contact' class='contact-section'>";
        echo "<h2>Contact our Service!</h2>";
        echo "<div class='contact-container'>";
        echo "<div class='contact-info'>";
        echo "<p>Here is our email: <a href='mailto:contact@example.com'>contact@example.com</a></p>";
        echo "<p>Here is our number: <a href='tel:+123456789'>+123456789</a></p>";
        echo "<p>Here are our social media:</p>";
        echo "<ul class='social-media'>";
        echo "<li><a href='#'><i class='fab fa-facebook-f'></i></a></li>";
        echo "<li><a href='#'><i class='fab fa-twitter'></i></a></li>";
        echo "<li><a href='#'><i class='fab fa-instagram'></i></a></li>";
        echo "<li><a href='#'><i class='fab fa-linkedin-in'></i></a></li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        echo "</section>";
        $this->generatePageFooter();
    }
    protected function getRandomComments(int $count) : array{
        $sql = "SELECT comment_text, star_rating, comment_date, username
        FROM website_comments
        JOIN users ON website_comments.user_id = users.user_id
        ORDER BY RAND()
        LIMIT ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bind_param("i", $count);
        $stmt->execute();
        $result = $stmt->get_result();

        $randomComments = [];

        while($row = $result->fetch_assoc()){
            $randomComments[] = $row;
        }

        return $randomComments;
    }
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new Index();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Index::main();