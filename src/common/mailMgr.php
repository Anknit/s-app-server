<?php
    require_once __DIR__.'/PHPMailer-master/PHPMailerAutoload.php';
    class mailAccess {
        private $mailConfig, $error, $data, $baseUrl;
        public function __construct () {
            $this->mailConfig = array(
                'smtpAuth'      =>  'true',
                'smtpHostName'	=>	SMTP_HOST,
                'smtpPort'		=>	SMTP_PORT,
                'smtpUsername'	=>	SMTP_USER,
                'smtpPassword'	=>	SMTP_PSWD,
                'sender'		=>	SMTP_FROM,
                'senderName'    =>  SMTP_FROMNAME,
            );
            $this->baseUrl = Project_URL_PATH;
            $this->error = 0;
            $this->data = array();
        }
        public function __destruct () {

        }
        public function notifyAdminUserRegister ($recipient, $user) {
            $mailSubject = 'Greetings from '.Project_Brand;
            $MailBody = '<html><body><p>Welcome to &nbsp;<a href="'.$this->baseUrl.'">'.Project_Brand.'</a></p>';
            $MailBody .= '<p>Dear Admin,</p>';
            $MailBody .= '<p>A new user has applied for registration on the portal.</p>';
            $MailBody .= '<p>Please find the details as follows</p><br/>';
            $MailBody .= '<p><strong>Name: </strong>'.$user['name'].'</p>';
            $MailBody .= '<p><strong>Email: </strong>'.$user['email'].'</p><br/>';
            $MailBody .= '<p>Please do the needful</p><br/>';
            $MailBody .= '<p>Regards</p>';
            $MailBody .= '<p>Team '.Project_Brand.'</p>';
            $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
            $MailBody .= '</body></html>';
            $config = $this->mailConfig;

            $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $recipient);
            return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }
        public function notifyUserAccountActivation ($recipient, $receipientId) {
            $mailSubject = 'Greetings from '.Project_Brand;
            $MailBody = '<html><body><p>Welcome to &nbsp;<a href="'.$this->baseUrl.'">'.Project_Brand.'</a></p>';
            $MailBody .= '<p>Dear User,</p>';
            $MailBody .= '<p>Your account has been successfully activated on the portal.</p>';
            $cardGenerate = $this->generateCardHtml($receipientId);
            if($cardGenerate['status']){
              $MailBody .= $cardGenerate['data'];
            } else {
              return array('status'=>false,'error'=>$cardGenerate['error']);
            }
            $MailBody .= '<p>Please login with your username and the password you have choosen at the time of registration</p><br/>';
            $MailBody .= '<p>Click <a href="'.Project_URL_PATH.'">Here</a> to login in your account';
            $MailBody .= '<p>Regards</p>';
            $MailBody .= '<p>Team '.Project_Brand.'</p>';
            $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
            $MailBody .= '</body></html>';
            $config = $this->mailConfig;
            $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $recipient);
            return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }
		    public function notifyUserAccountUpdate ($recipient, $name, $type) {
            $mailSubject = 'Your account has been updated on '.Project_Brand;
            $MailBody = '<html><body><p>Hi '.$name.',</p>';
            if($type == 'Default'){
              $MailBody .= '<p>Admin privileges of your account has been disabled on '.Project_Brand.'. Contact admin for any queries.</p>';
            } else if($type == 'Admin'){
              $MailBody .= '<p>Your account type has been changed on '.Project_Brand.' Your account type has been updated to <b>'.$type.'</b></p>';
            }
            $MailBody .= '<p>If you do not see new changes, login again by using your credentials.</p><br/>';
            $MailBody .= '<p>Click <a href="'.Project_URL_PATH.'">Here</a> to login in your account';
            $MailBody .= '<p>Regards</p>';
            $MailBody .= '<p>Team '.Project_Brand.'</p>';
            $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
            $MailBody .= '</body></html>';
            $config = $this->mailConfig;
            $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $recipient);
            return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }
        public function notifyUserEventInvitation ($recipients, $event, $link, $creator) {
            $mailSubject = 'You are invited to an event on '.Project_Brand;
            $MailBody = '<html><body><p>Hi,</p>';

      			$MailBody .= '<p><b>'.$creator.'</b> invited you to an event on <b>'.Project_Brand.'</b> described below:</p>';

      			$MailBody .= '<p><b>'.$event['name'].'</b></p>';
      			$MailBody .= '<p>Date : '.date('m/d/Y',((int)$event['startDate'])/1000).'</p>';
      			$MailBody .= '<p>'.$event['description'].'</p></br>';

            $MailBody .= '<p>Click <a href="'.Project_URL_PATH.'?hash='.$link.'">Here</a> to login in your account';
            $MailBody .= '<p>Regards</p>';
            $MailBody .= '<p>Team '.Project_Brand.'</p>';
            $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
            $MailBody .= '</body></html>';
            $config = $this->mailConfig;
            $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $recipients);
            return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }
	      public function notifyUserGroupInvitation ($data, $group, $creator) {
            $mailSubject = Project_Brand.' | Group Invitation';
            $MailBody = '<html><body><p>Hi User,</p>';

      			$MailBody .= '<p><b>'.$creator.'</b> added you to a group on <b>'.Project_Brand.'</b></p>';

      			$MailBody .= '<p><b>'.$group['title'].'</b></p>';
      			$MailBody .= '<p>'.$group['description'].'</p></br>';

            $MailBody .= '<p>View details at <a href="'.Project_URL_PATH.'">'.Project_URL_PATH.'</a>';
            $MailBody .= '<p>Regards</p>';
            $MailBody .= '<p>Team '.Project_Brand.'</p>';
            $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
            $MailBody .= '</body></html>';
            $config = $this->mailConfig;
            $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $data['invitedMembers']);
            return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }
        public function sendPasswordResetLink ($name, $link, $recipient) {
          $mailSubject = 'Password reset link for your account on '.Project_Brand;
          $MailBody = '<html><body><p>Hi '.$name.',</p>';
          $MailBody .= '<p>You can reset your account password on clicking the following link.</p><br/>';
          $MailBody .= '<p>Click <a href="'.Project_URL_PATH.$link.'">Here</a> to reset your password';
          $MailBody .= '<p>Regards</p>';
          $MailBody .= '<p>Team '.Project_Brand.'</p>';
          $MailBody .= '<p><a href = "'.Project_URL_PATH.'" alt="'.Project_Brand.'">'.Project_Brand.'<a></p>';
          $MailBody .= '</body></html>';
          $config = $this->mailConfig;
          $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $recipient);
          return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }

        public function sendDailyUpdate($user, $updatesArray) {
          require __DIR__.'/mail-templates/daily-updates.php';
          $config = $this->mailConfig;
          $mailResponse = $this->sendMail($mailSubject, $MailBody, $config['sender'], $config['senderName'], $user['email']);
          return array('status'=>$mailResponse['status'], 'data'=>$mailResponse['data'], 'error'=> $mailResponse['error']);
        }

        public function generateCardHtml($userid) {
          $userData = DB_Read(array(
            'Table' => 'userinfo',
            'Fields'=> 'firstname,email,entryyear,passingyear,imageurl,memberid',
            'clause'=> 'id = '.$userid
          ));
          if(is_array($userData)){
            $card_user_name = $userData[0]['firstname'];
            $card_user_email = $userData[0]['email'];
            $card_user_start_year = $userData[0]['entryyear'];
            $card_user_end_year = $userData[0]['passingyear'];
            $card_user_image_path = Project_URL_PATH.$userData[0]['imageurl'];
            $card_user_member_id = $userData[0]['memberid'];
            $card_brand_logo = Project_URL_PATH.'images/brand-logo.png';
            $card_header_image = Project_URL_PATH.'images/card-header.jpg';
            include_once __DIR__.'./../card.php';
            if($cardHtml){
              $status = true;
              $data = $cardHtml;
            } else {
              $status = false;
              $error = 'Failed to get card text';
            }
          } else {
            $status = false;
            $error = 'Failed to read user details for card';
          }
          return array('status'=>$status,'data'=>$data,'error'=>$error);
        }

        public function sendMail ($subject, $body, $from, $fromName, $recipient) {
            $status = false; $error = ''; $data= array();
            $mail = new PHPMailer;

            $mail->From = $from;
            $mail->FromName = $fromName;
            $mail->Sender = $from;
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;
			$mail->Username = "usershruti1@gmail.com";
			$mail->Password = "shruti123";
			//If SMTP requires TLS encryption then set it
			$mail->SMTPSecure = "tls";
			//Set TCP port to connect to
			$mail->Port = 587;

			$mail->From = "usershruti1@gmail.com";;
			$mail->FromName = "Administrator";
            $mail->isHTML(true);
            if(SERVER_ENV == 'local') {
              $recipient = LOCAL_EMAIL;
            }
      			if(is_array($recipient)){
      				for($i=0; $i< count($recipient); $i++) {
      					$mail->addAddress( trim($recipient[$i]['email']) );
      				}
      			}else {
      				$mail->addAddress($recipient);
      			}

            $mail->Subject = $subject;
            $mail->Body = $body;
            if(!$mail->send()){
                $error =  "Mailer Error: " . $mail->ErrorInfo;
            } else {
                $status = true;
                $data = $recipient;
            }
            return array('status'=>$status, 'error' => $error, 'data' =>$data);
        }

    }
?>
