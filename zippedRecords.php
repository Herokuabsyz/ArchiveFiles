<!DOCTYPE html>
<html>
<head>
	<title>Status Page</title>
	<link rel="stylesheet" type="test/css">
</head>
<style>
table{
  width: 100%;
  padding: 12px 16px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
  box-sizing: border-box;
  font-weight: bold;
}
th {
  width: 100%;
  padding: 10px 14px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 18px;
  box-sizing: border-box;
  font-weight: bold;
  color: #ff0080;
}
td{
  width: 100%;
  padding: 10px 14px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  box-sizing: border-box;
  color: #808080;
}
tr{
  width: 100%;
  background-color: #ffffff;
  color: #808080;
  padding: 10px 16px;
  margin: 8px 0;
  border: none;
  font-size: 14px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.button {
  background-color: #ffffff;
  border: none;
  color: #ff0080;
  padding: 15px 22px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}
.btn:hover {
  background-color: #3e8e41;
  color: white;
}
</style>
<body style="background:#ff0080; font-family: Arial; color: #ffffff">
	<div id="frm" style="width: 40%; border-radius: 5px; margin: 50px auto; padding: 50px;">
	<form>
		<table>
			<tr>
				<th>Zipped Records</th>
				<th>Date</th>
				<th>Status</th>
			</tr>
			<tr>
				<td>Zip1</td>
				<td>20th July</td>
				<td>Success!</td>
			</tr>
			<tr>
				<td>Zip1</td>
				<td>20th July</td>
				<td>Success!</td>
			</tr>
			<tr>
				<td>Zip1</td>
				<td>20th July</td>
				<td>Failure!</td>
			</tr>
			<tr>
				<td>Zip1</td>
				<td>20th July</td>
				<td>Failure!</td>
			</tr>
		</table>
	    <p>
			<input type="button" class="button" value="Zip Now!">
		</p>
	</form>
	</div>
</body>
</html>