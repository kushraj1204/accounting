<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->datechooser = $this->setting_model->getDatechooser();
    }

    function index() {
        //  if(!$this->rbac->hasPrivilege('student_attendance','can_view')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'users/index');
        $studentList = $this->student_model->getStudents();
        $staffList = $this->staff_model->getAll();
        $parentList = $this->parent_model->getParentList();

        $data['studentList'] = $studentList;
        $data['parentList'] = $parentList;
        $data['staffList'] = $staffList;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/users/userList', $data);
        $this->load->view('layout/footer', $data);
    }

    function changeStatus() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $role = $this->input->post('role');
        $data = array('id' => $id, 'is_active' => $status);
        if ($role != "staff") {
            $result = $this->user_model->changeStatus($data);
        } else {
            if ($status == "yes") {
                $data['is_active'] = 1;
            } else {
                $data['is_active'] = 0;
            }
            // print_r($data);
            // exit();
            $result = $this->staff_model->update($data);
        }

        if ($result) {
            $response = array('status' => 1, 'msg' => 'Status change successfully');
            echo json_encode($response);
        }
    }

    function admissionreport() {
        if (!$this->rbac->hasPrivilege('student_history', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/users/admissionreport');
        $data['title'] = 'Admission Report';
        $data['datechooser'] = $this->datechooser;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        // $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        //  }

        $class_id = $this->input->get("class_id");
        $year = $this->input->get("year");
        $data['class_id'] = $class_id;
        $data['year'] = $year;

        $_admission_year = $this->student_model->admissionYear();
        $_years = array_column($_admission_year, 'year');
        $min_year = min($_years);
        $max_year = max($_years);
//		$admission_year = array_filter($admission_year, function($x) {
//            return !empty($x['year']);
//        });
        $admission_year = array();
        for ($i = $min_year - 1; $i <= $max_year; $i++) {
            $admission_year[] = array(
                'year' => $i
            );
        }
        $admission_year = array_map(function($a) {
            if($this->datechooser == 'bs') {
                return array(
                    'year' => $a['year'] + 57
                );
            } else {
                return array(
                    'year' => $a['year']
                );
            }
        }, $admission_year);

        $data["admission_year"] = $admission_year;
        if ((empty($class_id)) && (empty($year))) {

            $resultlist = $this->student_model->studentAdmissionDetails($carray);
            $data["resultlist"] = $resultlist;
        } else {


            $resultlist = $this->student_model->searchAdmissionDetails($class_id, $year, $this->datechooser == 'bs');
            $data["resultlist"] = $resultlist;
        }
        if (!empty($resultlist)) {
            foreach ($resultlist as $key => $value) {

                $id = $value["sid"];
                $sessionlist[] = $this->student_model->studentSessionDetails($id);
            }
            $data["sessionlist"] = $sessionlist;
        }
        $this->load->view("layout/header", $data);
        $this->load->view("admin/users/admissionReport", $data);
        $this->load->view("layout/footer", $data);
    }

    function logindetailreport() {
        if (!$this->rbac->hasPrivilege('student_login_credential', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/users/logindetailreport');
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $studentdata = $this->student_model->get();
        if (isset($_POST["search"])) {

            $class_id = $this->input->post("class_id");
            $section_id = $this->input->post("section_id");

            $studentdata = $this->student_model->searchByClassSection($class_id, $section_id);
        }

        foreach ($studentdata as $key => $value) {
            $resultlist = $this->user_model->getUserLoginDetails($value["id"]);
            $parentlist = $this->user_model->getParentLoginDetails($value["id"]);
            if ($resultlist["role"] == "student") {
                $studentdata[$key]["st_k"] = $resultlist["id"];
                $studentdata[$key]["par_k"] = $parentlist["id"];
                $studentdata[$key]["student_username"] = $resultlist["username"];
                $studentdata[$key]["student_password"] = $resultlist["password"];
                $studentdata[$key]["parent_username"] = $parentlist["username"];
                $studentdata[$key]["parent_password"] = $parentlist["password"];
                $studentdata[$key]["parent__users_id"] = $parentlist["id"];
                $studentdata[$key]["student__users_id"] = $resultlist["id"];
            }
        }


        $data["resultlist"] = $studentdata;

        $this->load->view("layout/header");
        $this->load->view("admin/users/logindetailreport", $data);
        $this->load->view("layout/footer");
    }

    public function updateUserLogin()
    {
        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {
            if (!$this->rbac->hasPrivilege('student_login_credential', 'can_view')) {
                access_denied();
            }
            $student__users_id = $this->input->post('student__users_id');
            $parent__users_id = $this->input->post('parent__users_id');
            $this->form_validation->set_rules('student_username', 'lang:student_username', 'trim|required|alpha_numeric|edit_unique[users.username.'.$student__users_id.']');
            $this->form_validation->set_rules('parent_username', 'lang:parent_username', 'trim|required|alpha_numeric|differs[student_username]|edit_unique[users.username.'.$parent__users_id.']');
            $this->form_validation->set_rules('student_password', 'lang:student_password', 'trim|required');
            $this->form_validation->set_rules('parent_password', 'lang:parent_password', 'trim|required');
            if ($this->form_validation->run() == false) {
                $error = array(
                    'student_username' => form_error('student_username'),
                    'parent_username' => form_error('parent_username'),
                    'student_password' => form_error('student_password'),
                    'parent_password' => form_error('parent_password'),
                );
                echo json_encode(array('success' => false, 'error' => $error));
            } else {
                $data = array(
                    array(
                        'id' => $student__users_id,
                        'username' => $this->input->post('student_username'),
                        'password' => $this->input->post('student_password')
                    ),
                    array(
                        'id' => $parent__users_id,
                        'username' => $this->input->post('parent_username'),
                        'password' => $this->input->post('parent_password')
                    )
                );

                $this->db->update_batch('users', $data, 'id');
                echo json_encode(array('success' => true));
            }
        }
    }

}