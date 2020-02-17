<?php
session_start();
unset($_SESSION['resend_subject']);
unset($_SESSION['resend_body']);
unset($_SESSION['resend_attach']);
unset($_SESSION['resend_id']);
unset($_SESSION['sender_address_reply']);
header("location: Abu_Mail_Compose_Step1.php");
?>