<?php

include("system.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_subscription'])) {
        updateSubscription($id);
    } elseif (isset($_POST['delete_subscription'])) {
        deleteSubscription($id);
    } elseif (isset($_POST['add_to_history'])) {
        addToHistory($id);
    }
}

if ($id > 0) {
    $userSql = "SELECT * FROM user WHERE id = $id";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $subscriptionSql = "SELECT subscription.name FROM subscription
                            JOIN membership ON membership.id_subscription = subscription.id
                            WHERE membership.id_user = $id";

        $subscriptionResult = $conn->query($subscriptionSql);

        if ($subscriptionResult->num_rows > 0) {
            $sub = $subscriptionResult->fetch_assoc();
        }
    }
}





function updateSubscription($userId) {
    global $conn;

    $selectedSubscription = isset($_POST['subscription']) ? $_POST['subscription'] : '';

    if ($userId > 0 && !empty($selectedSubscription)) {
        $checkSubscriptionSql = "SELECT * FROM membership WHERE id_user = $userId";
        $checkSubscriptionResult = $conn->query($checkSubscriptionSql);

        if ($checkSubscriptionResult->num_rows > 0) {
            $updateSubscriptionSql = "UPDATE membership SET id_subscription = 
                                      (SELECT id FROM subscription WHERE name = '$selectedSubscription')
                                      WHERE id_user = $userId";
            $conn->query($updateSubscriptionSql);
        } else {
            $addSubscriptionSql = "INSERT INTO membership (id_user, id_subscription) 
                                   VALUES ($userId, (SELECT id FROM subscription WHERE name = '$selectedSubscription'))";
            $conn->query($addSubscriptionSql);
        }
    }

    header("Location: user.php?id=$userId");
    exit();
}

function deleteSubscription($userId) {
    global $conn;

    if ($userId > 0) {
        $deleteSubscriptionSql = "DELETE FROM membership WHERE id_user = $userId";
        $conn->query($deleteSubscriptionSql);
    }

    header("Location: user.php?id=$userId");
    exit();
}



function addToHistory($userId) {
    global $conn;

    $selectedMovie = isset($_POST['movie']) ? $_POST['movie'] : '';

    if ($userId > 0 && !empty($selectedMovie)) {
        $addHistorySql = "INSERT INTO membership_log (id_membership, id_session)
                          VALUES ($userId, $selectedMovie)";
        $conn->query($addHistorySql);
    }

    header("Location: user.php?id=$userId");
    exit();
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css" />
    <title>User Subscription</title>
</head>
<body>


<section class="wrapper">
      <div id="stars1"></div>
      <div id="stars2"></div>
      <div id="stars3"></div>
    
<a href="admin.php">Rechercher un autre utilisateur </a>
    <?php if (isset($user)) : ?>
        <div><p id="id">Name: <?= $user['firstname'] . ' ' . $user['lastname']; ?></p></div>

        <?php if (isset($sub)) : ?>
            <div><p>Subscription: <?= $sub['name']; ?></p></div>
        <?php else : ?>
            <div><p>Subscription: Pas d'abonnement </p></div>
        <?php endif; ?>

        <form action="user.php?id=<?= $user['id']; ?>" method="post" class="formulaire">
            <div><label for="subscription">Choose a subscription:</label></div>
            <select name="subscription" id="subscription">
                <option value="VIP">VIP</option>
                <option value="GOLD">GOLD</option>
                <option value="Classic">Classic</option>
                <option value="passday">Passday</option>
            </select>
            <button type="submit" name="update_subscription">Update Subscription</button>
        </form>

        <?php if (isset($sub)) : ?>
            <form action="user.php?id=<?= $user['id']; ?>" method="post">
                <div><button type="submit" name="delete_subscription">Delete Subscription</button></div>
            </form>
        <?php endif; ?>


    <?php else : ?>
        <p>User not found or no subscription information available</p>
    <?php endif; ?>

    <div class="form-histo">
    <form action="user.php?id=<?= $user['id']; ?>" method="post"> <br>
    <label for="movie"><div class='p'>Choose a movie:</div></label> <br>
    <select name="movie" id="movie">
        <?php
        $moviesSql = "SELECT * FROM movie 
        JOIN distributor ON movie.id_distributor = distributor.id
        JOIN movie_genre ON movie.id = movie_genre.id_movie
        JOIN genre ON movie_genre.id_genre = genre.id ";
        $moviesResult = $conn->query($moviesSql);

        if ($moviesResult->num_rows > 0) {
            while ($movieRow = $moviesResult->fetch_assoc()) {
                echo "<div><option value=\"{$movieRow['id']}\">{$movieRow['title']}</option></div>";
            }
        }
        ?>
    </select>
    <button type="submit" name="add_to_history">Add to History</button>
</form>
    </div>




    <?php

if ($id > 0) {
    $historySql = "SELECT * FROM user WHERE id = $id";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $historySql = "SELECT title FROM movie JOIN movie_schedule ON movie.id = movie_schedule.id_movie
        JOIN membership_log ON membership_log.id_session = movie_schedule.id 
        JOIN membership ON membership_log.id_membership = membership.id_user 
        JOIN user ON membership.id_user = user.id WHERE membership.id_user = $id";

        $historyResult = $conn->query($historySql);

        if ($historyResult->num_rows > 0) {
            echo '<div>Historique du membre:</div>';
            while ($row = $historyResult->fetch_assoc()) {
                echo '<div class="p">' . $row['title'] . '</div> <br>';
            }
        } else {
            echo '<div>Aucun historique trouvé pour ce membre.</div>';
        }
    }
}

?>
    
</body>
</html>