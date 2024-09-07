<?php 
session_start(); 
include("config.php"); 
if(!isset($_SESSION['AdminID'])){ 
    header('location:login.html'); 
} 
?>