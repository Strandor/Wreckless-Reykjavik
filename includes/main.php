<?php
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '** pass **';
$db_name = 'wreckless';
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die($conn->error);

function error($title, $message, $critical = false, $conn = null) {
    $_SESSION['error-title'] = $title;
    $_SESSION['error-text'] = $message;

    if($critical == true) {
        $title = $conn->escape_string($title);
        $function = $conn->escape_string(debug_backtrace()[0]['file']);
        $ip = $conn->escape_string($_SERVER['REMOTE_ADDR']);
        $conn->query("INSERT INTO `error`(`Title`, `Function`, `Time`, `IP`) VALUES ('" . $title . "','" . $function . "',CURRENT_TIMESTAMP,'" . $ip ."')");
    }
    header('Location: /error');
}

function warning($title, $message) {
    ?>
    <div class="limit-centered">
        <div class="error-box">
            <h1><?php echo $title?></h1>
            <p><?php echo $message?></p>
        </div>
    </div>
    <?php
}

function info($title, $message) {
    ?>
    <div class="limit-centered">
        <div class="error-box" style="border-top-color: #3F5765">
            <h1><?php echo $title?></h1>
            <p><?php echo $message?></p>
        </div>
    </div>
    <?php
}

function createTitle($name) {
    echo '<div class="limit-centered"> <h1 class="small-title">- ' . $name . '</h1> </div>';
}

function isLoggedIn() {
  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    return true;
  }
  return false;
}

class extraBoxes {
    public $title;
    public $content;
}

function createSideMenu($title, $subTitle, $smallText, $button, $arrayBox) {
?>
    <div class="tableRight">
        <div class="checkoutTotal">
            <h1><?php echo $title ?></h1>
            <h3 id="total"><?php echo $subTitle ?></h3>
            <?php echo $button?>
            <p><?php echo $smallText ?></p>
        </div>
        <br>
        <div class="extraBoxes">
            <?php
            foreach($arrayBox as $array) {
                ?>
                <div class="boxes">
                    <h3><?php echo $array->title?></h3>
                    <img onclick="toggleExtraBoxes(this)" src="/assets/vector/add.svg"/>
                    <img onclick="toggleExtraBoxes(this)" src="/assets/vector/remove.svg" style="display: none;"/>
                    <br>
                    <div class="extraBoxValues">
                        <?php echo $array->content ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
<?php
}
