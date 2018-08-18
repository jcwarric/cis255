<?php

printForm(); 

#-----------------------------------------------------------------------------
// display the entry form for course search
function printForm(){
	
    echo '<br />';
    echo '<br />';
    echo '<h2>Course Lookup</h2>';

    // print user entry form
    echo "<form action='course2.php'>";
    echo "Course Prefix (Department)<br/>";
    echo "<input type='text' placeholder='CS' name='prefix'><br/>";
    echo "Course Number<br/>";
    echo "<input type='text' placeholder='116' name='courseNumber'><br/>";
    echo "Instructor<br/>";
    echo "<input type='text' placeholder='gpcorser' name='instructor'><br/>";
    echo "<select name='day'>";
    echo "<option value=''>(None)</option>";
    echo "<option value='M'>Monday</option>";
    echo "<option value='MW'>Monday & Wednesday</option>";
    echo "<option value='T'>Tuesday</option>";
    echo "<option value='TR'>Tuesday & Thursday</option>";
    echo "<option value='W'>Wednesday</option>";
    echo "</select><br>";
    //echo "Building/Room<br/>";
    //echo "<input type='text' name='building'>";
    //echo "<input type='text' name='room'><br/>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
}
