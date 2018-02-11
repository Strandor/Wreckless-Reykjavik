<?php
require_once("main.php");

function getLoginValues() {
    return array(
        'name' => $_SESSION['name'],
        'id' => $_SESSION['id'],
        'email' => $_SESSION['email']
    );
}

function checkIfValuesAreValid($email, $password = null, $name = null) {
    $email_value = null;
    if(strlen($email) > 50) {
        $email_value = "Email is to long, maximum 50 characters";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_value =  "Email is not valid";
    }

    $checkPassword = null;
    if(strlen($password) < 8) {
        $checkPassword = "Password must be longer than 7 characters";
    }

    if(strlen($password) > 50) {
        $checkPassword = "Password can't be longer than 50 characters";
    }

    $checkName = null;
    if($name != null) {
        if(strlen($name) > 50) {
            $checkName = "Name is to long, maximum 50 characters";
        } else if(strlen($name) < 2) {
            $checkName = "Name is not valid";
        }
    }

    return array(
        'email' => $email_value,
        'password' => $checkPassword,
        'name' => $checkName
    );
}

function clearSession() {
    $_SESSION['name'] = null;
    $_SESSION['email'] = null;
    $_SESSION['id'] = null;
    $_SESSION['activated'] = null;
    $_SESSION['logged_in'] = null;
}

function setupSession($name, $email, $ID, $activated) {
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['id'] = $ID;
    $_SESSION['activated'] = $activated;
    $_SESSION['logged_in'] = true;
}

function validateUser($conn, $email, $password) {
    $result = $conn->query("SELECT id,name,password,activated FROM accounts WHERE email='" . $conn->escape_string($email) . "'");
    if(mysqli_num_rows($result) == 0) {
        return array(
            'email' => 'That email does not exist',
            'password' => null
        );
    } else {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            setupSession($row['name'], $email, $row['id'], $row['activated']);
            return null;
        } else {
            return array(
                'email' => null,
                'password' => 'That password does not match with the email'
            );
        }
    }
}

function loginAccount($conn, $email, $password, $ref = null) {
    $valuesValid = checkIfValuesAreValid($email, $password);
    if(!array_filter($valuesValid)) {
         $return = validateUser($conn, $email, $password);
         if($return == null) {
             if($ref == null) {
                 header("Location: profile/orders");
             } else {
                 header("Location:" . $ref);
                 exit;
             }
         }
         return $return;
    } else {
        return $valuesValid;
    }
}

function createAccount($conn, $name, $email, $password, $ref = null) {
    if(isset($_POST['email'])) {
        $valuesValid = checkIfValuesAreValid($email, $password, $name);
        if(!array_filter($valuesValid) && isset($_POST['checkbox']) && $_POST["checkbox"] == "on") {
            $sql = "SELECT ID FROM accounts WHERE email='" . $conn->escape_string($_POST['email']) . "'";
            $result = $conn->query($sql);
            if(mysqli_num_rows($result) != 0) {
                return array(
                    'name' => null,
                    'email' => 'Email already exists, forgot your password?',
                    'password' => null
                );
            } else {
                $name = $conn->escape_string($_POST['text']);
                $email = $conn->escape_string($_POST['email']);
                $password = $conn->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
                $hash = $conn->escape_string(md5(rand(0,1000)));
                $sql = "INSERT INTO accounts (email, name, password, timedate, hash, activated) VALUES ('$email', '$name', '$password', CURRENT_TIMESTAMP, '$hash', 0)";
                if($conn->query($sql)) {
                    require_once('mail/CommonMail.php');
                    $last_id = $conn->insert_id;
                    sendEmail($_POST['email'], 'Please verify your email - Wreckless Reykjavík', 'PLEASE VERIFY YOUR EMAIL', 'Thank you for creating account with us. You need to verify your account get access to all the features. If you did not create this account ignore this email.', $_SERVER['SERVER_NAME'] . '/account/verify?id='. $last_id . '&hash=' . $hash, 'CLICK TO VERIFY');
                    setupSession($_POST['text'], $_POST['email'], $last_id,0);
                    if($ref == null) {
                        header("Location: profile/orders");
                    } else {
                        header("Location:" . $ref);
                    }
                }
            }
        } else {
            return $valuesValid;
        }
    } else {
        header("Location: /account/register");
    }
    mysqli_close($conn);
}