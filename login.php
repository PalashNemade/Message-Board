<HTML>
<HEAD>
    <TITLE>Message Board</TITLE>
    <style>
        #input{
            background-color: aquamarine ;
        }
        #para{
            font-weight: bold;
            text-align: center;
        }
    </style>
</HEAD>
<Body>

<form action = 'login.php' method = 'post' >
    <h1 id = "para">Message Board</h1>
    <h2 id = "para">Login</h2>
    <fieldset id ="input">
        Username: <input type = "text" name = "username" /><br /><br />
        Password: <input type = "password" name = "userpass" /><br /><br />
        <input type = "submit" name = "login" value = "Login" />
        <?php
        if(isset($_POST['username'])&&isset($_POST['userpass'])) {
            try {
                $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = $dbh->prepare('SELECT * FROM users');
                $query->execute();
                $rowcount = 0;
                while($row = $query->fetch()) {
                    if (($row['username'] == $_POST['username'])) {
                        if ($row['password'] == md5($_POST['userpass'])) {
                            session_start();
                            $_SESSION['username'] = $_POST['username'];
                            $_SESSION['fullname'] = $row['fullname'];
                            echo '<script>window.location = "board.php"</script>';
                            break;
                        }
                    }
                    $rowcount++;
                }
                if($rowcount == $query->rowCount()){
                    echo "<div style = 'color : red'>Incorrect Username or Password </div>";
                }
            }
            catch (PDOException $e) {

            }
        }
        ?>
    </fieldset>
</form>
</Body>
<?php
/**
 * Created by PhpStorm.
 * User: Palash
 */

?>
</HTML>