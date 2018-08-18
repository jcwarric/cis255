<?php

/*
  filename 	: courses.php
  author   	: george corser
  course   	: cis355 (winter2016)
  description	: print fomatted output from JSON object 
                  returned by SVSU Courses API
  input    	: api.svsu.edu/courses
 */

// suppress notices
ini_set('error_reporting', 
    E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); 

main();

#-----------------------------------------------------------------------------
# FUNCTIONS
#-----------------------------------------------------------------------------

function main() {

    // echo html head section
    echo '<html>';
    echo '<head>';
    echo '	<link rel="icon" href="img/cardinal_logo.png" type="image/png" />';
    echo '<link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>';
    echo '</head>';

    // open html body section
    echo '<body>';

    // in html body section, if gpcorser's schedule, then print gpcorser's heading, else print general CS/CIS/CSIS heading
    if (!strcmp($_GET['instructor'], 'gpcorser')) {
        echo '<h1 align="center">George Corser, PhD</h1>';
        echo '<h2 align="center">CURRENT COURSES</h2>';
    } else {
        echo '<h1 align="center">SVSU/CSIS Department</h1>';
        echo '<h2 align="center">';
        echo $_GET['prefix'] ? ' - Prefix: ' . strtoupper($_GET['prefix']) : "";
        echo $_GET['courseNumber'] ? ' - Course Number: ' . $_GET['courseNumber'] : "";
        echo $_GET['instructor'] ? ' - Instructor: ' . strtoupper($_GET['instructor']) : "";
        echo '</h2>';
    }

    // if user entered something in a search box, then call printCourses() to filter 
    if ($_GET['prefix'] != "" || $_GET['courseNumber'] != "" || $_GET['instructor'] != "") {
        printCourses($_GET['prefix'], $_GET['courseNumber'], $_GET['instructor']);
    }
    else if ($_GET['day']){
        switch ($_GET['day']){
            case "M":
                echo "<h3>Monday</h3>";
            break;
            case "T":
                echo "<h3>Tuesday</h3>";
            break;
            case "W":
                echo "<h3>Wednesday</h3>";
            break;
            case "TR":
                echo "<h3>Tuesday & Thursday</h3>";
            break;
            case "MW":
                echo "<h3>Monday & Wednesday</h3>";
            break;
        }

        printDays($_GET['day']);
    }
    // otherwise call printSemester() for all courses for each semester
    else {
        // echo "<h3>Spring</h3>";
        // printSemester("18/SP");
        // echo "<h3>Summer</h3>";
        // printSemester("18/SU");
        // echo "<h3>Fall</h3>";
        // printSemester("18/FA");
        // echo "<h3>Winter</h3>";
        // printSemester("19/WI");
        echo "<h3>Monday</h3>";
        printDays("M");
        echo "<br/><br/>";
        echo "<h3>Monday & Wednesday</h3>";
        printDays("MW");
        echo "<br/><br/>";
        echo "<h3>Tuesday</h3>";
        printDays("T");
        echo "<br/><br/>";
        echo "<h3>Tuesday & Thursday</h3>";
        printDays("TR");
        echo "<br/><br/>";
        echo "<h3> Online</h3>";
        printOnlineCourses();
        echo "<br/><br/>";

    }

    // display the entry form for next search
    //printForm(); 
    // display button for course search
    echo '<a href="coursesearch.php" class="btn btn-primary">Search</a>';
    echo '<a href="https://github.com/jcwarric/cis255/tree/master/cis355/HW4" class="btn btn-info">GitHub</a>';

    // close html body section
    echo '</body>';
    echo '</html>';
}

#-----------------------------------------------------------------------------
// display the entry form for next search

function printForm() {

    echo '<br />';
    echo '<br />';
    echo '<h2>Course Lookup</h2>';

    // print user entry form
    echo "<form action='courses.php'>";
    echo "Course Prefix (Department)<br/>";
    echo "<input type='text' placeholder='CS' name='prefix'><br/>";
    echo "Course Number<br/>";
    echo "<input type='text' placeholder='116' name='courseNumber'><br/>";
    echo "Instructor<br/>";
    echo "<input type='text' placeholder='gpcorser' name='instructor'><br/>";
    //echo "Building/Room<br/>";
    //echo "<input type='text' name='building'>";
    //echo "<input type='text' name='room'><br/>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
}

#-----------------------------------------------------------------------------
// print all courses for a given filter

