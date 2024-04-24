<!DOCTYPE html>
<html>
    <head>
        <title>My Movies</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h1>My Movies</h1>  

        <h2><a href="admin.php">Partie Admin</a></h2>

<div class="form-container">
    <form method="POST" class="formulaire">
    <input type="text" name="films" placeholder="Rechercher un film"> <br>
    <input type="submit">

    <select name="genre" id="genre">
 
 <option value="">Selectionnez un genre</option>   
 <option value="action">action</option>
 <option value="adventure">adventure</option>
 <option value="animation">animation</option>
 <option value="biography">biography</option>
 <option value="comedy">comedy</option>
 <option value="crime">crime</option>
 <option value="drama">drama</option>
 <option value="family">family</option>
 <option value="fantasy">fantasy</option>
 <option value="horror">horror</option>
 <option value="mystery">mystery</option>
 <option value="romance">romance</option>
 <option value="thriller">thriller</option>
 <option value="sci-fi">science fiction</option>
 </select>
            <button type="submit">Filtrer</button>
        </fieldset>


    <input type="text" name="distributeur" placeholder="Rechercher un distributeur"> <br>
    <input type="submit">

    <label for="projection"> <p>Choisir une date de projection: </p></label>
</form>

<form method="GET" class="formulaire">

<input type="date" id="start" name="date" value="2018-07-22" min="2018-01-01" max="2024-12-31" />

<input type="submit">  

</form> 
</div>


<?php
include("system.php");

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 5;
$genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$films = isset($_POST['films']) ? $_POST['films'] : '';
$distributor = isset($_POST['distributeur']) ? $_POST['distributeur'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

if(isset($_GET['date'])) {
    $sql = "SELECT * FROM movie JOIN movie_schedule ON movie.id = movie_schedule.id_movie WHERE date_begin LIKE '%$date%'";
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div>Results:</div>';
    while ($row = $result->fetch_assoc()) {
        echo '<a href="movie.php?id=' . $row['id'] . '">' . $row['title'] . "</a> <br>";
    } } }


function countArticles()
{
    return 1;
}

if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['films']) || isset($_POST['distributeur']) || isset($_POST['genre'])) {
    $sql = "SELECT * FROM movie 
            JOIN distributor ON movie.id_distributor = distributor.id
            JOIN movie_genre ON movie.id = movie_genre.id_movie
            JOIN genre ON movie_genre.id_genre = genre.id ";

    if (!empty($distributor)) {
        $sql .= " AND distributor.name LIKE '%$distributor%'";
    }

    if (!empty($genre)) {
        $sql .= " AND genre.name LIKE '%$genre%'";
    }

    if (!empty($films)) {
        $sql .= " AND title LIKE '%$films%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="p">Results:</div>';
        while ($row = $result->fetch_assoc()) {
            echo '<a href="movie.php?id=' . $row['id_movie'] . '">' . $row['title'] . "</a> <br>";
        }
    } else {
        echo '<div class="p"> No results found.</div>';
    }

} 

$total_pages = $conn->query('SELECT COUNT(*) FROM movie')->fetch_row()[0];
$lastPage = $conn->query("SELECT * FROM movie ORDER BY title DESC LIMIT 5");

if ($stmt = $conn->prepare('SELECT * FROM movie LIMIT ?,?')) {

    $calc_page = ($page - 1) * $num_results_on_page;
    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
    $stmt->execute(); 

    $result = $stmt->get_result();
    $stmt->close();
}

?>


    </body>
</html>