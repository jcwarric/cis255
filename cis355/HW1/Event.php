<?php
	include_once "database.php";

	class Event {

		public $id;
		public $event_date;
		public $event_time;
		public $event_location;
		public $event_description;

		private $dateError;
		private $timeError;
		private $locationError;
		private $descriptionError;

		private $title = "Event";

		//lists the records in the table
		function list_records() {
        echo "
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
                <div class='container'>
                    <p class='row'>
                        <h3>$this->title" . "s" . "</h3>
                    </p>
                    <p>
                        <a href='eventMain.php?fun=1' class='btn btn-success'>Create</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM events ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["event_date"] . "</td>";
            echo "<td>". $row["event_time"] . "</td>";
            echo "<td>". $row["event_location"] . "</td>";
            echo "<td>". $row["event_description"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn' href='eventMain.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-success' href='eventMain.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='eventMain.php?fun=4&id=".$row["id"]."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        Database::disconnect();        
        echo "
			            </tbody>
			          </table>
			        </div>
			      </div>
			    </body>
			  </html>";  

  } // end list_records()
    

		//displays the create record form
		function create_record(){
			echo " 
				<html>
					<head>
						<title>Create a $this->title </title>";
			echo "
						<meta charset='UTF-8'>
	          <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
	          <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>";
      echo "
      	  </head>
      	  <body>
      	  <div class='container'>
      	  	<div class='span10 offset1'>
      	  		<p class='row'>
      	  			<h3>Create a $this->title </h3>
      	  		</p>
      	  		<form class='form-horizontal' action='eventMain.php?fun=11' method='post'>";
	      	  		$this->control_group("date", $this->dateError, $this->event_date);
				        $this->control_group("time", $this->timeError, $this->event_time);
				        $this->control_group("location", $this->locationError, $this->event_location);
				        $this->control_group("description", $this->descriptionError, $this->event_description);
			echo " 
		            <div class='form-actions'>
			            <button type='submit' class='btn btn-success'>Create</button>
			            <a class='btn' href='eventMain.php?'>Back</a>
		            </div>
        			</form>
    				</div>

          </div> <!-- /container -->
        </body>
      </html>
                    ";
		}

		function insert_record () {
        // validate input
        $valid = true;
        if (empty($this->event_date)) {
            $this->dateError = 'Please enter Date';
            $valid = false;
        }
        if (empty($this->event_time)) {
            $this->timeError = 'Please enter Time';
            $valid = false;
        }

        if (empty($this->event_location)) {
            $this->locationError = 'Please enter Location';
            $valid = false;
        } 

        if (empty($this->event_description)) {
            $this->descriptionError = 'Please enter Description';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO events (event_date, event_time,
            event_location, event_description) values(?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->event_date, $this->event_time,
            		$this->event_location, $this->event_description));
            Database::disconnect();
            header("Location: eventMain.php");
        }
        else {
            $this->create_record();
        }
    }

		function control_group ($label, $labelError, $val) {
		  echo "<div class='control-group";
		  echo !empty($labelError) ? 'error' : '';
		  echo "'>";
		  echo "<label class='control-label'>$label</label>";
		  echo "<div class='controls'>";
		  echo "<input name='event_$label' type='text' placeholder='$label' value='";
		  echo !empty($val) ? $val : '';
		  echo "'>";
		  if (!empty($labelError)) {
		      echo "<span class='help-inline'>";
		      echo $labelError;
		      echo "</span>";
		  }
		  echo "</div>";
		  echo "</div>";
	  }


	  //read an event
	  function read(){

	  	echo "<html>";
	  	//get the id of the event
	  	$id = null;
	  	if(!empty($_GET['id'])){
	  		$id = $_REQUEST['id'];
	  	}

	  	//if the id is null, return to the main page
			if ( null==$id ) {
        header("Location:eventMain.php");
    	} 
	  	//get the event's information from the database
	  	else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM events where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
    	}


    	echo "<html lang='en'>
<head>
    <meta charset='utf-8'>
    <link   href='css/bootstrap.min.css' rel='stylesheet'>
    <script src='js/bootstrap.min.js'></script>
</head>
 
<body>
    <div class='container'>
		  <div class='span10 offset1'>
		    <div class='row'>
		        <h3>Read an Event</h3>
		    </div>
		     
		    <div class='form-horizontal' >
		      <div class='control-group'>
		        <label class='control-label'>Date</label>
		        <div class='controls'>
		            <label class='checkbox'> " .
		              $data['event_date'] .
		          	"</label>
		        </div>
		      </div>
		      <div class='control-group'>
		        <label class='control-label'>Time</label>
		        <div class='controls'>
		            <label class='checkbox'>"
		                . $data['event_time'] . "
		            </label>
		        </div>
		      </div>
		      <div class='control-group'>
		        <label class='control-label'>Location</label>
		        <div class='controls'>
		            <label class='checkbox'>
		               ". $data['event_location'] . "
		            </label>
		        </div>
		      </div>
		       <div class='control-group'>
		        <label class='control-label'>Description</label>
		        <div class='controls'>
		            <label class='checkbox'>
		                " . $data['event_description'] . "
		            </label>
		        </div>
		      </div>
		        <div class='form-actions'>
		          <a class='btn' href='eventMain.php'>Back</a>
		       </div>
		     
		      
		    </div>
		</div>           
    </div> <!-- /container -->";

	  }

	  //delete event
	  function delete(){
	  	$id = 0;
     
	    if ( !empty($_GET['id'])) {
	        $id = $_REQUEST['id'];
	    }
	     
	    echo "
	    <html lang='en'>
				<head>
				    <meta charset='utf-8'>
				    <link   href='css/bootstrap.min.css' rel='stylesheet'>
				    <script src='js/bootstrap.min.js'></script>
				</head>
				 
				<body>
				    <div class='container'>
              <div class='span10 offset1'>
                  <div class='row'>
                      <h3>Delete an Event</h3>
                  </div>
                   
                  <form class='form-horizontal' action='eventMain.php?fun=44&id='". $id ."' method='post'>
                    <input type='hidden' name='id' value='". $id ."'/>
                    <p class='alert alert-error'>Are you sure to delete ?</p>
                    <div class='form-actions'>
                        <button type='submit' class='btn btn-danger'>Yes</button>
                        <a class='btn' href='eventMain.php'>No</a>
                      </div>
                  </form>
              </div>         
				    </div> <!-- /container -->
				  </body>
				</html>";
	  }

	  function delete_record(){
	  	$id = 0;
     
	    if ( !empty($_GET['id'])) {
	        $id = $_REQUEST['id'];
	    }
	     
	    if ( !empty($_POST)) {
	        // keep track post values
	        $id = $_POST['id'];
	         
	        // delete data
	        $pdo = Database::connect();
	        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $sql = "DELETE FROM events  WHERE id = ?";
	        $q = $pdo->prepare($sql);
	        $q->execute(array($id));
	        Database::disconnect();
	        header("Location: eventMain.php"); 
	    }
	  }

	  function update(){
	  	$id = 0;
	    if ( !empty($_GET['id'])) {
	        $id = $_REQUEST['id'];
	    }
	  	echo "<html lang='en'>
						<head>
						  <meta charset='utf-8'>
						  <link   href='css/bootstrap.min.css' rel='stylesheet'>
						  <script src='js/bootstrap.min.js'></script>
						</head>";
			echo "<body>
							<div class='container'>
				        <div class='span10 offset1'>
				          <div class='row'>
				            <h3>Update a Event</h3>
				          </div>";
				  echo "<form class='form-horizontal' action='eventMain.php?fun=33&id=" . $id . "' method='post'>";
	      	  	$this->control_group("date", $this->dateError, $this->event_date);
				      $this->control_group("time", $this->timeError, $this->event_time);
				      $this->control_group("location", $this->locationError, $this->event_location);
				      $this->control_group("description", $this->descriptionError, $this->event_description);
			    echo "<div class='form-actions'>
			            <button type='submit' class='btn btn-success'>Update</button>
			          <a class='btn' href='eventMain.php'>Back</a>
			          </div>
			        </form>
			    </div>         
		    </div> <!-- /container -->
		  </body>
		</html>";

	  }

	  function update_record(){
	  	 $id = null;
	    if ( !empty($_GET['id'])) {
	        $id = $_REQUEST['id'];
	    }
	     
	    if ( null==$id ) {
	        header("Location: mainEvent.php");
	    }
	    var_dump($_POST);
	     
	    if ( !empty($_POST)) {
	        // keep track validation errors
	        $this->nameError = null;
	        $this->timeError = null;
	        $this->locationError = null;
	        $this->descriptionError = null;
	         
	        // keep track post values
	        $this->event_date = $_POST['event_date'];
	        $this->event_time = $_POST['event_time'];
	        $this->event_location = $_POST['event_location'];
	        $this->event_description = $_POST['event_description'];
	         
	        // validate input
	        $valid = true;
	        if (empty($this->event_date)) {
	            $this->dateError = 'Please enter a date.';
	            $valid = false;
	        }
	         
	        if (empty($this->event_time)) {
	            $this->timeError = 'Please enter a time.';
	            $valid = false;
	        }
	         
	        if (empty($this->event_location)) {
	            $this->locationError = 'Please enter a location';
	            $valid = false;
	        }
	         
	        if (empty($this->event_description)) {
	            $this->descriptionError = 'Please enter a description';
	            $valid = false;
	        }
	        var_dump($valid);
	        // update data
	        if ($valid) {
	            $pdo = Database::connect();
	            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	            $sql = "UPDATE events  set event_date = ?, event_time = ?, event_location =?, event_description=? WHERE id = ?";
	            $q = $pdo->prepare($sql);
	            $q->execute(array($this->event_date,$this->event_time,$this->event_location,$this->event_description, $id));
	            Database::disconnect();
	            header("Location: eventMain.php");
	        }
	    	 else {
			    	echo "update";
						header("Location: eventMain.php?fun=3&id=" . $id);
			    }
	    }
	  }//end update_record

 	} //end Event
?>
