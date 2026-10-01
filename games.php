<?php

$file = fopen("games/games.csv", 'r');
$list = array();
$list2 = [];

while (!feof($file)) {
    $list = fgetcsv($file);
    if ($list === false) continue;
    $list2[] = $list;
};

fclose($file);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/links.php' ?>

    <title>Starry Sky's Games - Games</title>
</head>

<body>
    <?php include 'includes/navigation.php' ?>
    <h1 class="title mt-4">Available games</h1>

    <div class="container-custom text-center mb-5 ">
        <div class="row width-100">
            <div class="col my-3">
                <div class="card">
                    <h5 class="card-header p-3">Check out our recently added games below!</h5>

                    <div class="card-body">

                        <p class="card-text">
                            <?php
                            if (!$list2) { ?>
                        <p>Looks like we have nothing new to show you.<br>Check back in later or upload your own game to get it highlighted here!</p>
                    <?php } else { ?>
                        <table>
                            <tr>
                                <th>Game ID</th>
                                <th>Game Name</th>
                                <th>Console Type</th>
                                <th>Price</th>
                                <th>Preview</th>
                            </tr>

                            <?php foreach ($list2 as $item) { ?>
                                <tr>
                                    <td> <?php echo (htmlspecialchars("$item[0]")); ?> </td>

                                    <td> <?php echo (htmlspecialchars("$item[1]")); ?> </td>

                                    <td> <?php echo ucfirst(htmlspecialchars("$item[2]")); ?> </td>

                                    <td> <?php echo (htmlspecialchars("$" . "$item[3]")); ?> </td>

                                    <td class="pre-img">
                                        <a target="_blank" href="uploads/<?php echo (htmlspecialchars("$item[4]")); ?>">
                                            <img src="uploads/<?php echo (htmlspecialchars("$item[4]")); ?>" alt="Uploaded image">

                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>

                        </p>
                        <p class="text-secondary">Click photos to see full details</p>
                    </div>

                <?php } ?>




                </div>
            </div>
</body>

</html>