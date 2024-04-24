<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css" />
    <title>Admin Panel</title>
</head>
<body>
<section class="wrapper">
      <div id="stars1"></div>
      <div id="stars2"></div>
      <div id="stars3"></div>
    <h1>Rechercher un membre</h1>
    <form action="" method="POST" class="formulaire">
        <input type="text" name="name" placeholder="Rechercher un prénom ou nom de famille"> <br>

</form>        
</section>
</body>
</html>

<?php
include("system.php");

$name = isset($_POST['name']) ? $_POST['name'] : '';


if (isset($_POST['name'])) {
    $sql = "SELECT * FROM user WHERE CONCAT( firstname, ' ', lastname ) LIKE '%$name%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div>Results:</div>';
        while ($row = $result->fetch_assoc()) {
            echo '<a href="user.php?id=' . $row['id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</a> <br>';
        }

    } else {
        echo '<div>No results found.</div>';
    }

} else {

    echo '<div>Please provide at least one filter.</div>';
}

?>