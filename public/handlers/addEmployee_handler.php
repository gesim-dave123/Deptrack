<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Call the backend logic located outside public
require_once "../../app/add_employee.php";
