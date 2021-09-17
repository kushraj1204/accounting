<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'notification/index');
        $data['title'] = 'Notifications';


        $notifications = $this->notification_model->get();
        //$data['notificationlist'] = $notifications;

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        if (!empty($notifications)) {
            foreach ($notifications as $key => $value) {
                $roles = $value["roles"];
                $arr = explode(",", $roles);

                if ($this->rbac->isSuperAdmin() || $value['created_id'] == $userdata['id'] || array_key_exists($role_id, $arr)) {
                    //  echo "yes";
                    $rname = $this->notification_model->getRole($arr);

                    $data['notificationlist'][$key] = $notifications[$key];
                    $data['notificationlist'][$key]["role_name"] = array();
                    $data['notificationlist'][$key]["role_name"] = $rname;
                    if($value['visible_student'] == 'Yes') {
                        $data['notificationlist'][$key]["role_name"][] = array('name'=>'Student');
                    }
                    if($value['visible_parent'] == 'Yes') {
                        $data['notificationlist'][$key]["role_name"][] = array('name'=>'Parent');
                    }
                }
            }
        }
        $this->load->view('layout/header', $data);
        if($this->datechooser == 'bs') {
            $this->load->view('admin/notification/notificationList_bs', $data);
        } else{
            $this->load->view('admin/notification/notificationList', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    function add() {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'notification/add');
        $data['title'] = 'Add Notification';
        $data['title_list'] = 'Notification List';
        $data['roles'] = $this->role_model->get();

        $this->form_validation->set_rules('title', 'lang:title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'lang:message', 'trim|required|xss_clean');
        if($this->datechooser == 'bs') {
            $this->form_validation->set_rules('date_bs', 'lang:notice_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('publish_date_bs', 'lang:publish_date', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('date', 'lang:notice_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('publish_date', 'lang:publish_date', 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('visible[]', 'lang:message_to', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $student = "No";
            $staff = "No";
            $parent = "No";
            $staff_roles = array();
            $visible = $this->input->post('visible');
            foreach ($visible as $key => $value) {

                if ($value == "student") {
                    $student = "Yes";
                } else if ($value == "parent") {
                    $parent = "Yes";
                } else if (is_numeric($value)) {

                    $staff_roles[] = array('role_id' => $value, 'send_notification_id' => '');
                    $staff = "Yes";
                }
            }

            $data = array(
                'message' => $this->input->post('message'),
                'title' => $this->input->post('title'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'created_by' => 'admin',
                'created_id' => $this->customlib->getStaffID(),
                'visible_student' => $student,
                'visible_staff' => $staff,
                'visible_parent' => $parent,
                'publish_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('publish_date'))),
                'date_bs'=>$this->input->post('date_bs'),
                'publish_date_bs'=>$this->input->post('publish_date_bs'),
            );


            $this->notification_model->insertBatch($data, $staff_roles);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/notification/index');
        }
        $exam_result = $this->exam_model->get();
        $data['examlist'] = $exam_result;
        $this->load->view('layout/header', $data);
        if($this->datechooser == 'bs') {
            $this->load->view('admin/notification/notificationAdd_bs', $data);
        } else {
            $this->load->view('admin/notification/notificationAdd', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_edit')) {
            access_denied();
        }
        $data['id'] = $id;
        $notification = $this->notification_model->get($id);

        $data['notification'] = $notification;
        $data['roles'] = $this->role_model->get();
        $data['title'] = 'Edit Notification';
        $data['title_list'] = 'Notification List';
        $this->form_validation->set_rules('title', 'lang:title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'lang:message', 'trim|required|xss_clean');
        if($this->datechooser == 'bs') {
            $this->form_validation->set_rules('date_bs', 'lang:notice_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('publish_date_bs', 'lang:publish_date', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('date', 'lang:notice_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('publish_date', 'lang:publish_date', 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $student = "No";
            $staff = "No";
            $parent = "No";
            $prev_roles = $this->input->post('prev_roles');
            $visible = $this->input->post('visible');
            $staff_roles = array();
            $inst_staff = array();
            foreach ($visible as $key => $value) {

                if ($value == "student") {
                    $student = "Yes";
                } else if ($value == "parent") {
                    $parent = "Yes";
                } else if (is_numeric($value)) {
                    $inst_staff[] = $value;
                    $staff_roles[] = array('role_id' => $value, 'send_notification_id' => '');
                    $staff = "Yes";
                }
            }

            $to_be_del = array_diff($prev_roles, $inst_staff);
            $to_be_insert = array_diff($inst_staff, $prev_roles);
            $insert = array();
            if (!empty($to_be_insert)) {

                foreach ($to_be_insert as $to_insert_key => $to_insert_value) {
                    $insert[] = array('role_id' => $to_insert_value, 'send_notification_id' => '');
                }
            }


            $data = array(
                'id' => $id,
                'message' => $this->input->post('message'),
                'title' => $this->input->post('title'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'created_by' => 'admin',
                'created_id' => $this->customlib->getStaffID(),
                'visible_student' => $student,
                'visible_staff' => $staff,
                'visible_parent' => $parent,
                'publish_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('publish_date'))),
                'date_bs'=>$this->input->post('date_bs'),
                'publish_date_bs'=>$this->input->post('publish_date_bs'),
            );
            $this->notification_model->insertBatch($data, $insert, $to_be_del);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/notification/index');
        }
        $exam_result = $this->exam_model->get();
        $data['examlist'] = $exam_result;
        $this->load->view('layout/header', $data);
        if($this->datechooser == 'bs') {
            $this->load->view('admin/notification/notificationEdit_bs', $data);
        } else {
            $this->load->view('admin/notification/notificationEdit', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_delete')) {
            access_denied();
        }
        $this->notification_model->remove($id);
        redirect('admin/notification');
    }

    function setting() {
        if (!$this->rbac->hasPrivilege('notification_setting', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'notification/setting');
        $data = array();
        $data['title'] = 'Email Config List';
        $data['notificationMethods'] = $this->customlib->getNotificationModes();
        $notificationlist = $this->notificationsetting_model->get();
        $data['notificationlist'] = $notificationlist;
        $this->form_validation->set_rules('email_type', 'Email Type', 'required');
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $student_admission_array = $this->input->post('key_array[]');
            foreach ($student_admission_array as $student_admission_array_key => $student_admission_array_value) {
                $is_mail = 0;
                $is_sms = 0;
                $a = $this->input->post($student_admission_array_value . '_mail');

                if (isset($a)) {
                    $is_mail = 1;
                }
                $b = $this->input->post($student_admission_array_value . '_sms');

                if (isset($b)) {
                    $is_sms = 1;
                }

                $data_insert = array(
                    'type' => $student_admission_array_value,
                    'is_mail' => $is_mail,
                    'is_sms' => $is_sms
                );
                $this->notificationsetting_model->add($data_insert);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/notification/setting');
        }

        $data['title'] = 'Email Config List';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/notification/setting', $data);
        $this->load->view('layout/footer', $data);
    }

    function read() {
        $array = array('status' => "fail", 'msg' => 'somthing went wrong');
        $notification_id = $this->input->post('notice');
        if ($notification_id != "") {
            $staffid = $this->customlib->getStaffID();
            $data = $this->notification_model->updateStatusforStaff($notification_id, $staffid);
            $array = array('status' => "success", 'data' => $data, 'msg' => $this->lang->line('saved_successfully'));
        }

        echo json_encode($array);
    }

}

?>