<?php
/**
 * Created by PhpStorm.
 * User: zhangyujia
 * Date: 4720.11.17
 * Time: 07:47
 */
session_start();
//variables
$wish1 = $wish2 = $wish3 = "";
$name = $zip = $tel = "";
//font color, will be red when input fails its validation
$wish1_color = $wish2_color = $wish3_color = "";
$name_color = $zip_color = $tel_color = "";
//error message
$msg = "";


if (!isset($_SESSION['step']) || empty($_SESSION['step'])) { //initial step 0
    $_SESSION["step"] = 0;                                   //step value determine submit button's function
}

if (isset($_POST['submit'])) {
    switch ($_SESSION['step']) {
        case 0:
            wishValidation();
            break;                  //wishes will be validated
        case 1:
            infoValidation();
            break;                  //delivery information will be validated
        case 2:
            session_destroy();
            session_start();
            if (!isset($_SESSION['step']) || empty($_SESSION['step'])) {
                $_SESSION["step"] = 0;
            }
            break;                  //new session will be launched
    }

}


//this function will validate wish format and determine if to Delivery Information page
function wishValidation()
{
    //pass input value
    $_SESSION['wish1'] = $_POST['wish1'];
    $_SESSION['wish2'] = $_POST['wish2'];
    $_SESSION['wish3'] = $_POST['wish3'];

    if (empty($_SESSION['wish1']) || empty($_SESSION['wish2']) || empty($_SESSION['wish3'])) { //empty wish will fail validation
        $msg = "Wish cannot be empty";
    } elseif (preg_match("/.*\d.*/", $_SESSION['wish1'])) {//wish contains digitals will fail validation
        global $wish1_color;
        $wish1_color = "style='color:red';";
        $msg = "Wish1 should only contain letters.";
    } elseif (preg_match("/.*\d.*/", $_SESSION['wish2'])) {//wish contains digitals will fail validation
        global $wish2_color;
        $wish2_color = "style='color:red';";
        $msg = "Wish2 should only contain letters.";
    } elseif (preg_match("/.*\d.*/", $_SESSION['wish3'])) {//wish contains digitals will fail validation
        global $wish3_color;
        $wish3_color = "style='color:red';";
        $msg = "Wish3 should only contain letters.";
    } else {//wishes pass validation
        $msg = "";
        $_SESSION["step"] = 1;//set step to 1. Next time when "ok" button is clicked, infoValidation() will execute
    }
    echo "<p style='color:red'>" . $msg . "</p>"; //set error message to red
}


//this function will validate delivery information format and determine if to Overview page
function infoValidation()
{
    //pass input value
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['zip'] = $_POST['zip'];
    $_SESSION['tel'] = $_POST['tel'];

    //validate name, zip and tel form
    if (empty($_SESSION['name'])) {//empty name will fail validation
        global $name_color;
        $name_color = "style='color:red';";
        $msg = "Name cannot be empty!";
    } else {
        if (!preg_match("/^([a-zA-Z]+\s?)+$/", $_SESSION['name'])) { //name contain except letters will fail validation
            global $name_color;
            $name_color = "style='color:red';";
            $msg = "Name can only contain letters.";
        } else {
            if (empty($_SESSION['zip'])) {//empty zip will fail validation
                global $zip_color;
                $zip_color = "style='color:red';";
                $msg = "zip cannot be empty!";
            } else {
                if (!preg_match("/^\d+[\s]\w+$/", $_SESSION['zip'])) {//zip NOT follows certain format: NUMBER CITY, will fail validation
                    global $zip_color;
                    $zip_color = "style='color:red';";
                    $msg = "Zip and city should be like '23562 &nbsp Luebeck'. There must be a space between zip and city!";
                } else {
                    if (empty($_SESSION['tel'])) {//empty telephone number will fail validation
                        global $tel_color;
                        $tel_color = "style='color:red';";
                        $msg = "Telephone number cannot be empty!";
                    } elseif (!preg_match("/^(\+)?(\d+[\s-]?)+$/", $_SESSION['tel'])) {//tel NOT follows certain format will fail validation
                        global $tel_color;
                        $tel_color = "style='color:red';";
                        $msg = "Telephone number can be like: +49 103-2-311." . "<br>";
                    } elseif (strlen($_SESSION['tel']) <= 5) {//tel's length less than 6 will fail validation
                        global $tel_color;
                        $tel_color = "style='color:red';";
                        $msg = "The minimum length of Tel. is 6";
                    } else {//all validation pass
                        $_SESSION["step"] = 2;
                        $msg = "";
                    }
                }
            }
        }
    }
    echo "<p style='color:red'>" . $msg . "</p>";
}

?>


<html>
<body>

<h1><?php
    switch ($_SESSION['step']) {//corresponding header will be displayed
        case 0:
            echo "My Wishlist";
            break;
        case 1:
            echo "Delivery information";
            break;
        case 2:
            echo "Wishes overview";
            break;
    } ?>
</h1>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table>
        <tr>
            <td <?php echo $wish1_color ?>> 1. Wish:</td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 0:
                        echo "<input name='wish1' type='text' value=''>";
                        break;
                    default:
                        echo $_SESSION["wish1"];
                        break;
                }
                ?></td>
        </tr>
        <tr>
            <td <?php echo $wish2_color ?>> 2. Wish:</td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 0:
                        echo "<input name='wish2' type='text' value=''>";
                        break;
                    default:
                        echo $_SESSION["wish2"];
                        break;
                }
                ?></td>
        </tr>
        <tr>
            <td <?php echo $wish3_color ?>> 3. Wish:</td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 0:
                        echo "<input name='wish3' type='text' value=''>";
                        break;
                    default:
                        echo $_SESSION["wish3"];
                        break;
                }
                ?></td>
        </tr>
    </table>
    <table style="display: <?php
    switch ($_SESSION["step"]) {//the delivery information will be blocked until wishes validated successfully
        case 0:
            echo "none";
            break;
        default:
            echo "block";
            break;
    }
    ?>;">
        <tr>
            <td <?php echo $name_color ?>> First and Second name: </td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 1:
                        echo "<input name='name' type='text' value='$name'><td></td>";
                        echo $name_color;
                        break;
                    case 2:
                        echo $_SESSION["name"];
                        break;
                }
                ?></td>
        </tr>
        <tr>
            <td <?php echo $zip_color ?>> ZIP and city: </td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 1:
                        echo "<input name='zip' type='text' value='$zip'><td></td>";
                        break;
                    case 2:
                        echo $_SESSION["zip"];
                        break;
                }
                ?></td>
        </tr>
        <tr>
            <td <?php echo $tel_color ?>> Telephone: </td>
            <td><?php
                switch ($_SESSION["step"]) {
                    case 1:
                        echo "<input name='tel' type='text' value='$tel'><td></td>";
                        break;
                    case 2:
                        echo $_SESSION["tel"];
                        break;
                }
                ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                <input type="button" value="Cancel" name="cancel" onclick="window.location='index.php'"
                       style="color:red; display: <?php
                       switch ($_SESSION["step"]) {//the cancel button will disappear when finished all enter
                           case 0:
                               echo "block";
                               break;
                           case 1:
                               echo "block";
                               break;
                           case 2:
                               echo "none";
                               break;
                       } ?>;">
            </td>
            <td>
                <input type="submit" value="Ok" name="submit" style="color:green; display: inline">
            </td>
        </tr>
    </table>

</form>
</body>
</html>

