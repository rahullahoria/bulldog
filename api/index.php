<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';


//usage resource
require_once "resources/usage/saveUsage.php";
require_once "resources/usage/getFile.php";
require_once "resources/companies/managers/getManagerEmployees.php";
require_once "resources/companies/managers/employees/getEmployee.php";

//app
require_once "app.php";





?>