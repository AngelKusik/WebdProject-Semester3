<?php
    /*
    * Name: Angelica Kusik
    * Date: September 21, 2022
    * Course: Webd 3203-01
    */
    
    $file = "sign-in.php";
    $date = "September 21, 2022";
    $title = "Sing-in Page";
    $description = "The sing-in page is the landing page of this application, where we autenticate the user's information in order to allow access to the dashboard and index pages.";

    //Include the header file
    include "./includes/header.php";

    $today = date("Ymd");
    $now = date("Y-m-d G:i:s");
    $handle = fopen("./logs/".$today."_log.txt", 'a');
    $error_message = "";
    $email_address = "";

    //Before requiring the user to login, check if the user is already on the session (aka validated already)
    if(isset($_SESSION['user']))
    {
        //If so, call the redirect_user funtion and pass in the dashboard url and redirect the user 
        //without requesting the email and password again.
        redirect_user('dashboard.php');
    }

    //declare a variable to hold the user information recorded on the database
    $user = "";


    if($_SERVER['REQUEST_METHOD']=='POST'){
        $email_address = trim($_POST['inputEmail']);
        $password = trim($_POST['inputPassword']);

        //validate email address

        //Before checking the database for a record of the email, check if the email was entered
        if (!isset($email_address) || strlen($email_address) == 0)
        {
            $error_message .= "You must enter your email address.<br/>";
            $email_address = "";
        }

        if(strlen($error_message) == 0) 
        {
            //Next check if a password was entered
            if (!isset($password) || strlen($password) == 0)
            {
                $error_message .= "You must enter your password.<br/>";
                $password = "";
            }
            
            //If there is no error message carry on
            if(strlen($error_message) == 0)
            {
                //Check if email and password entered by the user are valid
                //If so return the user's records from the database, otherwise, return false
                $user = user_authenticate($password, $email_address);

                //If the user records were successfully returned, carry on
                if($user){  
                    //Before transfering user to dashboard:
                    //1) Add autenticated user to the session
                    $_SESSION['user'] = $user;
                    //dump($user);

                    //2) Update lastAccess timestamp on the database
                    user_update_login_time($now, $email_address);

                    //3) Add a welcome message to the session (will be used on the dasboard)
                    //validate if the last access exist, if the lastaccess is empty then only display message welcome back $user
                    if(isset($user['lastaccess']))
                    {
                        set_message("Welcome back " .$user['firstname']. "! Your last login was at " .$user['lastaccess']);
                    }
                    else 
                    {
                        set_message("Welcome back " .$user['firstname']. "!");
                    }
                    
                    //4) Register on the logs the successfull login info
                    fwrite($handle, "Sign in success at ".$today.". User " .$email_address. " sign in.\n");

                    //5)Close the log file
                    fclose($handle);
                    
                    //Now transfer user to dashboard page using the redirect_user function
                    redirect_user('dashboard.php');

                    //And flush the buffer
                    ob_flush();      
                }
                else 
                {
                    //If user could not be verified, calls the handle_failed_login function to register unsuccessfull login attempt in the logs
                    //and create an error message
                    $error_message = "Email or password are not valid";
                    handle_failed_login($handle, $now, $email_address, $error_message);
                }
            }
            else
            {
                //If user could not be verified, calls the handle_failed_login function to register unsuccessfull login attempt in the logs
                //and create an error message
                handle_failed_login($handle, $now, $email_address, $error_message);
            }
        }
        else
        {
            //If user could not be verified, calls the handle_failed_login function to register unsuccessfull login attempt in the logs
            //and create an error message
            handle_failed_login($handle, $now, $email_address, $error_message);
         }

    }

?> 

<form class="form-signin my-5" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <?php 
    if(is_message())
    {
        echo "<h2>" .get_message() . "</h2>";
    }
    ?>
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" name="inputEmail"  value="<?php echo $email_address; ?>" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<?php
include "./includes/footer.php";
?>    