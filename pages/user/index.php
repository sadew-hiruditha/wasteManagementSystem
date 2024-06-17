<?php
session_start();

if(isset($_SESSION["user_id"])){
    

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../../css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <title></title>
    </head>
    <body>
        
      
        <div class="container mt-5">
        <!-- User Profile Section -->
        <h1>Welcome<span class="name"> <?=$_SESSION["user_firstname"]." ".$_SESSION["user_lastname"]?></span> </h1>
        
        <hr>

        <!-- Collection Schedule Section -->
        <h3>Collection Schedule</h3>
        <ul class="list-group">
            <li class="list-group-item">Schedule Date 1</li>
            <li class="list-group-item">Schedule Date 2</li>
            <li class="list-group-item">Schedule Date 3</li>
        </ul>
        <hr>

        <!-- Request Pickup Section -->
        <h3>Request Pickup</h3>
        <form action="request_pickup.php" method="POST">
            <div class="mb-3">
                <label for="pickup_date" class="form-label">Pickup Date</label>
                <input type="date" class="form-control" id="pickup_date" name="pickup_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Request Pickup</button>
        </form>
        <hr>

        <!-- Pickup History Section -->
        <h3>Pickup History</h3>
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Date:</strong> Pickup Date 1
                <strong>Status:</strong> Status 1
            </li>
            <li class="list-group-item">
                <strong>Date:</strong> Pickup Date 2
                <strong>Status:</strong> Status 2
            </li>
        </ul>
        <hr>

        <!-- Sign Out Button -->
        <a href="sign_out.php" class="btn btn-danger">Sign Out</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
}else{
    header("Location:../../");
}
    