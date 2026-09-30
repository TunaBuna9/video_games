<?php
$inven_code = '';
$game_name = '';
$console = '';
$price = '';
$isValidCode = '';
$isValidPrice = '';
$game_image = '';
$filename = '';

$errors = [];
$game_list = [];


// Consoles
$consolesList = [
  'pc',
  'playstation4',
  'xbox360',
  'wii',
  '3ds',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validation + reterival
  $inven_code = strtoupper(trim(filter_input(INPUT_POST, 'inven_code') ?? ''));
  $game_name = trim(filter_input(INPUT_POST, 'game_name') ?? '');
  $console = trim(filter_input(INPUT_POST, 'console') ?? '');
  $price = trim(filter_input(INPUT_POST, 'price') ?? '');

  // Image upload and transfering
  $uploadedFile  = $_FILES['game_image'];
  $filename = basename($uploadedFile['name']);

  // Adds uni code to the first half, so duplicate images can exist

  if ($filename != '') {
    $filename = time() . $filename;
  }


  echo ($filename);
  $destination = __DIR__ . '/uploads/' . $filename;
  move_uploaded_file(
    $uploadedFile['tmp_name'],
    $destination
  );

  // __Error messaging__
  // Invetory Code + match
  if (!$inven_code) {
    $errors['inven_code'] = 'Please enter a game code.';
  } else {
    $idPattern = "/^GAME-\d{4}$/";
    $isValidCode = preg_match($idPattern, $inven_code);
  };

  if ($isValidCode === 0) {
    $errors['inven_code'] = 'Please use the format: GAME-1234.';
  };

  // Games Name errors
  if (!$game_name) {
    $errors['game_name'] = 'Please enter a game title.';
  };

  // Console list errors
  if (!in_array($console, $consolesList, true)) {
    $errors['console'] = 'Please select a game console.';
  }

  // Price errors + matches
  if (!$price) {
    $errors['price'] = 'Please enter a price.';
  } elseif ($price <= 0) {
    $errors['price'] = 'Price must be greater than zero';
  } else {
    $pricePattern = "/\d{1}.\d{2,2}$/";
    $isValidPrice = preg_match($pricePattern, $price);
  }

  if ($isValidPrice === 0) {
    $errors['price'] = 'Please use the format: 20.00';
  };

  // No Image error
  if (!$filename) {
    $errors['game_image'] = 'Please select a preview image.';
  };

  if (!$errors) {
    $new = [
      $inven_code,
      $game_name,
      $console,
      $price,
      $filename
    ];

    $game_list = [$new];

    $database = "games/games.csv";

    $file = fopen($database, 'a');
    if ($file === false) {
      die("Error opening the file" . $filename);
    }

    foreach ($game_list as $row) {
      fputcsv($file, $row);
    }

    fclose($file);
  }
};

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'includes/links.php' ?>
  <title>Starry Sky's Games - Home</title>
</head>

<body>
  <?php include 'includes/navigation.php' ?>

  <div class="container-fluid my-1 text-center">
    <h1 class="mt-4">Find your next game today! The sky's the limit</h1>

    <div class="container text-center mb-5">
      <div class="row">
        <div class="col-sm-12 col-md-6 my-3">
          <div class="card h-100">
            <h5 class="card-header">About Us</h5>
            <img src="images/pixelartStars.jpg" class="card-img-top" height = "50%" alt="Blue and Purple pixel art of the glaxay.">

            <div class="card-body">

              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-6 my-3">
          <div class="card h-100">
            <h5 class="card-header">Featured</h5>
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container text-center">
      <div class="col-sm-12 text-start">
        <div class="card mb-5 ">
          <h2 class="card-header text-center p-4">Share Your Game Today!</h2>
          <form method="post" action="index.php" enctype="multipart/form-data" novalidate >
            <div class="card-body">
              <p class="card-text">
              <p class="text-center">Fill out the form below to add your game to our severs!</p>

              <!-- ID Code -->
              <label class="formLabel" for="inven_code"> Inventory Code: </label>
              <input
                class="form-control  <?= isset($errors['inven_code']) ? 'is-invalid' : '' ?>"
                type="text"
                id="inven_code"
                name="inven_code"
                placeholder="GAME-1234"
                value="<?= htmlspecialchars($inven_code); ?>">

              <?php if (isset($errors['inven_code'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['inven_code']) ?></div>
              <?php endif; ?>
              <br>

              <!-- Game Name -->
              <label class="formLabel" for="game_name"> Game Name: </label>
              <input
                class="form-control <?= isset($errors['game_name']) ? 'is-invalid' : '' ?>"
                type="text"
                id="game_name"
                name="game_name"
                placeholder="Rage Racers"
                value="<?= htmlspecialchars($game_name); ?>">

              <?php if (isset($errors['game_name'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['game_name']) ?></div>
              <?php endif; ?>
              <br>

              <!-- Console Drop down -->
              <label class="formLabel" for="console">Console Type: </label>
              <select class="form-select  <?= isset($errors['console']) ? 'is-invalid' : '' ?>" name="console" id="console" value="<?= htmlspecialchars($console); ?>">

                <option value="">Choose a console</option>

                <option value="pc" <?= $console === 'pc' ? 'selected' : '' ?>>
                  Pc
                </option>

                <option value="playstation4" <?= $console === 'playstation4' ? 'selected' : '' ?>>
                  Playstation 4
                </option>

                <option value="xbox360" <?= $console === 'xbox360' ? 'selected' : '' ?>>
                  Xbox 360
                </option>

                <option value="wii" <?= $console === 'wii' ? 'selected' : '' ?>>
                  Wii
                </option>

                <option value="3ds" <?= $console === '3ds' ? 'selected' : '' ?>>
                  3DS
                </option>

              </select>

              <?php if (isset($errors['console'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['console']) ?></div>
              <?php endif; ?>
              <br>

              <!-- Price -->
              <label class="formLabel" for="price"> Price (USD): </label>
              <input
                class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>"
                type="text"
                id="price"
                name="price"
                placeholder="$25.00"
                value="<?= htmlspecialchars($price); ?>">

              <?php if (isset($errors['price'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['price']) ?></div>
              <?php endif; ?>
              <br>

              <!-- Game image -->
              <label for="game_image">Upload an image preview of your game: </label>
              <input class="form-control <?= isset($errors['game_image']) ? 'is-invalid' : '' ?>" type="file" name="game_image" id="game_image">

            </div>

            <?php if ($filename): ?>
              <img
                src="uploads/<?= htmlspecialchars($filename) ?>"
                alt="Uploaded image"
                width="200">

              <p>Your image has been uploaded</p>
            <?php else: ?>
              <p>No image has been uploaded.</p>
            <?php endif; ?>


            <div class="card-footer text-center">
              <button class="btn btn-primary btn-lg" type="submit">
                Add Game
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>

  </div>
</body>

</html>