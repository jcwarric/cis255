<?php

require "database.php";
require "Customer.php";
$customer = new Customer();

if(isset($_POST["name"])) $customer->name = $_POST["name"];
if(isset($_POST["email"])) $customer->email = $_POST["email"];
if(isset($_POST["mobile"])) $customer->mobile = $_POST["mobile"];

if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

switch ($fun) {
    case 1: // create
        $customer->create_record();
        break;
    case 2: // read
        $customer->read();
        break;
    case 3: // update
        $customer->update();
        break;
    case 4: // delete
        $customer->delete();
        break;
    case 11: // insert database record from create_record()
        $customer->insert_record();
        break;
    case 33: // update database record from update_record()
        $customer->update_record();
        break;
    case 44: // delete database record from delete_record()
        $customer->delete_record();
        break;
    case 0: // list
    default: // list
        $customer->list_records();
}
