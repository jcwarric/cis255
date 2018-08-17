<?php

// see HTML form (upload01.html) for overview of this program

require_once ('functions01.php');
if(!empty($_POST)){
	// set PHP variables from data in HTML form 
	$fileName       = $_FILES['Filename']['name'];
	$tempFileName   = $_FILES['Filename']['tmp_name'];
	$fileSize       = $_FILES['Filename']['size'];
	$fileType       = $_FILES['Filename']['type'];
	// $fileDescription = $_POST['Description']; // not used

	// set server location (subdirectory) to store uploaded files
	$fileLocation = "uploads/";
	$fileFullPath = $fileLocation . $fileName; 
	if (!file_exists($fileLocation))
	    mkdir ($fileLocation); // create subdirectory, if necessary

	// debugging code...
	// echo phpinfo(); exit(); // to see location of php.ini
	// note: can't set php.ini:file_uploads on the fly
	// echo ini_set('file_uploads', '1'); // "set" does not work
	// echo ini_get('file_uploads'); // "get" does work
	// echo "<pre>"; print_r(ini_get_all()); echo "</pre>"; exit();
	// echo "<pre>"; print_r($_FILES); echo "</pre>"; exit(); // view $_FILES array

	// if file does not already exist, upload it
	if (!file_exists($fileFullPath)) {
	    $result = move_uploaded_file($tempFileName, $fileFullPath);
	    if ($result) {
	        echo "File <b><i>" . $fileName 
	            . "</i></b> has been successfully uploaded.";
	        // code below assumes filepath is same as filename of this file
	        // minus the 12 characters of this file, "upload01.php"
	        // plus the string, $fileLocation, i.e. "uploads/"
	        echo "<br>To see all uploaded files, visit: " 
	                . "<a href='"
	                . substr(get_current_url(), 0, -12)
	                . "$fileLocation'>" 
	                . substr(get_current_url(), 0, -12) 
	                . "$fileLocation</a>";
	    } else {
	        echo "Upload denied for file. " . $fileName 
	            . "</i></b>. Verify file size < 2MB. ";
	    }
	}
	// otherwise, show error message
	else {
	    echo "File <b><i>" . $fileName 
	        . "</i></b> already exists. Please rename file.";
	} 
}
?>

<!DOCTYPE html>
<!--            ****************************************
File:           upload01.html
Description:    Simple PHP file upload, without MySQL. 
                See also: upload01.php.
Source:         Code modified from: 
                http://www.lionblogger.com/how-to-upload-file-to-server-using-php-save-the-path-in-mysql/
                **************************************** 
-->
<html>
    
    <head>
        <title>Upload01</title>
        <meta charset="UTF-8">
        <meta name="viewport" 
              content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/11CE0A59-69C8-C94B-8A21-18E8DEFC87BC/main.js" charset="UTF-8"></script></head>
    
    <body>
        
        <h1>(1) Upload a file to a server subdirectory</h1>
        <p>This form will perform a simple upload of any file, 
            as long as the file is smaller than 2MB. </p>
        <form method="post" action="upload01.php" 
              enctype="multipart/form-data">
            <p>File</p>
            <input type="file" 
                name="Filename"> 
            <p>Description</p>
            <textarea rows="10" cols="35" 
                name="Description" disabled></textarea>
            <br/>
            <input TYPE="submit" name="upload" value="Submit"/>
        </form>
        <button>
        <div class="form-actions">
					<a class="btn" href="index.php">Back</a>
				</div>
    </body>
    
</html>

