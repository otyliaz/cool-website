<?php
session_start();
if(!isset($_SESSION['iduser'])){
   header("Location: login.php");
}
?>
<!--remember TO SANITIZE INPUTS-->
<!DOCTYPE html>
<html lang="en">
<head>  
    <title>Add list - Vocable</title>
    <meta charset="UTF-16" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<?php
include_once("nav.php");
//ADD require_once("connect.inc");

require_once("connlocal.inc");

// select from user_languages where language = the language and user = the user
$iduser_lang = $_SESSION['iduser_lang'];

$q_select="SELECT `idlist`, `listname`, `level`, `public` FROM lists WHERE iduser_lang = $iduser_lang"; 
$r_select= @mysqli_query ($conn, $q_select);

if(isset($_POST['submit'])) {
    
    $name = $_POST['name'];
    $level = $_POST['level'];
    $public = $_POST['public'];

    $query="INSERT INTO `lists` (`listname`, `iduser_lang`, `level`, `public`) VALUES ('$name', '$iduser_lang', '$level', '$public')";
    
    $inserted= @mysqli_query ($conn, $query);

    //redirects it back to the page after submission so it will show the list
    header("Location: addlist.php");
    exit();

    //echo $_SESSION['iduser_lang'] ; 
    //echo $query;
}

//select from table lists where the idlist=
$selected_language = $_SESSION['selected_language'];
?>

<body>
<div class="content">

<?php 
if (mysqli_num_rows($r_select) > 0) { 
    echo '<h2>Here are your lists for ' . $selected_language . ':</h2>';

    echo "<div class='container'>";
    //fetches the rows
    while ($row = mysqli_fetch_array($r_select, MYSQLI_ASSOC)) {
    $idlist = $row['idlist'];
    //the link for the lists
    echo '<div class="card" id="list"><a id="list" href="/addwords.php?idlist=' . $idlist . '"><p id="name">' . $row['listname'] . '</p>';
    echo '<p>' . $row['level'] . '</p>'; 
    echo "<div id='public-status'>";
    if ($row['public'] == 1) {
        echo '<p>Public</p> <i class="fa fa-unlock"></i>';} 
    else {
        echo '<p>Private</p><i class="fa fa-lock"></i>';} 
    //echo $row['public'];
    echo "</div>";

    
    echo '<a class="button delete" href="/deletelist.php?idlist=' . $idlist . '">Delete</a>';
    echo '</a></div>';} 

    echo "</div>";

    echo '<br><h2>Or create a new list below!</h2>';
}
else{
    echo "<h2>Looks like you don't have any " . $selected_language. " lists, create one below!</h2><br>";
}
?>

    <div class="form">
        <form action="addlist.php" method="post"> 
            <label for="name">Name of your vocabulary list:</label><br>
            <input type="text" name="name" id="name" placeholder="Type here..." required> 
            <br>
            <label for="level">Proficiency level:</label><br>
            <select name="level" id="level">
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
                <option value="Technical">Technical</option>
            </select>
            <br>
            <input type="checkbox" name="public" id="public" value="1"> 
            <label for="public">Set public? (Everyone will be able to see your list!) </label>
            <br><input class="button" type="submit" name="submit" value="Go!">
        </form>
    </div>

</div>

<footer>
    <p>&copy; Vocable by Otylia 2023</p>
</footer>

</body>
</html>