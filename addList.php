<?php
    include ("connection.php");
    session_start();

    $listName = $_GET['listName'];


    $nameQuery = "SELECT * FROM users WHERE username = '{$_SESSION['currUser']}'";
    $nameResult = mysqli_query($conn, $nameQuery);
    $nameRow = mysqli_fetch_array($nameResult, MYSQLI_ASSOC);

    // Insert the list name into the database
    $insertQuery = "INSERT INTO `lists` (`listID`, `userID`, `listName`, `dateCreated`) VALUES (NULL, '{$nameRow['userID']}', '$listName' , NOW())";
    mysqli_query($conn, $insertQuery);



    $username = $_SESSION['currUser'];
    $sql = "SELECT *
            FROM users
            INNER JOIN lists ON users.userID = lists.userID
            WHERE users.username = '$username';";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 0) {
        echo "<p style = 'text-align:center'> No lists yet. </p>";
    } else {
        while ($data = $result->fetch_assoc()) {
            echo "<div class='horContainer'>";
                echo "<div class='perListContainer'>";
                    echo "<div class='listNameVerContainer'>";
                        // List Title
                        echo "<p onclick='openList({$data['listID']})' class='listName'> {$data['listName']} </p>";
                        echo "<input type='hidden' name='listID' value='{$data['listID']}'>";


                        $itemNumQuery = "SELECT COUNT(*) AS itemCount
                                        FROM items
                                        WHERE listID = '{$data['listID']}';";
                        $itemNumResult = mysqli_query($conn, $itemNumQuery);

                        if ($itemNumResult) {
                            $itemNumData = mysqli_fetch_assoc($itemNumResult);
                            $itemCount = $itemNumData['itemCount'];
                            echo "<p class='listItemCount'> $itemCount items </p>";
                        } else {
                            echo "Error fetching item count";
                        }

                        echo "<img class='more' src='resources/icons/more.png'>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        }
    }
//Add new list Option
    echo "<div class='horContainer'>";
        echo "<div class='newTaskContainer'>";
            echo "<div class='horContainer'>";
                echo "<p onclick = 'openAddList()' class='newTask'> new list+ </p>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
?>