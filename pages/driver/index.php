<?php
session_start();

if(isset($_SESSION["user_id"])){
    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
   

<body>
    <div class="container mt-5">
        <!-- Driver Profile Section -->
        <h2>Welcome to the Driver Dashboard <span class="name"><?=$_SESSION["user_firstname"]." ".$_SESSION["user_lastname"]?></span> </h2>
        <hr>

        <hr>

<!-- Manage Users Section -->
<h3>Manage Users</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>User 1</td>
            <td>Lastname 1</td>
            <td>user1@example.com</td>
            <td>User</td>
            <td>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>
        <!-- Add more user rows as needed -->
    </tbody>
</table>

<hr>

<!-- Manage Drivers Section -->
<h3>Manage Drivers</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Driver 1</td>
            <td>Lastname 1</td>
            <td>driver1@example.com</td>
            <td>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>
        <!-- Add more driver rows as needed -->
    </tbody>
</table>

<hr>

<!-- Manage Waste Collection Section -->
<h3>Manage Waste Collection</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Date</th>
            <th>Driver</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Location 1</td>
            <td>2024-01-01</td>
            <td>Driver 1</td>
            <td>Completed</td>
            <td>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>
        <!-- Add more collection rows as needed -->
    </tbody>
</table>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
}else{
    header("Location:../../");
}