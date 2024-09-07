<?php 
session_start(); 
include("config.php"); 
if(!isset($_SESSION['TechnicianID'])){ 
    header('location:login.html'); 
} 
?>