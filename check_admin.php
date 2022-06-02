<?php
session_start();
if (empty($_SESSION['role'])) {
    header('HTTP/1.1 403 Forbidden');
    die("You are forbidden!");
}