<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>    <title>Document</title>
</head>
<body>
    <?php include 'components/Navbar.php'; ?>
    <?php include 'components/Hero.php'; ?>
    <?php include 'components/Steps.php'; ?>
    <?php include 'components/Features.php'; ?>
  
    <?php include 'components/Testimonials.php'; ?>
    <?php include 'components/Pricing.php'; ?>
    <?php include 'components/FAQ.php'; ?>
    <?php include 'components/CTA.php'; ?>
    <?php include 'components/Footer.php'; ?>

    <?php include 'components/MailContact.php'; ?>
 
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
