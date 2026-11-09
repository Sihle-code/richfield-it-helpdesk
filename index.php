
<?php
require 'inc/bootstrap.php';

if (user()) {
    go('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richfield IT Help Desk Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7fb;
            color: #243247;
            font-family: Arial, sans-serif;
        }
        .hero {
            background: #12345a;
            color: white;
            padding: 90px 20px;
            text-align: center;
        }
        .hero h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            height: 100%;
        }
        .btn-access {
            background: #f0a52b;
            color: #172b45;
            font-weight: bold;
            padding: 13px 28px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-access:hover {
            background: #d99019;
            color: #172b45;
        }
        footer {
            background: #12345a;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">Richfield IT Help Desk</span>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <h1>IT Help Desk Management System</h1>
        <p class="lead">
            A centralised platform for managing technical support requests,
            tracking issues and improving communication.
        </p>
        <a href="login.php" class="btn-access">Access Help Desk System</a>
    </div>
</section>

<main class="container py-5">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="content-card">
                <h3>About This Project</h3>
                <p>
                    This system was developed as an academic Information
                    Technology project for Richfield Graduate Institute
                    of Technology.
                </p>
                <p>
                    Its purpose is to demonstrate how an IT help desk
                    can manage support requests through a structured
                    digital platform.
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card">
                <h3>System Features</h3>
                <ul>
                    <li>Submit and track support tickets.</li>
                    <li>Assign tickets to technicians.</li>
                    <li>Monitor ticket progress and status.</li>
                    <li>Communicate through ticket updates.</li>
                    <li>Access knowledge base articles.</li>
                    <li>Manage users and support reports.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h4>Need technical assistance?</h4>
        <p>Access the Help Desk system to submit or manage a support request.</p>
        <a href="login.php" class="btn-access">Go to Login</a>
    </div>
</main>

<footer>
    <p class="mb-0">
        Academic IT Project | Richfield Graduate Institute of Technology
    </p>
</footer>

</body>
</html>
