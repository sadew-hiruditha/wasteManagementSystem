<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GreenPath - Smart Waste Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <style>
            :root {
                --primary-color: #2ecc71;
                --secondary-color: #27ae60;
            }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .navbar {
                background-color: rgba(255, 255, 255, 0.9);
                box-shadow: 0 2px 4px rgba(0,0,0,.1);
            }
            .navbar-brand {
                color: var(--primary-color) !important;
                font-weight: bold;
            }
            .nav-link {
                color: #333 !important;
            }
            .btn-custom {
                background-color: var(--secondary-color);
                border-color: var(--secondary-color);
                color: white;
                transition: all 0.3s ease;
            }
            .btn-custom:hover {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                transform: translateY(-2px);
            }
            .hero {
                background:url('images/login.jpg');
                background-size: cover;
                background-position: center;
                color: white;
                padding: 100px 0;
            }
            .feature-icon {
                font-size: 3rem;
                color: var(--primary-color);
            }
            .card {
                border: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(0,0,0,.1);
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0,0,0,.1);
            }
            .how-to-use {
                background-color: #f8f9fa;
            }
            .step-number {
                background-color: var(--primary-color);
                color: white;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: inline-flex;
                justify-content: center;
                align-items: center;
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">GreenPath</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#how-to-use">How to Use</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="MainLogin.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-custom ms-2" href="sign_up.php">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="hero">
            <div class="container text-center">
                <h1 class="display-4 mb-4">Smart Waste Management for a Greener Future</h1>
                <p class="lead mb-5">Join GreenPath to revolutionize waste management in your community</p>
                <a href="sign_up.php" class="btn btn-light btn-lg">Get Started</a>
            </div>
        </header>

        <main>
            <section id="features" class="py-5">
                <div class="container">
                    <h2 class="text-center mb-5">Our Features</h2>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 text-center p-4">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-list-alt"></i>
                                </div>
                                <h5 class="card-title">User Waste Request Management</h5>
                                <p class="card-text">Users can list their waste requests based on categories, quantities, and additional notes. Requests can be edited, changed, and viewed on their dashboard.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 text-center p-4">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <h5 class="card-title">Driver Request Handling</h5>
                                <p class="card-text">Drivers can view and manage user requests categorized by user names. They can change the status of requests to pending, accepted, or completed.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 text-center p-4">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <h5 class="card-title">Dashboard and Analytics</h5>
                                <p class="card-text">Users and drivers have access to a dashboard to view and manage requests, track statuses, and access real-time data and analytics for informed decision-making.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <section id="how-to-use" class="how-to-use py-5">
                <div class="container">
                    <h2 class="text-center mb-5">How to Use GreenPath</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">1</span>Sign Up</h5>
                            <p>Create your GreenPath account to access our waste management tools and features.</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">2</span>Set Up Your Profile</h5>
                            <p>Customize your profile with your information.</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">3</span>List Your Waste Request</h5>
                            <p>Submit your waste requests based on categories, quantities, and additional notes.</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">4</span>Manage and Edit Requests</h5>
                            <p>View, edit, and manage your listed waste requests through your dashboard.</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">5</span>Track Request Status</h5>
                            <p>Monitor the status of your waste requests in real-time.</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5><span class="step-number">6</span>Analyze Performance</h5>
                            <p>Access real-time data and analytics to evaluate and improve your waste management strategies.</p>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <footer class="bg-dark text-light py-4">
            <div class="container text-center">
                <p>&copy; 2024 GreenPath Waste Management System. All rights reserved.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>