function printCourses($prefix, $courseNumber, $instructor) {

    // call printListing() for each semester using all parameters
    /*
      $term = "18/WI";
      $string ="https://api.svsu.edu/courses?prefix=$prefix&courseNumber=$courseNumber&term=$term&instructor=$instructor";
      echo "<h3>2018 - Winter</h3>";
      printListing($string);
     */

    $term = "18/SP";
    $string = "https://api.svsu.edu/courses?prefix=$prefix&courseNumber=$courseNumber&term=$term&instructor=$instructor";
    echo "<h3>2018 - Spring</h3>";
    printListing($string);

    $term = "18/SU";
    $string = "https://api.svsu.edu/courses?prefix=$prefix&courseNumber=$courseNumber&term=$term&instructor=$instructor";
    echo "<h3>2018 - Summer</h3>";
    printListing($string);

    $term = "18/FA";
    $string = "https://api.svsu.edu/courses?prefix=$prefix&courseNumber=$courseNumber&term=$term&instructor=$instructor";
    echo "<h3>2018 - Fall</h3>";
    printListing($string);

    $term = "19/WI";
    $string = "https://api.svsu.edu/courses?prefix=$prefix&courseNumber=$courseNumber&term=$term&instructor=$instructor";
    echo "<h3>2019 - Winter</h3>";
    printListing($string);
}

#-----------------------------------------------------------------------------
// print all CS/CIS/CSIS courses for a given semester (term)

function printSemester($term) {

    // note: printSemester() is only called when user has not entered anything in entry form
    // print all CIS courses for semester
    $string = "https://api.svsu.edu/courses?prefix=CIS&term=$term";
    printListing($string);

    // print all CS courses for semester
    $string = "https://api.svsu.edu/courses?prefix=CS&term=$term";
    printListing($string);

    // print all CSIS courses for semester
    $string = "https://api.svsu.edu/courses?prefix=CSIS&term=$term";
    printListing($string);


}

//-----------------------------------------------------------------------
//prints the CIS courses that occur on the given day
function printDays($dayAbbrev){
    $string = "https://api.svsu.edu/courses?prefix=CIS";
    $json = curl_get_contents($string);
    $obj = json_decode($json);
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead>";
    echo "<tr>";
        echo "<th>Course </th>";
        echo "<th>Title </th>";
        //echo "<th>Available Seats</th>";
        echo "<th>Days and Times</th>";
        echo "<th>Location </th>" ;
        echo "<th>Instructor </th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($obj->courses as $course) {
        foreach ($course->meetingTimes as $times) {
            if(array_key_exists("days", $times)) {
                if(!strcmp($times->days, $dayAbbrev)) {
                    
                    echo "<tr>";
                    //course abbreviation and number
                    echo "<td>" . $course->prefix . " "
                        . $course->courseNumber . "*"
                        . $course->lineNumber . "</td>";
                    //course title
                    echo "<td>" . $course->title . "</td>";

                    //available seats
                    // if(array_key_exists("seatsAvailable", $course)) {
                    // echo "<td>" . $course->$seatsAvailable . "(" 
                    //     . $course->$capacity . ")" . "</td>"; 
                    // } 
                    // else{
                    //     echo "<td><td/>"; 
                    // }

                    //days and times
                    echo "<td>" . $times->days . " "
                        . $times->startTime . " - " . $times->endTime
                        . "</td>" ;
                    
                    //location
                    if(array_key_exists("building", $times)){
                        echo "<td>" . $times->building . 
                         $times->room . "</td>";
                    }
                    else{
                        echo "<td><td/>"; 
                    }
                    //instructor

                    echo "<td>" . $times->instructor . "</td>";
                    echo "</tr>";
                }
            }
        }
    }//end for each
    echo "</tbody></table>";

}


function printOnlineCourses(){
    $string = "https://api.svsu.edu/courses?prefix=CIS";
    $json = curl_get_contents($string);
    $obj = json_decode($json);
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead>";
    echo "<tr>";
        echo "<th>Course </th>";
        echo "<th>Title </th>";
        //echo "<th>Available Seats</th>";
        echo "<th>Days and Times</th>";
        echo "<th>Location </th>" ;
        echo "<th>Instructor </th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($obj->courses as $course) {
        foreach ($course->meetingTimes as $times) {
            //if the class doesn't have a day, its online
            if(!array_key_exists("days", $times)) {
                if(!strcmp($times->days, $dayAbbrev)) {

                    echo "<tr>";
                    //course abbreviation and number
                    echo "<td>" . $course->prefix . " "
                        . $course->courseNumber . "*"
                        . $course->lineNumber . "</td>";
                    //course title
                    echo "<td>" . $course->title . "</td>";

                    //available seats
                    // if(array_key_exists("seatsAvailable", $course)) {
                    // echo "<td>" . $course->$seatsAvailable . "(" 
                    //     . $course->$capacity . ")" . "</td>"; 
                    // } 
                    // else{
                    //     echo "<td><td/>"; 
                    // }

                    //online classes don't have days and times
                    echo "<td></td>" ;
                    
                    //location
                    echo "<td>Online</td>";
                   
                    //instructor
                    echo "<td>" . $times->instructor . "</td>";
                    echo "</tr>";
                }
            }
        }
    }//end for each
    echo "</tbody></table>";
}
#-----------------------------------------------------------------------------
// print an html table for one single query of the api

