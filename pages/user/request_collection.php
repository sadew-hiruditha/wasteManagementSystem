<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';

use classes\User;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('Location: ../../index.php');
    exit();
}

// Create a User object with the session data
$user = new User(
        $_SESSION['user_firstname'],
        $_SESSION['user_lastname'],
        $_SESSION['user_username'],
        '',
        $_SESSION['user_mobile'],
        $_SESSION['user_street'],
        $_SESSION['user_city'],
        $_SESSION['user_state'],
        $_SESSION['user_postalcode']
);
$user->setId($_SESSION['user_id']);

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
$currentPage = 'request_collection';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Request Collection - GreenPath</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            :root {
                --primary-color: #4CAF50;
                --secondary-color: #45a049;
                --background-color: #f4f9f4;
                --card-color: #ffffff;
                --text-color: #333333;
                --border-color: #e0e0e0;
            }

            body {
                background-color: var(--background-color);
                font-family: 'Roboto', sans-serif;
                color: var(--text-color);
            }

            .content-wrapper {
                background-color: var(--card-color);
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                padding: 40px;
                margin-top: 30px;
            }

            .form-control, .form-select {
                border-radius: 10px;
                padding: 15px 20px;
                border: 2px solid var(--border-color);
                transition: all 0.3s ease;
                font-size: 16px;
            }

            .form-control:focus, .form-select:focus {
                box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.3);
                border-color: var(--primary-color);
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                border-radius: 10px;
                padding: 12px 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                font-size: 18px;
            }

            .btn-primary:hover {
                background-color: var(--secondary-color);
                border-color: var(--secondary-color);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            }

            .waste-type-selector {
                display: flex;
                gap: 15px;
                margin-bottom: 20px;
                overflow-x: auto;
                padding: 5px;
           
              
            }

            .waste-type-option {
                flex: 1 0 auto;
                width: calc(33.333% - 10px);
                min-width: 100px;
                max-width: 150px;
                text-align: center;
                padding: 15px 10px;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.3s ease;
 
                border: 2px solid transparent;

            }

            .waste-type-option:hover {
                transform: translateY(-3px);
                box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            }


            .waste-type-option i {
                font-size: 3rem;
                margin-bottom: 15px;
            }

            .waste-type-option p {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 600;
            }

            .waste-type-option.selected {
/*                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
                transform: scale(1.05);*/
                border-color: var(--primary-color);
            }

            #general-waste {
                background-color: #FFF3E0;
                color: #FF9800;
            }

            #recyclable-waste {
                background-color: #E1F5FE;
                color: #03A9F4;
            }

            #organic-waste {
                background-color: #E8F5E9;
                color: #4CAF50;
            }

            .form-label {
                font-weight: 600;
                color: var(--text-color);
                margin-bottom: 10px;
                font-size: 18px;
            }

            .icon-input {
                position: relative;
            }

            .icon-input i {
                position: absolute;
                left: 15px;
                top: 100%;
                transform: translateY(-50%);
                color: var(--primary-color);
                font-size: 20px;
            }

            .icon-input input,
            .icon-input textarea {
                padding-left: 50px;
            }

            @media (max-width: 768px) {
                .waste-type-option {
                    flex: 1 0 calc(50% - 20px);
                }
            }

            @media (max-width: 480px) {
                .waste-type-option {
                    flex: 1 0 100%;
                }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <?php include 'nav.php'; ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="content-wrapper">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                            <h1 class="h2"><i class="fas fa-recycle me-2" style="color: var(--primary-color);"></i>Request Collection</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="userDashboard.php" style="color: var(--primary-color);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Request Collection</li>
                                </ol>
                            </nav>
                        </div>

                        <?php if ($message): ?>
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i><?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form action="process_request.php" method="post" class="mt-4">
                            <div class="mb-4">
                                <label class="form-label">Select Waste Type</label>
                                <div class="waste-type-selector">
                                    <div class="waste-type-option" id="general-waste" onclick="selectWasteType('General')">
                                        <i class="fas fa-trash-alt"></i>
                                        <p>General</p>
                                    </div>
                                    <div class="waste-type-option" id="recyclable-waste" onclick="selectWasteType('Recyclable')">
                                        <i class="fas fa-recycle"></i>
                                        <p>Recyclable</p>
                                    </div>
                                    <div class="waste-type-option" id="organic-waste" onclick="selectWasteType('Organic')">
                                        <i class="fas fa-leaf"></i>
                                        <p>Organic</p>
                                    </div>
                                </div>
                                <input type="hidden" id="waste_type" name="waste_type" required>
                            </div>

                            <div class="mb-4 icon-input">
                                <i class="fas fa-weight"></i>
                                <label class="form-label" for="quantity">Quantity (in kg)</label>

                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                            </div>

                            <div class="mb-4 icon-input">
                                <label class="form-label" for="preferred_date">Preferred Collection Date</label>
                                <i class="far fa-calendar-alt"></i>
                                <input type="text" class="form-control" id="preferred_date" name="preferred_date" required>
                            </div>

                            <div class="mb-4 icon-input">
                                <label class="form-label" for="notes">Additional Notes</label>
                                <i class="fas fa-sticky-note"></i>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions or details?"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit Request
                            </button>
                        </form>
                    </div>
                </main>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
                                        // Toggle sidebar on mobile
                                        $(".navbar-toggler").click(function () {
                                            $("#sidebar").toggleClass("show");
                                        });

                                        // Close sidebar when clicking outside on mobile
                                        $(document).click(function (event) {
                                            if (!$(event.target).closest('#sidebar, .navbar-toggler').length) {
                                                $("#sidebar").removeClass("show");
                                            }
                                        });

                                        function selectWasteType(type) {
                                            document.getElementById('waste_type').value = type;
                                            document.querySelectorAll('.waste-type-option').forEach(el => el.classList.remove('selected'));
                                            document.getElementById(type.toLowerCase() + '-waste').classList.add('selected');
                                        }

                                        document.querySelectorAll('.waste-type-option').forEach(option => {
                                            option.addEventListener('click', function () {
                                                const type = this.id.replace('-waste', '');
                                                selectWasteType(type.charAt(0).toUpperCase() + type.slice(1));
                                            });
                                        });

                                        document.querySelector('form').addEventListener('submit', function (e) {
                                            const wasteType = document.getElementById('waste_type').value;
                                            if (!wasteType) {
                                                e.preventDefault();
                                                alert('Please select a waste type');
                                            }
                                        });

                                        document.addEventListener('DOMContentLoaded', function () {
                                            flatpickr("#preferred_date", {
                                                dateFormat: "Y-m-d",
                                                minDate: "today",
                                                maxDate: new Date().fp_incr(30) // Allow booking up to 30 days in advance
                                            });
                                        });
        </script>
    </body>
</html>