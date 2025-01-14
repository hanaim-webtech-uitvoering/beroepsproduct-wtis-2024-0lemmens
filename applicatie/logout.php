<?php
require_once 'db_connectie.php';

session_unset();
session_destroy();

header("Location: index.php");
exit;
