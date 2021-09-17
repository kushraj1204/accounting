<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mailer {

    public $mail_config;
    private $sch_setting;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('emailconfig_model');
        $this->CI->mail_config = $this->CI->emailconfig_model->getActiveEmail();
        $this->CI->load->model('setting_model');
        $this->sch_setting = $this->CI->setting_model->get();
    }

    public function send_mail($toemail, $subject, $body) {

        $mail = new PHPMailer();
        $school_name = $this->sch_setting[0]['name'];
        $email = $this->sch_setting[0]['email'];
        if ($this->CI->mail_config->email_type == "smtp") {
            $mail->IsSMTP();
            if($this->CI->mail_config->smtpauth_type == 1){
                $mail->SMTPAuth = true;
                $mail->Host = $this->CI->mail_config->smtp_server;
                $mail->Port = $this->CI->mail_config->smtp_port;
                $mail->Username = $this->CI->mail_config->smtp_username;
                $mail->Password = $this->CI->mail_config->smtp_password;
            }
            else{
            $mail->Host = $this->CI->mail_config->smtp_server;
            $mail->Port = $this->CI->mail_config->smtp_port;
            }
        }
        if($this->CI->mail_config->smtpauth_type == 1){
            $mail->SetFrom($this->CI->mail_config->smtp_username, $school_name);
            $mail->AddReplyTo($this->CI->mail_config->smtp_username, $school_name);
        }else{
            $mail->SetFrom($email, $school_name);
            $mail->AddReplyTo($email, $email);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $body;
        $mail->AddAddress($toemail);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        if ($mail->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
