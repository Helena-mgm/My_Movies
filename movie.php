<?php

include("system.php");

$movieId = $_GET['id'];
$date = isset($_GET['date']) ? $_GET['date'] : '';
$room = isset($_GET['room']) ? $_GET['room'] : '';

if ($movieId > 0) {
    $userSql = "SELECT * FROM movie WHERE id = $movieId";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        if(isset($_GET['date'])) {
            $sql = "INSERT into movie_schedule (id_movie, date_begin, id_room) VALUES ('$movieId', '$date', '$room')";
            $result = $conn->query($sql);
            echo "Séance ajoutée";
 } } }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Fiche film</title>
</head>
<body>
<section class="wrapper">
      <div id="stars1"></div>
      <div id="stars2"></div>
      <div id="stars3"></div>
    <h1>Ajouter une séance à un film :</h1>
    


    <form method="GET" action ="movie.php">
    <select name="room" id="room">
 
        <option value="">Selectionnez une salle</option>   
        <option value="1">montana</option>
        <option value="2">highscore</option>
        <option value="3">salle 3</option>
        <option value="4">astek</option>
        <option value="5">gecko</option>
        <option value="6">azure</option>
        <option value="7">toshiba</option>
        <option value="8">salle 14</option>
        <option value="9">asus</option>
        <option value="10">salle 16</option>
        <option value="11">microsoft</option>
        <option value="12">VIP</option>
        <option value="13">golden</option>
        <option value="14">salle 23</option>
        <option value="15">lenovo</option>
        <option value="17">salle 31</option>
        <option value="19">huawei</option>
    </select>

        <input type="hidden" name="id" value="<?php echo $movieId; ?>">
        <input type="date" id="start" name="date" value="2018-07-22" min="2018-01-01" max="2024-12-31" />
  
    <input type="submit">
    <div class="mo"></div>

</form>        
</section>
</body>
</html>