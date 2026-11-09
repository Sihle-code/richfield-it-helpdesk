<?php require 'inc/bootstrap.php'; if(user())audit('logout'); session_unset(); session_destroy(); session_start(); flash('You have been logged out.'); go('login.php');
