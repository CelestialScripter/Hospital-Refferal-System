<?php

require_once 'config.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	

	public function retreive($query)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$userRows=$stmt->fetch(PDO::FETCH_ASSOC);
		return $userRows;
		
	}
			/*$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();	
			return $stmt;		
			$userRow['userStatus']
				{
					if($userRow['userPass']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['userID'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}


		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
            <tr>
            <td><?php print($row['id']); ?></td>
            <td><?php print($row['first_name']); ?></td>
            <td><?php print($row['last_name']); ?></td>
            <td><?php print($row['email_id']); ?></td>
            <td><?php print($row['contact_no']); ?></td>
            <td align="center">
            <a href="edit-data.php?edit_id=<?php print($row['id']); ?>"><i class="glyphicon glyphicon-edit"></i></a>
            </td>
            <td align="center">
            <a href="delete.php?delete_id=<?php print($row['id']); ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
            </td>
            </tr>
            <?php
		}	
	}
*/

	
	// public function std_input($newId,$SLname,$SFname,$SMname,$newTel,$newMail,$sex,$DOB,$S_STATE,$S_LGA,$S_ADD,$DEFORM,$psport)
	// {
		
	// 	try
	// 	{							
			

	// 		/*$stmt1 = $this->conn->prepare("SELECT * FROM users WHERE user_email=:uemail");
	// 		$stmt->execute(array(":uemail"=>$SMail));
	// 		$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	// 		$newMail=$userRow['user_email'];
	// 		$newId=$userRow['user_id'];
	// 		$newTel=$userRow['sphone'];*/

	// 		$stmt = $this->conn->prepare("INSERT INTO student_data(user_id,slname,sfname,smname,sphone,email,gender,dob,sstate,slga,sadd,disable,passport) 
	// 		                                             VALUES(:uid, :slastname, :sfirstname, :smidname, :sTel, :sMail, :ssex, :dateob, :origin, :local, :sloc, :handicap, :sphoto)");
	// 		$stmt->bindparam(":uid",$newId);
	// 		$stmt->bindparam(":slastname",$SLname);
	// 		$stmt->bindparam(":sfirstname",$SFname);
	// 		$stmt->bindparam(":smidname",$SMname);
	// 		$stmt->bindparam(":sTel",$newTel);
	// 		$stmt->bindparam(":sMail",$newMail);
	// 		$stmt->bindparam(":ssex",$sex);
	// 		$stmt->bindparam(":dateob",$DOB);
	// 		$stmt->bindparam(":origin",$S_STATE);
	// 		$stmt->bindparam(":local",$S_LGA);
	// 		$stmt->bindparam(":sloc",$S_ADD);
	// 		$stmt->bindparam(":handicap",$DEFORM);
	// 		$stmt->bindparam(":sphoto",$psport);
	// 		$stmt->execute();	
	// 		return $stmt;
	// 	}
	// 	catch(PDOException $ex)
	// 	{
	// 		echo $ex->getMessage();
	// 	}
	// }
	
	
	// public function acad_input($newId,$sitting1,$sitting_yr,$subject1,$score1,$subject2,$score2,$subject3,$score3,$subject4,$score4,$subject5,$score5,$sitting2,$sitting_yr2,$subject1B,$score1B,$subject2B,$score2B,$subject3B,$score3B,$subject4B,$score4B,$subject5B,$score5B,$ALevel1,$ALevel2,$ALevel3,$ACenter,$Univ1,$Univ2,$Choice1,$Choice2)
	// {
	// 	try
	// 	{							
	// 		$stmt = $this->conn->prepare("INSERT INTO academic_data(user_id,exam,exam_yr,OL_1,grade1,OL_2,grade2,OL_3,grade3,OL_4,grade4,OL_5,grade5,exam2,exam_yr2,OL_1B,grade1B,OL_2B,grade2B,OL_3B,grade3B,OL_4B,grade4B,OL_5B,grade5B,ijmb1,ijmb2,ijmb3,ijmb_center,uni1,uni2,course1,course2) 
	// 		                                             VALUES(:uid, :Sitting_1st, :Sitting_1yr, :Sub1, :GD1, :Sub2, :GD2, :Sub3, :GD3, :Sub4, :GD4, :Sub5, :GD5, :Sitting_2nd, :Sitting_2yr, :Sub1B, :GD1B, :Sub2B, :GD2B, :Sub3B, :GD3B, :Sub4B, :GD4B, :Sub5B, :GD5B, :AL1, :AL2, :AL3, :AL_Center, :Sch1, :Sch2, :Dept1, :Dept2)");
	// 		$stmt->bindparam(":uid",$newId);
	// 		$stmt->bindparam(":Sitting_1st",$sitting1);
	// 		$stmt->bindparam(":Sitting_1yr",$sitting_yr);
	// 		$stmt->bindparam(":Sub1",$subject1);
	// 		$stmt->bindparam(":GD1",$score1);
	// 		$stmt->bindparam(":Sub2",$subject2);
	// 		$stmt->bindparam(":GD2",$score2);
	// 		$stmt->bindparam(":Sub3",$subject3);
	// 		$stmt->bindparam(":GD3",$score3);
	// 		$stmt->bindparam(":Sub4",$subject4);
	// 		$stmt->bindparam(":GD4",$score4);
	// 		$stmt->bindparam(":Sub5",$subject5);
	// 		$stmt->bindparam(":GD5",$score5);
	// 		$stmt->bindparam(":Sitting_2nd",$sitting2);
	// 		$stmt->bindparam(":Sitting_2yr",$sitting_yr2);
	// 		$stmt->bindparam(":Sub1B",$subject1B);
	// 		$stmt->bindparam(":GD1B",$score1B);
	// 		$stmt->bindparam(":Sub2B",$subject2B);
	// 		$stmt->bindparam(":GD2B",$score2B);
	// 		$stmt->bindparam(":Sub3B",$subject3B);
	// 		$stmt->bindparam(":GD3B",$score3B);
	// 		$stmt->bindparam(":Sub4B",$subject4B);
	// 		$stmt->bindparam(":GD4B",$score4B);
	// 		$stmt->bindparam(":Sub5B",$subject5B);
	// 		$stmt->bindparam(":GD5B",$score5B);
	// 		$stmt->bindparam(":AL1",$ALevel1);
	// 		$stmt->bindparam(":AL2",$ALevel2);
	// 		$stmt->bindparam(":AL3",$ALevel3);
	// 		$stmt->bindparam(":AL_Center",$ACenter);
	// 		$stmt->bindparam(":Sch1",$Univ1);
	// 		$stmt->bindparam(":Sch2",$Univ2);
	// 		$stmt->bindparam(":Dept1",$Choice1);
	// 		$stmt->bindparam(":Dept2",$Choice2);
	// 		$stmt->execute();	
	// 		return $stmt;
	// 	}
	// 	catch(PDOException $ex)
	// 	{
	// 		echo $ex->getMessage();
	// 	}
	// }
	
	
	// public function parent_data_input($newId,$P_TITLE,$P_NAME,$P_LNAME,$P_EMAIL,$P_TEL,$P_OCC,$P_ADD)
	// {
	// 	try
	// 	{							
	// 		$stmt = $this->conn->prepare("INSERT INTO parent_data(user_id,title,pfname,plname,pemail,pphone,occupation,padd) 
	// 		                                             VALUES(:uid,:PTitle, :PF_name, :PlastN, :PMail, :PTEL, :PJob, :PLoc)");
	// 		$stmt->bindparam(":uid",$newId);
	// 		$stmt->bindparam(":PTitle",$P_TITLE);
	// 		$stmt->bindparam(":PF_name",$P_NAME);
	// 		$stmt->bindparam(":PlastN",$P_LNAME);
	// 		$stmt->bindparam(":PMail",$P_EMAIL);
	// 		$stmt->bindparam(":PTEL",$P_TEL);
	// 		$stmt->bindparam(":PJob",$P_OCC);
	// 		$stmt->bindparam(":PLoc",$P_ADD);
	// 		$stmt->execute();	
	// 		return $stmt;
	// 	}
	// 	catch(PDOException $ex)
	// 	{
	// 		echo $ex->getMessage();
	// 	}
	// }
	
	
	public function register($email,$upass,$code,$referred)
	{
		try
		{							
			$password = md5($upass);
			$stmt = $this->conn->prepare("INSERT INTO users(email,pword,tokenCode,referrer) 
			                                             VALUES(:user_mail, :user_pass, :active_code, :referrer)");
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":active_code",$code);
			$stmt->bindparam(":referrer",$referred);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	
	public function login($uname,$upass)
	{
		try
		{
			$password = md5($upass);
			$stmt = $this->conn->prepare("SELECT * FROM contact_person WHERE username=:email_id");
			$stmt->execute(array(":email_id"=>$uname));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['status']=="1")
				{
					if($userRow['password']==$password)
					{
						$_SESSION['userSession'] = $userRow['contact_id'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	

	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}

	public function submit_review($user_id,$subject,$description,$rating)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO reviews(user_id,subject,description,rating) 
			                                             VALUES(:u_id, :subj, :descr, :rate)");
			$stmt->bindparam(":u_id",$user_id);
			$stmt->bindparam(":subj",$subject);
			$stmt->bindparam(":descr",$description);
			$stmt->bindparam(":rate",$rating);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	
	public function refer($referred_by,$firstname,$middlename,$lastname,$gender,$telephone,$dob,$sickness,$priority,$diagnosis,$specialty,$cover_link)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO referrals(reffered_by,firstname,middlename,lastname,telephone,gender,dob,sickness,priority,diagnosis,file,specialty_needed) 
			                                             VALUES(:referred, :firstname, :middlename, :lastname, :telephone, :gender, :dob, :subj, :priority, :diagnosis, :docs, :specialty)");
			$stmt->bindparam(":referred",$referred_by);
			$stmt->bindparam(":firstname",$firstname);
			$stmt->bindparam(":middlename",$middlename);
			$stmt->bindparam(":lastname",$lastname);
			$stmt->bindparam(":telephone",$telephone);
			$stmt->bindparam(":gender",$gender);
			$stmt->bindparam(":dob",$dob);
			$stmt->bindparam(":subj",$sickness);
			$stmt->bindparam(":priority",$priority);
			$stmt->bindparam(":diagnosis",$diagnosis);
			$stmt->bindparam(":specialty",$specialty);
			$stmt->bindparam(":docs",$cover_link);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function temp($hospital_id,$destination,$telephone,$distance)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO temp_dist(hospital_id,destination_hospital,patient_phone,distance) 
			                                             VALUES(:hid, :dest, :phone, :dist)");
			$stmt->bindparam(":hid",$hospital_id);
			$stmt->bindparam(":dest",$destination);
			$stmt->bindparam(":phone",$telephone);
			$stmt->bindparam(":dist",$distance);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function post_refer($referral_id,$destination_hospital)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO referred(referral_id,hospitalReferredTo) 
			                                             VALUES(:rid, :dest)");
			$stmt->bindparam(":rid",$referral_id);
			$stmt->bindparam(":dest",$destination_hospital);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function accept($ref_id)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE referrals SET status='ACCEPTED' 
													 WHERE referral_id=:id ");
			$stmt->bindparam(":id",$ref_id);
			$stmt->execute();

			if ($stmt) {
				$ref_stmt=$this->conn->prepare("UPDATE referred SET status='ACCEPTED' 
													 WHERE referral_id=:rid ");
				$ref_stmt->bindparam(":rid",$ref_id);
				$ref_stmt->execute();
			}
			
			return true;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

}