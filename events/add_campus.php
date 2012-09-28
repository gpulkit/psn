<?php
	require_once('../lib2.php');

	if(isset($_POST['submit']))
	{
		if($_POST['campuses'] != '')
		{
			$success = true;
			$str = mysql_real_escape_string($_POST['campuses']);
			$campuses = explode(",",$str);
			foreach ($campuses as $campus) {
				$campus = trim($campus);
				$result = mysql_query("SELECT * FROM campuses WHERE campus_name = '$campus'");
				$num=mysql_num_rows($result);
				if($num == 0)
				{
					$query = "INSERT INTO campuses (campus_name) VALUES('$campus')";
					//Debug: echo $query;
					$result = mysql_query($query);
					if($result==0) 
					{
						echo mysql_error($conn);
						$success = false;
						break;
					}
				}
			}

			if($success)
				echo '<h2>campuses added!</h2><br/>';
		}

	}
	$result = mysql_query("SELECT *  FROM campuses");
?>
<html>
<head>
	<title>
		Add campuses
	</title>
</head>
<body>
	<h1>
		Fill in the campus names! Fill in the form and hit submit.
	</h1>
	<form method='post' action='add_campus.php' enctype="multipart/form-data">
		<table>	
			<tr>
				<td> List of Existing Campuses: </td>
				<td>
					<select name='list'>
						<?php 
							while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
							{
								$id = $row['campus_id'];
								$name = $row['campus_name'];
								echo "<option value='$id'>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>	
			<tr>
				<td>
					Name of campuses to add:
				</td>
				<td>
					<input type='text' name='campuses'/>
				</td>
			</tr>	
		 	<tr>
		 		<td> <input type='submit' name='submit' value='Add'/> </td>
		 	</tr>
		</table>
	</form>
</body>
</html>