function printListing($apiCall) {

    $json = curl_get_contents($apiCall);
    $obj = json_decode($json);

    if (!($obj->courses == null)) {

        echo "<table border='3' width='100%'>";

        foreach ($obj->courses as $course) {

            $building = strtoupper(trim($_GET['building']));
            $buildingMatch = false;
            $thisBuilding0 = trim($course->meetingTimes[0]->building);
            $thisBuilding1 = trim($course->meetingTimes[1]->building);
            if ($building && ($thisBuilding0 == $building || $thisBuilding1 == $building))
                $buildingMatch = true;
            if (!($building))
                $buildingMatch = true;
            if (!$buildingMatch)
                continue;

            $room = strtoupper(trim($_GET['room']));
            $roomMatch = false;
            $thisroom0 = trim($course->meetingTimes[0]->room);
            $thisroom1 = trim($course->meetingTimes[1]->room);
            if ($room && ($thisroom0 == $room || $thisroom1 == $room))
                $roomMatch = true;
            if (!($room))
                $roomMatch = true;
            if (!$roomMatch)
                continue;

            // different <tr bgcolor=...> for each professor
            switch ($course->meetingTimes[0]->instructor) {
                case "james":            // 1
                    $printline = "<tr bgcolor='#B19CD9'>";  // pastel purple
                    break;
                case "icho":             // 2
                    $printline = "<tr bgcolor='lightblue'>";  // light blue
                    break;
                case "krahman":           // 3 
                    $printline = "<tr bgcolor='pink'>";  // pink
                    break;
                case "gpcorser":           // 4
                    $printline = "<tr bgcolor='yellow'>";   // yellow
                    break;
                case "pdharam":           // 5
                    $printline = "<tr bgcolor='#77DD77'>";  // pastel green (light green)
                    break;
                case "amulahuw":           // 6
                    $printline = "<tr bgcolor='#FFB347'>";  // pastel orange
                    break;
                default:
                    $printline = "<tr>"; // no background color
            }

            $printline .= "<td width='13%'>" . $course->prefix . " " . $course->courseNumber . "*" . $course->section . "</td>";
            $printline .= "<td width='40%'>" . $course->title . " (" . $course->lineNumber . ")" . "</td>";
            $printline .= "<td width='12%'>Av:" . $course->seatsAvailable . " (" . $course->capacity . ")" . "</td>";

            // print day and time column
            if ($course->meetingTimes[0]->days) {
                $printline .= "<td width='15%'>" . $course->meetingTimes[0]->days . " " . $course->meetingTimes[0]->startTime;
                // $printline .= . "<br /> " . $course->meetingTimes[1]->days . " " . $course->meetingTimes[1]->startTime ;
                $printline .= "</td>";
            } else {
                $printline .= "<td width='15%'>";
                $printline .= $course->meetingTimes[1]->days . " " . $course->meetingTimes[1]->startTime . "</td> ";
            }

            // print building and room column
            $printline .= "<td width='10%'>";
            if (substr($course->section, -2, 1) == "9")
                $printline .= "(Online)";
            else
            if (substr($course->section, -2, 1) == "7")
                $printline .= $course->meetingTimes[1]->building . " " . $course->meetingTimes[1]->room;
            else
                $printline .= $course->meetingTimes[0]->building . " " . $course->meetingTimes[0]->room;
            $printline .= "</td>";

            // print instructor column
            $printline .= "<td width='10%'>" . $course->meetingTimes[0]->instructor . "</td>";
            $printline .= "</tr>";
            echo $printline;
        } // end foreach

        echo "</table>";
        echo "<br/>";
    } // end if (!($obj->courses == null))
    else {
        echo "No courses fit search criteria";
        echo "<br />";
    }
}

#-----------------------------------------------------------------------------
// read file into a string

function curl_get_contents($url) {

    // alternative to file_get_contents

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
