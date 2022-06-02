<?php
session_start();
if (empty($_SESSION['login'])) {
    header("Location: http://localhost/week_5/login.php");
    die();
}