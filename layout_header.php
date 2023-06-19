<?php

/**
 * HTML Header template
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

if(isset($_SESSION['user_id'])){
    include_once dirname(__FILE__).'/user_preferences/instantiate_user_preferences.php';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>UnlockED</title>

    <!-- favicon stuff -->

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#8BC34A">
    
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#8BC34A">
    <!-- /favicon stuff -->

    <!-- Global stylesheets -->
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">--> <!-- if needed, uncomment when product lives on AWS -->
    <!--link href="<?php echo FONTSSDIR; ?>/roboto/roboto.php?weight=400,300,100,500,700,900" rel="stylesheet" type="text/css"--> <!-- overrides overall font-weight and disallows for limitless font-weight classes -->
    <link href="<?php echo LIBSDIR; ?>/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo LIBSDIR; ?>/icons/material/styles.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/icons/ultimate-icons/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/global_assets/css/extras/animate.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <link href="<?php echo LIBSDIR; ?>/custom-styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/gamification.css" rel="stylesheet" type="text/css">
</head>

<body class="navbar-top <?php if(isset($_SESSION['user_id'])){ echo $userSidebar;} ?>">
