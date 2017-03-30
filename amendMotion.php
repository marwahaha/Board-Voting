<html>
	<head>
		<title>Amending Motion</title>
	</head>
	<body>
		<?php
		$motionid=$_POST['motionid'];
		$motionname=$_POST['motionname'];
		$newmotiondesc=$_POST['newmotiondesc'];
		$existingmotiondec=$_POST['existingmotiondec'];
		$userid=$_SESSION['userid'];
		
		include_once('include/db-config.php');
	
		if ($newmotiondesc != "")
		{
			if ($newmotiondesc == $existingmotiondec)
			{
				echo "The new motion text and the existing is the same";
			}
			elseif ($newmotiondesc != $existingmotiondec)
			{
				$updateMotion=$db_con->prepare(
					"UPDATE motions SET motion_description=:description where motions_id=:motionid;");
				$updateMotion->execute(array(':updatedvote'=>$motiontext,':motionid' =>$motionid));
				echo "Updated the motion";
				echo "<br />";
				
				$dispo="AMENDED";
				$amendMotion=$db_con->prepare(
						"UPDATE motions SET motion_disposition=:dispo where motions_id=:motionid;");
				$amendMotion->execute(array(':dispo'=>$dispo,':motionid'=>$motionid));
				echo "Updated motion disposition";
				
				$field="Motion Description";
				$insertChange=$db_con->prepare(
					"INSERT INTO motionChangeLog
						(userid,motionid,field,oldValue,newValue)
						VALUES (:userid,:motionid,:field,:oldValue,:newValue);");
				$insertChange->bindParam(':userid',$userid);
				$insertChange->bindParam(':motionid',$motionid);
				$insertChange->bindParam(':field',$field);
				$insertChange->bindParam(':oldValue',$existingmotiondec);
				$insertChange->bindParam(':newValue',$newmotiondesc);
				$insertChange->execute();
				
				$disposition="AMENDED";
				$updateStatus=$db_con->prepare(
					"UPDATE motions set motion_disposition = :motiondispo where motion_id=:motionid;");
				$updateStatus->bindParam(':motiondispo',$disposition);
				$updateStatus->bindParam(':motionid',$motionid);
				$updateStatus->execute();
				
			}
			else
			{
				echo "You did not enter anything";
			}
		}	
		else
		{
			echo "You did not enter anything";
		}
		?>
		<a href="dashboard.php">Main Dashboard</a>
	</body>
</html>
