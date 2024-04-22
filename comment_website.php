<?php declare(strict_types=1);

require_once "Page.php";
require_once "parts/nav/nav.php";
class comment_website extends Page{

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
        $this->generatePageHeader("Add comment to our Service!");
        echo <<< HTML
        <div class="comment-form">
            <h2>Add Comment to our Service!</h2>
            <form action="process_comment_website.php" method="post">
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
    protected function processReceivedData(): void
    {

    }

    public static function main(): void 
    {
        try{
            $page = new comment_website();
            $page->processReceivedData();
            $page->generateView();
        }catch(Exception $e){
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

comment_website::main();