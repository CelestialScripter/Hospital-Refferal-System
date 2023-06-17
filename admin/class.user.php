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
						header("Location: home.php?error");
						exit;
					}
				}
				else
				{
					header("Location: home.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: home.php?error");
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

	
	public function register($uname,$email,$upass,$stel,$code)
	{
		try
		{							
			$password = $upass;
			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_email,user_pass,user_phone,tokenCode) 
			                                             VALUES(:user_name, :user_mail, :user_pass, :user_phone, :active_code)");
			$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":user_phone",$stel);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	public function login($admin_name,$admin_pword)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM admin WHERE username=:email_id");
			$stmt->execute(array(":email_id"=>$admin_name));
			$adminRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				
				if($adminRow['password']==$admin_pword)
				{
					$_SESSION['adminSession'] = $adminRow['admin_id'];
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
		if(isset($_SESSION['adminSession']))
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
		$_SESSION['adminSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="your_gmail_id_here@gmail.com";  
		$mail->Password="your_gmail_password_here";            
		$mail->SetFrom('your_gmail_id_here@gmail.com','Coding Cage');
		$mail->AddReplyTo("your_gmail_id_here@gmail.com","Coding Cage");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}
	
	function send_mail_contact($email,$message,$subject,$attachment)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="your_gmail_id_here@gmail.com";  
		$mail->Password="your_gmail_password_here";            
		$mail->SetFrom('your_gmail_id_here@gmail.com','Coding Cage');
		$mail->AddReplyTo("your_gmail_id_here@gmail.com","Coding Cage");
		$mail->Subject    = $subject;
		$mail->addAttachment    = $attachment;
		$mail->MsgHTML($message);
		$mail->Send();
	}



	public function getUID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function getCHID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM charges WHERE charges_id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	
	public function edit_InUser($id,$uname,$uemail,$upass,$uphone,$reg_created,$Ustatus,$regStatus,$payStatus,$payType)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE users SET user_name=:name, 
		                                               user_email=:mail, 
													   user_pass=:pword, 
													   user_phone=:tel,
													   reg_date=:created,
													   modified_date= NOW(),
													   userStatus=:status,
													   regStatus=:reg,
													   payStatus=:pstatus,
													   pType=:pay
													 WHERE user_id=:id ");
			$stmt->bindparam(":name",$uname);
			$stmt->bindparam(":mail",$uemail);
			$stmt->bindparam(":pword",$upass);
			$stmt->bindparam(":tel",$uphone);
			$stmt->bindparam(":created",$reg_created);
			$stmt->bindparam(":status",$Ustatus);
			$stmt->bindparam(":reg",$regStatus);
			$stmt->bindparam(":pstatus",$payStatus);
			$stmt->bindparam(":pay",$payType);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			
			return true;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function delete_InUser($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM users WHERE user_id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}
	
	
	public function payment($_id,$lastname,$firstname,$email,$stel)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO payment(user_id,lastname,firstname,email,phone) 
			                                             VALUES(:uid, :lastname, :firstname, :user_mail, :user_phone)");
			$stmt->bindparam(":uid",$_id);
			$stmt->bindparam(":lastname",$lastname);
			$stmt->bindparam(":firstname",$firstname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_phone",$stel);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function add_hospital($hospital,$telephone,$address,$doctors,$nurses,$latitude,$longitude)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO hospitals(hospital_name,telephone,address,physicians,nurses,latitude,longitude) VALUES(:hname, :tel, :addr, :docs, :nurse, :lat, :long)");
			$stmt->bindparam(":hname",$hospital);
			$stmt->bindparam(":tel",$telephone);
			$stmt->bindparam(":addr",$address);
			$stmt->bindparam(":docs",$doctors);
			$stmt->bindparam(":nurse",$nurses);
			$stmt->bindparam(":lat",$latitude);
			$stmt->bindparam(":long",$longitude);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
		
	}


	public function add_contact($hospital,$firstname,$lastname,$telephone,$position,$username,$password,$specialization,$cover_link)
	{
		try
		{
			$new_pass = md5($password);
			$stmt = $this->conn->prepare("INSERT INTO contact_person(hospital_id,contact_firstname,contact_lastname,telephone,post,username,password,photograph,specialization) VALUES(:hospital, :firstname, :lastname, :telephone, :position, :username, :pass, :photo,:specialization)");
			$stmt->bindparam(":hospital",$hospital);
			$stmt->bindparam(":firstname",$firstname);
			$stmt->bindparam(":lastname",$lastname);
			$stmt->bindparam(":telephone",$telephone);
			$stmt->bindparam(":position",$position);
			$stmt->bindparam(":username",$username);
			$stmt->bindparam(":pass",$new_pass);
			$stmt->bindparam(":specialization",$specialization);
			$stmt->bindparam(":photo",$cover_link);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
		
	}
	
	public function view_hospitals($query)
	{
		$i = 1;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
                <tr>
					<td><?php print($i++); ?></td>
					<td><?php print($row['hospital_name']); ?></td>
					<td><?php print($row['telephone']); ?></td>
					<td><?php print($row['address']); ?></td>
					<td><?php print($row['latitude']); ?></td>
					<td><?php print($row['longitude']); ?></td>
					<td align="center">
						<a href="edit_hospital.php?edit_id=<?php print($row['hospital_id']); ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						<a href="delete_hospital.php?delete_id=<?php print($row['hospital_id']); ?>"><i class="fa fa-trash"></i></a>
					</td>
                </tr>
                <?php
			}
		}
		else
		{
			?>
            <tr>
            <td colspan="7" align="center">Nothing here...</td>
            </tr>
            <?php
		}
		
	}

	public function getHID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM hospitals WHERE hospital_id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function edit_hospital($id,$hospital,$telephone,$address,$doctors,$nurses,$latitude,$longitude)
	{
		try
		{
			$stmt=$this->conn->prepare("UPDATE hospitals SET hospital_name=:name, 
		                                               telephone=:phone, 
													   address=:addr, 
													   physicians=:docs,
													   nurses=:nurse,
													   latitude=:lat,
													   longitude=:long
													 WHERE hospital_id=:id ");
			$stmt->bindparam(":name",$hospital);
			$stmt->bindparam(":phone",$telephone);
			$stmt->bindparam(":addr",$address);
			$stmt->bindparam(":docs",$doctors);
			$stmt->bindparam(":nurse",$nurses);
			$stmt->bindparam(":lat",$latitude);
			$stmt->bindparam(":long",$longitude);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			
			return true;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function delete_hospital($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM hospitals WHERE hospital_id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		if ($stmt) {
			$contact_stmt = $this->conn->prepare("DELETE FROM contact_person WHERE hospital_id=:hid");
			$contact_stmt->bindparam(":hid",$id);
			$contact_stmt->execute();
		}
		
		return true;
	}

	public function view_physicians($query)
	{
		$i = 1;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$hosp_stmt = $this->conn->prepare("SELECT * FROM hospitals WHERE hospital_id=:id");
				$hosp_stmt->execute(array(":id"=>$row['hospital_id']));
				$hospRow=$hosp_stmt->fetch(PDO::FETCH_ASSOC);
				?>
                <tr>
					<td><?php print($i++); ?></td>
					<td><img src="<?php print($row['photograph']); ?>" width="50px" height="50px"/></td>
                	<td><?php print($row['contact_firstname']." ".$row['contact_lastname']); ?></td>
					<td><?php print($row['telephone']); ?></td>
					<td><?php print($row['post']); ?></td>
					<td><?php print($hospRow['hospital_name']); ?></td>
					<td align="center">
						<a href="edit_physician.php?edit_id=<?php print($row['contact_id']); ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						<a href="delete_physician.php?delete_id=<?php print($row['contact_id']); ?>"><i class="fa fa-trash"></i></a>
					</td>
                </tr>
                <?php
			}
		}
		else
		{
			?>
            <tr>
            <td colspan="7" align="center">Nothing here...</td>
            </tr>
            <?php
		}
		
	}

	public function getPID($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM contact_person WHERE contact_id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}
	
	public function edit_physician($id,$hospital,$firstname,$lastname,$telephone,$position,$username,$password,$specialization,$cover_link)
	{
		try
		{
			$new_pass = md5($password);
			$stmt=$this->conn->prepare("UPDATE contact_person SET hospital_id=:hospital, 
		                                               contact_firstname=:firstname, 
													   contact_lastname=:lastname, 
													   telephone=:telephone,
													   post=:position,
													   username=:username,
													   password=:pass,
													   photograph=:photo,
													   specialization=:specialization
													 WHERE contact_id=:id ");
			$stmt->bindparam(":hospital",$hospital);
			$stmt->bindparam(":firstname",$firstname);
			$stmt->bindparam(":lastname",$lastname);
			$stmt->bindparam(":telephone",$telephone);
			$stmt->bindparam(":position",$position);
			$stmt->bindparam(":username",$username);
			$stmt->bindparam(":pass",$new_pass);
			$stmt->bindparam(":specialization",$specialization);
			$stmt->bindparam(":photo",$cover_link);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			
			return true;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function delete_physician($id)
	{
		$stmt = $this->conn->prepare("DELETE FROM contact_person WHERE contact_id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		
		return true;
	}
	
}