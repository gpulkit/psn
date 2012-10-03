<?php
require_once('lib2.php');
$result = mysql_query('SELECT * from users');
$result = mysql_query('SELECT * from campuses');
if($result == 0)
	mysql_error($result,$conn);

while (($row = mysql_fetch_array($result, MYSQL_ASSOC)))
{
        //$user_id = $row['user_id'];
        $campus_id = $row['campus_id'];
        $campus_name = $row['campus_name'];

        $result2 = mysql_query("SELECT * from orgs WHERE campus_id = '$campus_id'");
        if($result2 == 0)
	    mysql_error($result2,$conn);

        echo "<h1 style='color:red;'>".$campus_name.'</h1>';
        while (($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)))
        {
            $org_id = $row2['org_id'];
            $org_name = $row2['org_name'];
            
            $result3 = mysql_query("SELECT * from users  WHERE org_id = '$org_id'");
            if($result3 == 0)
            	    mysql_error($result3,$conn);

            $count = mysql_num_rows($result3);
            echo "<h2 style='color:blue;'>".$org_name.' ('.$count.')</h2>';
            while (($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)))
            {
                $user_id = $row3['user_id'];
                $user_name = $row3['name'];
                $email = $row3['email'];
                echo "<h3>".$email.'</h3>';
                //printUserPictures($user_id);
            }
        }

}
        
        echo "<h1 style='color:red;'>Miscellaneous</h1>";
        $result3 = mysql_query("SELECT * from users  WHERE org_id IS NULL");
            if($result3 == 0)
            	    mysql_error($result3,$conn);

            while (($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)))
            {
                $user_id = $row3['user_id'];
                $user_name = $row3['name'];
                $email = $row3['email'];
                echo "<h3>".$email.'</h3>';
                //printUserPictures($user_id);
            }
?>
