<?php
/**
 * Created by PhpStorm.
 * User: Brainnovation
 * Date: 12/26/2018
 * Time: 10:57 AM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Neemainstructor extends Admin_Controller{
    function __construct() {
        parent::__construct();

        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->load->model("staff_model");
        $this->load->model("api/student_model_api");
        //  $this->load->model("timeline_model");
        $this->load->model("leaverequest_model");
        $this->contract_type = $this->config->item('contracttype');
        $this->marital_status = $this->config->item('marital_status');
        $this->staff_attendance = $this->config->item('staffattendance');
        $this->payroll_status = $this->config->item('payroll_status');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->status = $this->config->item('status');
        $this->load->library('Common_lib');
    }


    function search() {
        if (!$this->rbac->hasPrivilege('neema_instructor', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Staff Search';
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'neemainstructor/search');
        $search = $this->input->post("search");
        $resultlist = $this->staff_model->searchFullTextInstructor("", 1);
        $result_one = array();
        foreach($resultlist as $key=>$val){
            $intitude_code = $val['institute_code'];
            $instituteUserId = $val['institute_userid'];
            $instituteRoleId = $val['institute_roleid'];
            $reg_code = $val['registration_code'];
            $check = $this->common_lib->check_user_status($intitude_code,$instituteUserId,$instituteRoleId,$reg_code);
            $result_one[$key]['id'] = $val['id'];
            $result_one[$key]['employee_id'] = $val['employee_id'];
            $result_one[$key]['name'] = $val['name'];
            $result_one[$key]['surname'] = $val['surname'];
            $result_one[$key]['department'] = $val['department'];
            $result_one[$key]['designation'] = $val['designation'];
            $result_one[$key]['contact_no'] = $val['contact_no'];
            $result_one[$key]['registration_code'] = $val['registration_code'];
            $result_one[$key]['institute_code'] = $val['institute_code'];
            $result_one[$key]['institute_userid'] = $val['institute_userid'];
            $result_one[$key]['registration_code'] = $val['registration_code'];
            $result_one[$key]['institute_roleid'] = $val['institute_roleid'];
            $result_one[$key]['neema_status'] = $check;
//            $result_one[$key][''] = [''];
         }
        $data['resultlist'] = $result_one;
        $staffRole = $this->staff_model->getStaffRole();
        $staffIdList = $this->student_model_api->getAllStudentId(6);
        $data["role"] = $staffRole;
        $data["role_id"] = "";
        $data['staffIds'] = $staffIdList;

        $search_text = $this->input->post('search_text');
        if (isset($search)) {
      if ($search == 'search_full') {
                $data['searchby'] = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist = $this->staff_model->searchFullTextInstructor($search_text, 1);
                $result_one = array();
                foreach($resultlist as $key=>$val){
                    $intitude_code = $val['institute_code'];
                    $instituteUserId = $val['institute_userid'];
                    $instituteRoleId = $val['institute_roleid'];
                    $reg_code = $val['registration_code'];
                    $check = $this->common_lib->check_user_status($intitude_code,$instituteUserId,$instituteRoleId,$reg_code);
                    $result_one[$key]['id'] = $val['id'];
                    $result_one[$key]['employee_id'] = $val['employee_id'];
                    $result_one[$key]['name'] = $val['name'];
                    $result_one[$key]['surname'] = $val['surname'];
                    $result_one[$key]['department'] = $val['department'];
                    $result_one[$key]['designation'] = $val['designation'];
                    $result_one[$key]['contact_no'] = $val['contact_no'];
                    $result_one[$key]['registration_code'] = $val['registration_code'];
                    $result_one[$key]['institute_code'] = $val['institute_code'];
                    $result_one[$key]['institute_userid'] = $val['institute_userid'];
                    $result_one[$key]['registration_code'] = $val['registration_code'];
                    $result_one[$key]['institute_roleid'] = $val['institute_roleid'];
                    $result_one[$key]['neema_status'] = $check;
                //            $result_one[$key][''] = [''];
                }
                $data['resultlist'] = $result_one;
                $data['resultlist'] = $resultlist;
                $data['title'] = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/staff/instructorsearch', $data);
        $this->load->view('layout/footer');
    }

}
?>