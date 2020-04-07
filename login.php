<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="test/css">
</head>
<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 20px;
  box-sizing: border-box;
  font-weight: bold;
  color: #000;
}
input[type=password], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 20px;
  box-sizing: border-box;
  font-weight: bold;
  color: #000;
}
input[type=submit] {
  width: 100%;
  background-color: #ffffff;
  color: #000000;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  font-size: 20px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

input[type=submit]:hover {
  background-color: #ffa31a;
}

</style>
<body style="background:#66ff99; font-family: Arial;">
<h1 style="color: #000000"><center>Login</center></h1>
	<div id="frm" style="border: solid gray 1px; width: 20%; border-radius: 5px; margin: 100px auto; padding: 50px; background: #000000; opacity: 0.5; filter: alpha(opacity=50);">
		<form action="process.php" method="POST" style="font-weight: bold;">
			<p>
				<label style="color:#ffffff">Username: </label> <input type="text" id="user" name="user" />
			</p>
			<p>
				<label style="color:#ffffff">Password: </label> <input type="password" id="pass" name="pass" />
			</p>
			<p>
				<input type="submit" id="btn" name="Login" />
			</p>
		</form>
	</div>
</body>
</html>