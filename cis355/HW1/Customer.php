<?php
	include_once "database.php";

	class Customer {

		public $id;
		public $name;
		public $email;
		public $mobile;

		private $nameError;
		private $emailError;
		private $mobileError;

		private $title = "Customer";

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
                        <a href='customerMain.php?fun=1' class='btn btn-success'>Create</a>
                        
                        <a href='https://github.com/jcwarric/cis255/tree/master/cis355/HW1' class='btn btn-info'>Github</a>
                        <a href='eventMain.php' class='btn btn-secondary'>Events</a>
                    </p>

                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Mobile Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM customers ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["name"] . "</td>";
            echo "<td>". $row["email"] . "</td>";
            echo "<td>". $row["mobile"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn' href='customerMain.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-success' href='customerMain.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='customerMain.php?fun=4&id=".$row["id"]."'>Delete</a>";
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
      	  		<form class='form-horizontal' action='customerMain.php?fun=11' method='post'>";
	      	  		$this->control_group("name", $this->nameError, $this->name);
				        $this->control_group("email", $this->emailError, $this->email);
				        $this->control_group("mobile", $this->mobileError, $this->mobile);
								echo " 
		            <div class='form-actions'>
			            <button type='submit' class='btn btn-success'>Create</button>
			            <a class='btn' href='customerMain.php?'>Back</a>
		            </div>
        			</form>
    				</div>

          </div> <!-- /container -->
        </body>
      </html>
                    ";
		}

		//insert a record into the database using the values from the "create a customer" form.
		function insert_record () {
        // validate input
        $valid = true;
        if (empty($this->name)) {
            $this->nameError = 'Please enter a Name';
            $valid = false;
        }
        if (empty($this->email)) {
            $this->emailError = 'Please enter an Email Address';
            $valid = false;
        }

        if (empty($this->mobile)) {
            $this->mobileError = 'Please enter a Mobile Number';
            $valid = false;
        } 


        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO customers (name, email,
            mobile) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name, $this->email,
            		$this->mobile));
            Database::disconnect();
            header("Location: customerMain.php");
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
		  echo "<input name='$label' type='text' placeholder='$label' value='";
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


	  //read an event from the database
	  function read(){

	  	echo "<html>";
	  	//get the id of the event
	  	$id = null;
	  	if(!empty($_GET['id'])){
	  		$id = $_REQUEST['id'];
	  	}

	  	//if the id is null, return to the main page
			if ( null==$id ) {
        header("Location:customerMain.php");
    	} 
	  	//get the event's information from the database
	  	else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM customers where id = ?";
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
					        <h3>Read a Customer</h3>
					    </div>
					     
					    <div class='form-horizontal' >
					      <div class='control-group'>
					        <label class='control-label'>Name</label>
					        <div class='controls'>
					            <label class='checkbox'> " .
					              $data['name'] .
					          	"</label>
					        </div>
					      </div>
					      <div class='control-group'>
					        <label class='control-label'>Email</label>
					        <div class='controls'>
					            <label class='checkbox'>"
					                . $data['email'] . "
					            </label>
					        </div>
					      </div>
					      <div class='control-group'>
					        <label class='control-label'>Mobile</label>
					        <div class='controls'>
					            <label class='checkbox'>
					               ". $data['mobile'] . "
					            </label>
					        </div>
					      </div>
					        <div class='form-actions'>
					          <a class='btn' href='customerMain.php'>Back</a>
					       </div>
					    </div>
					</div>           
			    </div> <!-- /container -->
			  </body>
			</html>";
	  }

	  //form to delete an event
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
                      <h3>Delete a Customer</h3>
                  </div>
                   
                  <form class='form-horizontal' action='customerMain.php?fun=44&id='". $id ."' method='post'>
                    <input type='hidden' name='id' value='". $id ."'/>
                    <p class='alert alert-error'>Are you sure to delete ?</p>
                    <div class='form-actions'>
                        <button type='submit' class='btn btn-danger'>Yes</button>
                        <a class='btn' href='customerMain.php'>No</a>
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
	        $sql = "DELETE FROM customers  WHERE id = ?";
	        $q = $pdo->prepare($sql);
	        $q->execute(array($id));
	        Database::disconnect();
	        header("Location: customerMain.php"); 
	    }
	  }

	  //function for the update form
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
				            <h3>Update a Customer</h3>
				          </div>";
				  echo "<form class='form-horizontal' action='customerMain.php?fun=33&id=" . $id . "' method='post'>";
	      	  	$this->control_group("name", $this->nameError, $this->name);
				      $this->control_group("email", $this->emailError, $this->email);
				      $this->control_group("mobile", $this->mobileError, $this->mobile);
			    echo "<div class='form-actions'>
			            <button type='submit' class='btn btn-success'>Update</button>
			          <a class='btn' href='customerMain.php'>Back</a>
			          </div>
			        </form>
			    </div>         
		    </div> <!-- /container -->
		  </body>
		</html>";

	  }

	  //update the database with the new record's values
	  function update_record(){
	  	    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $emailError = null;
        $mobileError = null;
         
        // keep track post values
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
         
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
         
        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE customers  set name = ?, email = ?, mobile =? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$email,$mobile,$id));
            Database::disconnect();
            header("Location: customerMain.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM customers where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $name = $data['name'];
        $email = $data['email'];
        $mobile = $data['mobile'];
        Database::disconnect();
    }
	   
	  }//end update_record

 	} //end Event
?>
