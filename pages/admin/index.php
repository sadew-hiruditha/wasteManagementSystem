<?php
session_start();

if (isset($_SESSION["user_id"])) {

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <title>Admin Dashboard</title>
    </head>
   

    <body>
    <div class="container mt-5">
        <h2>Welcome to the Admin Dashboard <span class="name"> <?= $_SESSION["user_firstname"] . " " . $_SESSION["user_lastname"] ?></span> </h2>

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
                <tr>
                    <td>2</td>
                    <td>User 2</td>
                    <td>Lastname 2</td>
                    <td>user2@example.com</td>
                    <td>Driver</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>

        <!-- Manage Routes Section -->
        <h3>Manage Routes</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Route ID</th>
                    <th>Route Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Route 1</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Route 2</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>

        <!-- Assign Routes Section -->
        <h3>Assign Routes</h3>
        <form action="assign_route.php" method="POST">
            <div class="mb-3">
                <label for="driver" class="form-label">Select Driver</label>
                <select class="form-select" id="driver" name="driver" required>
                    <option value="Driver 1">Driver 1</option>
                    <option value="Driver 2">Driver 2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="route" class="form-label">Select Route</label>
                <select class="form-select" id="route" name="route" required>
                    <option value="Route 1">Route 1</option>
                    <option value="Route 2">Route 2</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
        <hr>

        <!-- View Pickup History Section -->
        <h3>Pickup History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Route</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-06-17</td>
                    <td>Driver 1</td>
                    <td>Route 1</td>
                    <td>Completed</td>
                </tr>
                <tr>
                    <td>2024-06-18</td>
                    <td>Driver 2</td>
                    <td>Route 2</td>
                    <td>Pending</td>
                </tr>
            </tbody>
        </table>
        <hr>

        <!-- Sign Out Button -->
        <a href="sign_out.php" class="btn btn-danger">Sign Out</a>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

    </html>
<?php
} else {
    header("Location:../../");
}
