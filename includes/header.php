<!doctype html>
<html lang="en">
  <head>
  <?php
    
    /* Name: Angelica Kusik
    * Date: September, 21, 2022
    * Course: WEBD 3201      
    */ 

    /* Start a session */
    session_start();
    /* Start output buffer which allows redirect between pages without any errors */
    ob_start();
    /* Includes */
    require ("includes\contants.php");
    require ("includes\db.php");
    require ("includes/functions.php");

    //Check the session for messages
    //if there is a message, save the message content into the variable, otherwise set the variable to blank
    if (is_message())
    {
        $message = get_message();
        //after saving the content of the message on a variable, remove it from the session
        remove_message();
    }
    else
    {
        $message = "";
    }

    dump($message);


    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <!--
	Author: Angelica Kusik
	Filename: <?php echo $file . "\n"; ?>
	Date: <?php echo $date . "\n"; ?>
    Page Title: <?php echo $title . "\n"; ?>
	Description: <?php echo $description . "\n" ?>
	-->

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/styles.css" rel="stylesheet">
	
  </head>
  <body>
    <!--Using the session, check if user is validated, and if so, display the navbar and and navigation column -->
    <?php
        if(isset($_SESSION['user']))
        {
            echo 
            '<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
                <a class="navbar-brand col-sm-3 col-md-2 mr-0 text-white" href="#">
                '.$message.'
                </a>
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="logout.php">Sign out</a>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
            <div class="row">
                
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <span data-feather="file"></span>
                            Home (index)
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                        </li>
        
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file"></span>
                            Orders
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="shopping-cart"></span>
                            Products
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="users"></span>
                            Customers
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="bar-chart-2"></span>
                            Reports
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="layers"></span>
                            Integrations
                        </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Saved reports</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                        <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Current month
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Last quarter
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Social engagement
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Year-end sale
                        </a>
                        </li>
                    </ul>
                    </div>
                </nav>
                <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-5 border-bottom">';
        }
        else if (!(isset($_SESSION['user']['type'])&&($_SESSION['user']['type']==ADMIN)))
        {
            echo 
            '<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
                <a class="navbar-brand col-sm-3 col-md-2 mr-0 text-white" href="#">
                '.$message.'
                </a>
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="logout.php">Sign out</a>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
            <div class="row">
                
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <span data-feather="file"></span>
                            Home (index)
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" href="salesperson.php">
                            <span data-feather="register salesperson"></span>
                            Sales Person Registration
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file"></span>
                            Orders
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="shopping-cart"></span>
                            Products
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="users"></span>
                            Customers
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="bar-chart-2"></span>
                            Reports
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="layers"></span>
                            Integrations
                        </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Saved reports</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                        <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Current month
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Last quarter
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Social engagement
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Year-end sale
                        </a>
                        </li>
                    </ul>
                    </div>
                </nav>
                <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-5 border-bottom">';

        }
        else
        {
            echo 
                '<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
                    <a class="navbar-brand col-sm-3 col-md-2 mr-0 text-white" href="#">
                        Company Name
                    </a>
                    <ul class="navbar-nav px-3">
                        <li class="nav-item text-nowrap">
                            <a class="nav-link" href="sign-in.php">Sign in</a>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                <div class="row">
                    <main class="col-md-8 offset-md-2 col-lg-10 offset-lg-1 pt-3 px-4 pb-5 my-5">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">';
        }

    ?> <!--End of echo -->
