<?php

require "database.php";
require "Event.php";
$event = new Event();

if(isset($_POST["event_date"])) $event->event_date = $_POST["event_date"];
if(isset($_POST["event_time"])) $event->event_time = $_POST["event_time"];
if(isset($_POST["event_location"])) $event->event_location = $_POST["event_location"];
if(isset($_POST["event_description"])) $event->event_description = 
    $_POST["event_description"];

if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

switch ($fun) {
    case 1: // create
        $event->create_record();
        break;
    case 2: // read
        $event->read();
        break;
    case 3: // update
        $event->update();
        break;
    case 4: // delete
        $event->delete();
        break;
    case 11: // insert database record from create_record()
        $event->insert_record();
        break;
    case 33: // update database record from update_record()
        $event->update_record();
        break;
    case 44: // delete database record from delete_record()
        $event->delete_record();
        break;
    case 0: // list
    default: // list
        $event->list_records();
}
