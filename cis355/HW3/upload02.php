<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {margin:20px 20px 50px 50px;}
    </style>
</head>
</html>
<?php
    // see HTML form (upload02.html) for overview of this program

    // include code for database access
    require 'database.php';

    // set PHP variables from data in HTML form 
    $fileName       = $_FILES['Filename']['name'];
    $tempFileName   = $_FILES['Filename']['tmp_name'];
    $fileSize       = $_FILES['Filename']['size'];
    $fileType       = $_FILES['Filename']['type'];
    $fileDescription = $_POST['Description']; 

    // set server location (subdirectory) to store uploaded files
    $fileLocation = "uploads\\";
    $fileFullPath = $fileLocation . $fileName; 

    if (!file_exists($fileLocation))
        mkdir ($fileLocation); // create subdirectory, if necessary

    // execute debugging code...
    // echo phpinfo(); exit(); // to see location of php.ini
    // note: can't set php.ini:file_uploads on the fly
    // echo ini_set('file_uploads', '1'); // "set" does not work
    // echo ini_get('file_uploads'); // "get" does work
    // echo "<pre>"; print_r(ini_get_all()); echo "</pre>"; exit();
    // echo "<pre>"; print_r($_FILES); echo "</pre>"; exit(); 

    // connect to database
    $pdo = Database::connect();

    // exit, if requested file already exists -- in the database table 
    $fileExists = false;
    $sql = "SELECT filename FROM upload02 WHERE filename='$fileName'";
    foreach ($pdo->query($sql) as $row) {
        if ($row['filename'] == $fileName) {
            $fileExists = true;
        }
    }
    if ($fileExists) {
        echo "File <html><b><i>" . $fileName 
            . "</i></b></html> already exists in DB. Please rename file.";
        exit(); 
    }

    // exit, if requested file already exists -- in the subdirectory 
    if(file_exists($fileFullPath)) {
        echo "File <html><b><i>" . $fileName 
            . "</i></b></html> already exists in file system, "
            . "but not in database table. Cannot upload.";
        exit(); 
    }

    // if all of above is okay, then upload the file
    $result = move_uploaded_file($tempFileName, $fileFullPath);

    // //get absolute path of image
    $absolutePath = getcwd();
    $absolutePath = $absolutePath . '\\' . $fileFullPath;
     $imageFullPath = str_replace("\\", "\\\\", $absolutePath);
     //echo $imageFullPath ;

    // if upload was successful, then add a record to the SQL database
    if ($result) {
        echo "Your file <html><b><i>" . $fileName 
            . "</i></b></html> has been successfully uploaded";
        // echo "It is located at: " . $imageFullPath . "<br/>";
        $sql = "INSERT INTO upload02(filename,filetype,filesize,filepath,description)"
            . " VALUES ('$fileName','$fileType','$fileSize',"
            . "'$imageFullPath', '$fileDescription');";
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $q = $pdo->prepare($sql);
        $q->execute(array());
    // otherwise, report error
    } else {
        echo "Upload denied for this file. Verify file size < 2MB. ";
    }

    // list all files in database 
    // ORDER BY BINARY filename ASC (sorts case-sensitive, like Linux)
    echo '<br><br>All files in database...<br><br>';
    $sql = 'SELECT * FROM upload02 ' 
        . 'ORDER BY BINARY filename ASC;';

    //put images, their descriptions, and their paths into a table
    echo "<table class='table table-striped'>";
    echo '<thead>';
        echo '<tr>';
            echo "<th scope='col'>Image</th>";
            echo "<th scope='col'>Description</th>";
            echo "<th scope='col'>File Path</th>";
        echo '</tr>';
    echo '<thead>';
    echo '<tbody>';
    $i = 0; 
    foreach ($pdo->query($sql) as $row) {
        echo '<tr>';
            echo "<td><img src='uploads/" . $row['filename'] . "' class='img-responsive' width='200px'> </td>";
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['filepath'] . '</td>';
            echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<br><br>';

    // list all files in subdirectory
    echo 'All files in subdirectory...<br>';
    echo '<pre>';
    $arr = array_slice(scandir("$fileLocation"),2);
    asort($arr);
    print_r($arr);
    echo '<pre>';
    echo '<br><br>';

    // disconnect
    Database::disconnect(); 
?>