<?php
foreach ($_GET as $key => $value) {
    if (empty($_GET[$key])) {
        // header('Location: index.php');
        echo "$key not found";
    }
}

$a       = (object) filter_var_array($_GET, FILTER_SANITIZE_STRING);
$product = $a->product;
$id      = $a->tid;
$link    = $a->receipt;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Awesome</title>
</head>

<body>
    <center>

        <div class="container mt-4">
            <h1 class="text-center">Success, thanks for purchasing <?php echo $product; ?></h1>
            <hr>
            <p>Transaction ID : <?php echo $id ?></p>
            <p>Please make sure to check your email for the receipt or click the link below</p>
            <div class="form-control">
                <p>Receipt Link : <a target="_blank" href="<?php echo $link ?>" class="btn btn-light mt-2">Receipt</a>
                </p>
            </div>
            <a href="index.php" class="btn btn-light mt-2">Back</a>

        </div>
    </center>
</body>

</html>