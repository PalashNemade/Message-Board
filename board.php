<?php
session_start();
error_reporting(0);
?>
<html>
<head><title>Message Board</title></head>
<body>
<form action = "board.php" method ="POST" >
    <input type = "submit" name = "logout" value = "Logout" />
</form>
<?php
if(isset($_POST['logout'])){
    session_unset($_SESSION);
    session_destroy();
    echo '<script>window.location = "login.php"</script>';
}
?>

<br/>

<div style="display: inline-block">
    <form id="data" action = "board.php" method = "POST">
    <textarea name = "message" rows = "10" cols = "50" >

    </textarea><br />

    <input type = "submit" name = "newPost" value = "New Post" />
    </form>
</div>
<div style="text-align: center; background-color: chartreuse;display: inline-block;float: right;overflow: scroll;max-height: 400px;width: 50% ;margin-right: 100px">
<?php
$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$message = $_POST['message'];
$messageId = uniqid();
$username = $_SESSION['username'];

if(isset($_POST['message'])&&isset($_POST['newPost'])){

        $query = $dbh->prepare('INSERT INTO posts VALUES (?,NULL,?,NOW(),?)');
        $query->execute([$messageId,$username,$message]);

        /* print_r($dbh);
         $dbh->beginTransaction();
         $dbh->exec('delete from users where username="smith"');
         $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
               or die(print_r($dbh->errorInfo(), true));
         $dbh->commit();

         $stmt = $dbh->prepare('select * from users');
         $stmt->execute();
         print "<pre>";
         while ($row = $stmt->fetch()) {
           print_r($row);
         }
         print "</pre>"; */
}

if(isset($_GET['replyto'])){

    $replyMsgId = uniqid();
    $statement = $dbh->prepare('INSERT INTO posts VALUES (?,?,?, NOW(),?)');
    $statement->execute([$replyMsgId,$_GET['replyto'],$_SESSION['username'],$message]);
}

$statement = $dbh->query('SELECT po.id,us.username,us.fullname,po.datetime,po.replyto,po.message FROM posts po, users us WHERE po.postedby = us.username ORDER BY po.datetime desc');
while($rows = $statement->fetch()){
    echo "<div><label> FullName: ".$rows['fullname']."</label><br />";
    echo "<label> Username: ".$rows['username']."</label><br />";
    echo "<label> Message ID: ".$rows['id']."</label><br />";
    echo "<label> Date/Time: ".$rows['datetime']."</label><br />";
    echo "<label> Reply to: ".$rows['replyto']."</label><br />";
    echo "<label> Message: ".$rows['message']."</label><br />";
    echo "<button type = 'submit' formaction = 'board.php?replyto= ".$rows['id']."' form='data'> Reply </button></div>";
}
?>
</div>
</body>
</html>