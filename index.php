<?php
/*
 * Name: Angelica Kusik
 * Date: September 21, 2022
 * Course: Webd 3203-01
 */

$file = "index.php";
$date = "September 21, 2022";
$title = "Home Page";
$description = "index.php is the home page of this website";

include "./includes/header.php";

?>

<h1 class="cover-heading mr-auto"><?php echo $title; ?></h1>
<h2><?php echo $message; ?></h2>
<p class="lead">Cover is a one-page template for building simple and beautiful home pages. Download, edit the text, and add your own fullscreen background photo to make it your own.</p>
<p class="lead">
    <a href="#" class="btn btn-lg btn-secondary">Learn more</a>
</p>

<?php
include "./includes/footer.php";
?>    