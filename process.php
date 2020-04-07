<!DOCTYPE html>
<html>
<head>
	<title>Status Page</title>
	<link rel="stylesheet" type="test/css">
</head>
<body style="background:#ff0080; font-family: Arial; color: #ffffff">
<?php

	//include ("DBConnection.class.php"); //the path of the connection file
	
	//$DBConnection = new DBConnection();
	
	class DBConnection
	{
		var $conn;
		//your passowrd is changed to "password"
		//use this file for connection
		
		function DBConnection()
		{
			$databaseName    = $GLOBALS['configuration']['login'];
			$serverName      = $GLOBALS['configuration']['localhost'];
			$databaseUser    = $GLOBALS['configuration']['postgres'];
			$databasePassword= $GLOBALS['configuration']['password'];
			$databasePort    = $GLOBALS['configuration']['5432'];
			//$this->conn = pg_connect("host='localhost' post='5432' dbname='login' user='postgres' password='password'") or die("unable to connect to database!!");
			$this->conn=mysql_connect($serverName.":".$databasePort,$databaseUser,$databasePassword) or die("unable to connect to database!!");
			if($this->conn)
			{
				if(!mysql_select_db($databaseName))
				{
                     throw new Exception('Cannot find: "'.$databaseName.'"');
                }
			}
			else
			{
					throw new Exception('Cannot connect to the database.');
			}
		}
		
	}
	
	//html form
	
	
	
	//Get values pass from form in login.php file
	$username = $_POST['user'];
	$password = $_POST['pass'];
	
	// to prevent mysql injection
	/*$username = stripcslashes($username);
	$password = stripcslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	
	//connect to the server and select database
	mysql_connect("localhost","root","");
	mysql_select_db("login");
	
	//Query the database for user
	$result = mysql_query("select * from users where username = '$username' and password = '$password'") 
	          or die("Failed to query database".mysql_error());
	$row = mysql_fetch_array($result);
	if($row['username'] == $username && $row['password'] == $password)
	{
		echo "<h1><center>Login success!!!  Welcome...</center></h1>",$row['username'];
	}
	else
	{
		echo "<h1><center>Failed to login!!</center></h1>";
	}*/
	
?>
</body>
</html>