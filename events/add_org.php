<?php
	require_once('../lib2.php');

	if(isset($_POST['submit']))
	{
		if($_POST['orgs'] != '')
		{
			$success = true;
			$str = mysql_real_escape_string($_POST['orgs']);
			$campus_id = mysql_real_escape_string($_POST['list']);
			$orgs = explode(",",$str);
			foreach ($orgs as $org) {
				$org = trim($org);
				$result = mysql_query("SELECT * FROM orgs WHERE org_name = '$org' AND campus_id = '$campus_id'");
				$num=mysql_num_rows($result);
				if($num == 0)
				{
					$query = "INSERT INTO orgs (org_name, campus_id) VALUES('$org','$campus_id')";
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
				echo '<h2>Orgs added!</h2><br/>';
		}

	}
	$result = mysql_query("SELECT *  FROM campuses");
?>
<html>
<head>
	<title>
		Add orgs
	</title>
</head>
<body>
	<h1>
		Fill in the org names! Fill in the form and hit submit.
	</h1>
	<form method='post' action='add_org.php' enctype="multipart/form-data">
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
					Name of orgs to add:
				</td>
				<td>
					<input type='text' name='orgs'/>
				</td>
			</tr>	
		 	<tr>
		 		<td> <input type='submit' name='submit' value='Add'/> </td>
		 	</tr>
		</table>
</body>
</html>
