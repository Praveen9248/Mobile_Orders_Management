<?php 
session_start(); 
include("config.php"); 
if(!isset($_SESSION['UserID'])){ 
    header('location:login.html'); 
} 
?>