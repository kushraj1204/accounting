<?php

class Staff extends Admin_Controller
{
    private $datechooser;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
        $this->config->load("payroll");
        $this->load->library('Customlib');
        $this->name_titles = $this->customlib->getNameTitles();
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
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
        $this->load->event('staff_events');
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Staff Search';

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff');
        $search = $this->input->post("search");
        $resultlist = $this->staff_model->searchFullText("", 1);
        $data['resultlist'] = $resultlist;
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data["role_id"] = "";

        $search_text = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', 'lang:role', 'trim|required|xss_clean');
                if ($this->form_validation->run() == FALSE) {

                    $data["resultlist"] = array();
                } else {
                    $data['searchby'] = "filter";
                    $role = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');
                    $data["role_id"] = $role;
                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist = $this->staff_model->getEmployee($role, 1);
                    $data['resultlist'] = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby'] = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist = $this->staff_model->searchFullText($search_text, 1);
                $data['resultlist'] = $resultlist;
                $data['title'] = 'Search Details: ' . $data['search_text'];
            }
        }
        if (!$this->rbac->isSuperAdmin()) {
            $data['resultlist'] = array_filter($data['resultlist'], function ($r) {
                return $r['user_type'] != 'Super Admin';
            });
        }
        $this->load->view('layout/header');
        $this->load->view('admin/staff/staffsearch', $data);
        $this->load->view('layout/footer');
    }

    function disablestafflist()
    {

        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/disablestafflist');
        $data['title'] = 'Staff Search';
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;

        $search = $this->input->post("search");
        $search_text = $this->input->post('search_text');
        $resultlist = $this->staff_model->searchFullText($search_text, 0);
        $data['resultlist'] = $resultlist;

        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', 'lang:role', 'trim|required|xss_clean');
                if ($this->form_validation->run() == FALSE) {

                    //$resultlist = array();
                    $data['resultlist'] = $resultlist;
                } else {
                    $data['searchby'] = "filter";
                    $role = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');

                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist = $this->staff_model->getEmployee($role, 0);
                    $data['resultlist'] = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby'] = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist = $this->staff_model->searchFullText($search_text, 0);
                $data['resultlist'] = $resultlist;
                $data['title'] = 'Search Details: ' . $data['search_text'];
            }
        }
        $data['date_chooser'] = $this->datechooser;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/disablestaff', $data);
        $this->load->view('layout/footer', $data);
    }

    function profile($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }

        $this->load->model("staffattendancemodel");
        $this->load->model("setting_model");
        $data["id"] = $id;
        $data['title'] = 'Staff Details';
        $staff_info = $this->staff_model->getProfile($id);
        if (!$this->rbac->isSuperAdmin() && $staff_info['user_type'] == 'Super Admin') {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Access denied</div>');

            redirect('admin/staff');
        }
        $userdata = $this->customlib->getUserData();
        $userid = $userdata['id'];
        $timeline_status = '';
        if ($userid == $id) {
            $timeline_status = 'yes';
        }
        $timeline_list = $this->timeline_model->getStaffTimeline($id, $timeline_status);
        $data["timeline_list"] = $timeline_list;
        $staff_payroll = $this->staff_model->getStaffPayroll($id);
        $staff_leaves = $this->leaverequest_model->staff_leave_request($id);
        $alloted_leavetype = $this->staff_model->allotedLeaveType($id);
        $this->load->model("payroll_model");
        $salary = $this->payroll_model->getSalaryDetails($id);
        $attendencetypes = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $i = 0;
        $leaveDetail = array();
        foreach ($alloted_leavetype as $key => $value) {
            $count_leaves[] = $this->leaverequest_model->countLeavesData($id, $value["leave_type_id"]);
            $leaveDetail[$i]['type'] = $value["type"];
            $leaveDetail[$i]['alloted_leave'] = $value["alloted_leave"];
            $leaveDetail[$i]['approve_leave'] = $count_leaves[$i]['approve_leave'];
            $i++;
        }
        $data["leavedetails"] = $leaveDetail;
        $data["staff_leaves"] = $staff_leaves;
        $data['staff_doc_id'] = $id;
        $data['staff'] = $staff_info;
        $data['staff_payroll'] = $staff_payroll;
        $data['salary'] = $salary;

        $monthlist = $this->customlib->getMonthDropdown();
        $startMonth = $this->setting_model->getStartMonth();
        $data["monthlist"] = $monthlist;
        $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $session_current = $this->setting_model->getCurrentSessionName();
        $startMonth = $this->setting_model->getStartMonth();
        $centenary = substr($session_current, 0, 2); //2017-18 to 2017
        $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
        $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
        $month_number = date("m", strtotime($startMonth));

        if ($month_number >= $startMonth && $month_number <= 12) {
            $year = $centenary . $year_first_substring;
        } else {
            $year = $centenary . $year_second_substring;
        }

        $j = 0;
        for ($n = 1; $n <= 31; $n++) {

            $att_date = sprintf("%02d", $n);

            $attendence_array[] = $att_date;

            foreach ($monthlist as $key => $value) {

                $datemonth = date("m", strtotime($value));
                $att_dates = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);
                $date_array[] = $att_dates;
                $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($id, $att_dates);
            }

            $j++;
        }

        $session = $this->setting_model->getCurrentSessionName();

        $session_start = explode("-", $session);
        $start_year = $session_start[0];

        $date = $start_year . "-" . $startMonth;
        $newdate = date("Y-m-d", strtotime($date . "+1 month"));

        $countAttendance = $this->countAttendance($start_year, $startMonth, $id);
        $data["countAttendance"] = $countAttendance;

        $data["resultlist"] = $res;
        $data["attendence_array"] = $attendence_array;
        $data["date_array"] = $date_array;
        $data["payroll_status"] = $this->payroll_status;
        $data["payment_mode"] = $this->payment_mode;
        $data["contract_type"] = $this->contract_type;
        $data["status"] = $this->status;
        $roles = $this->role_model->get();
        $data["roles"] = $roles;

        $stafflist = $this->staff_model->get();
        $data['stafflist'] = $stafflist;

        $this->load->view('layout/header', $data);
        if ($this->datechooser == 'bs') {
            $this->load->view('admin/staff/staffprofile_bs', $data);
        } else {
            $this->load->view('admin/staff/staffprofile', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    function countAttendance($st_month, $no_of_months, $emp)
    {

        $record = array();
        for ($i = 1; $i <= 1; $i++) {

            $r = array();
            $month = date('m', strtotime($st_month . " -$i month"));
            $year = date('Y', strtotime($st_month . " -$i month"));

            foreach ($this->staff_attendance as $att_key => $att_value) {

                $s = $this->staff_model->count_attendance($year, $emp, $att_value);


                $r[$att_key] = $s;
            }

            $record[$year] = $r;
        }

        return $record;
    }

    function getSession()
    {
        $session = $this->session_model->getAllSession();
        $data = array();
        $session_array = $this->session->has_userdata('session_array');
        $data['sessionData'] = array('session_id' => 0);
        if ($session_array) {
            $data['sessionData'] = $this->session->userdata('session_array');
        } else {
            $setting = $this->setting_model->get();

            $data['sessionData'] = array('session_id' => $setting[0]['session_id']);
        }
        $data['sessionList'] = $session;

        return $data;
    }

    public function getSessionMonthDropdown()
    {
        $startMonth = $this->setting_model->getStartMonth();
        $array = array();
        for ($m = $startMonth; $m <= $startMonth + 11; $m++) {
            $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $array[$month] = $month;
        }
        return $array;
    }

    public function download($staff_id, $doc)
    {

        $this->load->helper('download');
        $filepath = "./uploads/staff_documents/$staff_id/" . $this->uri->segment(5);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(5);

        force_download($name, $data);
    }

    function doc_delete($id, $doc, $file)
    {
        $this->staff_model->doc_delete($id, $doc, $file);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Document Deleted Successfully');
        redirect('admin/staff/profile/' . $id);
    }

    function ajax_attendance($id)
    {
        $this->load->model("staffattendancemodel");
        $attendencetypes = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $year = $this->input->post("year");
        if (!empty($year)) {

            $monthlist = $this->customlib->getMonthDropdown();
            $startMonth = $this->setting_model->getStartMonth();
            $data["monthlist"] = $monthlist;
            $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();


            $j = 0;
            for ($n = 1; $n <= 31; $n++) {

                $att_date = sprintf("%02d", $n);

                $attendence_array[] = $att_date;

                foreach ($monthlist as $key => $value) {

                    $datemonth = date("m", strtotime($value));
                    $att_dates = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);
                    $date_array[] = $att_dates;
                    $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($id, $att_dates);
                }

                $j++;
            }


            $date = $year . "-" . $startMonth;
            $newdate = date("Y-m-d", strtotime($date . "+1 month"));

            $countAttendance = $this->countAttendance($year, $startMonth, $id);
            $data["countAttendance"] = $countAttendance;
            $data["id"] = $id;
            $data["resultlist"] = $res;
            $data["attendence_array"] = $attendence_array;
            $data["date_array"] = $date_array;

            $this->load->view("admin/staff/ajaxattendance", $data);
        } else {

            echo "No Record Found";
        }
    }

    function create()
    {

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff');
        $roles = $this->role_model->get();
        $data["roles"] = $roles;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $payscaleList = $this->staff_model->getPayroll();
        $leavetypeList = $this->staff_model->getLeaveType();
        $data["leavetypeList"] = $leavetypeList;
        $data["payscaleList"] = $payscaleList;
        $designation = $this->staff_model->getStaffDesignation();
        $data["designation"] = $designation;
        $department = $this->staff_model->getDepartment();
        $data["department"] = $department;
        $marital_status = $this->marital_status;


        $data["marital_status"] = $marital_status;
        $data['name_titles'] = $this->name_titles;

        $data['title'] = 'Add Staff';
        $data["contract_type"] = $this->contract_type;
        $this->form_validation->set_rules('name', 'lang:name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', 'lang:role', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'lang:gender', 'trim|required|xss_clean');
        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('dob_bs', 'lang:date_of_birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date_of_joining_bs', 'lang:date_of_joining', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('dob', 'lang:date_of_birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date_of_joining', 'lang:date_of_joining', 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('department', 'lang:department', 'trim|required|xss_clean');
        $this->form_validation->set_rules('designation', 'lang:designation', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean', array(
        //  'valid_email' => 'Invalid Email',
        //));
        $this->form_validation->set_rules(
            'email', 'lang:email', array('required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id'))
            )
        );

        $this->form_validation->set_rules(
            'employee_id', 'lang:staff_id', array('required',
                array('check_exists', array($this->staff_model, 'valid_employee_id'))
            )
        );


        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            if ($this->datechooser == 'bs') {
                $this->load->view('admin/staff/staffcreate_bs', $data);
            } else {
                $this->load->view('admin/staff/staffcreate', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {

            $employee_id = $this->input->post("employee_id");
            $department = $this->input->post("department");
            $designation = $this->input->post("designation");
            $role = $this->input->post("role");
            if (!$this->rbac->isSuperAdmin() && $role == 7) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Access denied</div>');

                redirect('admin/staff');
            }
            $name = $this->input->post("name");
            $gender = $this->input->post("gender");
            $marital_status = $this->input->post("marital_status");
            $dob = $this->input->post("dob");
            $dob_bs = $this->input->post("dob_bs");
            $contact_no = $this->input->post("contactno");
            $emergency_no = $this->input->post("emergency_no");
            $email = $this->input->post("email");
            $date_of_joining = $this->input->post("date_of_joining");
            $date_of_joining_bs = $this->input->post("date_of_joining_bs");
            $date_of_leaving = $this->input->post("date_of_leaving");
            $date_of_leaving_bs = $this->input->post("date_of_leaving_bs");
            $address = $this->input->post("address");
            $qualification = $this->input->post("qualification");
            $work_exp = $this->input->post("work_exp");
            $basic_salary = $this->input->post('basic_salary');
            $account_title = $this->input->post("account_title");
            $bank_account_no = $this->input->post("bank_account_no");
            $bank_name = $this->input->post("bank_name");
            $pan_no = $this->input->post("pan_no");
            $cit_no = $this->input->post("cit_no");
            $bank_branch = $this->input->post("bank_branch");
            $contract_type = $this->input->post("contract_type");
            $shift = $this->input->post("shift");
            $location = $this->input->post("location");
            $leave = $this->input->post("leave");
            $facebook = $this->input->post("facebook");
            $twitter = $this->input->post("twitter");
            $linkedin = $this->input->post("linkedin");
            $instagram = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name = $this->input->post("father_name");
            $surname = $this->input->post("surname");
            $mother_name = $this->input->post("mother_name");
            $note = $this->input->post("note");
            $epf_no = $this->input->post("epf_no");
            $stn_title = $this->input->post("stn_title");

            $pf = $this->input->post('pf');
            $tds = $this->input->post('tds');
            $bonus = $this->input->post('bonus');
            $account_balance = $this->input->post('account_balance', 0);
            $account_balance_type = $this->input->post('account_balance_type', 'debit');

            $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_insert = array(
                'password' => $this->enc_lib->passHashEnc($password),
                'employee_id' => $employee_id,
                'department' => $department,
                'designation' => $designation,
                'qualification' => $qualification,
                'work_exp' => $work_exp,
                'name' => $name,
                'contact_no' => $contact_no,
                'emergency_contact_no' => $emergency_no,
                'email' => $email,
                'dob_bs' => $dob_bs,
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'marital_status' => $marital_status,
                'date_of_joining' => date('Y-m-d', $this->customlib->datetostrtotime($date_of_joining)),
                'date_of_joining_bs' => $date_of_joining_bs,
                'date_of_leaving' => '',
                'local_address' => $address,
                'permanent_address' => $permanent_address,
                'note' => $note,
                'surname' => $surname,
                'mother_name' => $mother_name,
                'father_name' => $father_name,
                'gender' => $gender,
                'account_title' => $account_title,
                'bank_account_no' => $bank_account_no,
                'bank_name' => $bank_name,
                'pan_no' => $pan_no,
                'cit_no' => $cit_no,
                'bank_branch' => $bank_branch,
                'payscale' => '',
                'basic_salary' => $basic_salary,
                'epf_no' => $epf_no,
                'contract_type' => $contract_type,
                'shift' => $shift,
                'location' => $location,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'linkedin' => $linkedin,
                'instagram' => $instagram,
                'stn_title' => $stn_title,
                'is_active' => 1,
                'pf' => $pf,
                'tds' => $tds,
                'bonus' => $bonus,
                'account_balance'=>$account_balance,
                'account_balance_type'=>$account_balance_type
            );
            if (!empty($date_of_leaving_bs)) {
                $data_insert['date_of_leaving_bs'] = $date_of_leaving_bs;
                $data_insert['date_of_leaving'] = date('Y-m-d', $this->customlib->datetostrtotime($date_of_leaving));
            }
            $leave_type = $this->input->post('leave_type');
            $leave_array = array();
            foreach ($leave_type as $leave_key => $leave_value) {
                $leave_array[] = array(
                    'staff_id' => 0,
                    'leave_type_id' => $leave_value,
                    'alloted_leave' => $this->input->post('alloted_leave_' . $leave_value)
                );
            }


            $role_array = array('role_id' => $this->input->post('role'), 'staff_id' => 0);

            $account_balance=$data_insert['account_balance'];
            $account_balance_type=$data_insert['account_balance_type'];
            unset($data_insert['account_balance']);unset($data_insert['account_balance_type']);
            $insert_id = $this->staff_model->batchInsert($data_insert, $role_array, $leave_array);
            $staff_id = $insert_id;
            $data_insert['account_balance']=$account_balance;
            $data_insert['account_balance_type']=$account_balance_type;
            Neema_events::trigger('on_staff_create', array_merge($data_insert, array('id' => $insert_id)));

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/staff_images/" . $img_name);
                $data_img = array('id' => $staff_id, 'image' => $img_name);
                $this->staff_model->add($data_img);
            }

            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = 'resume';
                $resume = "resume" . $staff_id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $resume;
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
            } else {

                $resume = "";
            }

            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["second_doc"]["name"]);
                $first_title = 'joining_letter';
                $joining_letter = "joining_letter" . $staff_id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $joining_letter;
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
            } else {

                $joining_letter = "";
            }

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["third_doc"]["name"]);
                $first_title = 'resignation_letter';
                $resignation_letter = "resignation_letter" . $staff_id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $resignation_letter;
                move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
            } else {

                $resignation_letter = "";
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = 'Signature Image';
                $fourth_doc = "signature_image_" . $staff_id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $fourth_doc;
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
            } else {
                $fourth_title = "";
                $fourth_doc = "";
            }


            $data_doc = array('id' => $staff_id, 'resume' => $resume, 'joining_letter' => $joining_letter, 'resignation_letter' => $resignation_letter, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);
            $this->staff_model->add($data_doc);

            //===================
            if ($staff_id) {

                $teacher_login_detail = array('id' => $staff_id, 'credential_for' => 'staff', 'username' => $email, 'password' => $password, 'contact_no' => $contact_no, 'email' => $email);

                $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
            }

            //==========================


            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('saved_successfully') . '</div>');

            redirect('admin/staff');
        }
    }

    function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/staff_import_sample.csv";
        $data = file_get_contents($filepath);
        $name = 'import_staffs_sample_file.csv';

        force_download($name, $data);
    }

    public function prepareDataForImport($result, $phase = 1)
    {
        $designation = $this->staff_model->getStaffDesignation();
        $department = $this->staff_model->getDepartment();
        $genderList = $this->customlib->getGender();
        $roles = $this->role_model->get();
        $roleArray = array();
        foreach ($roles as $eachrole) {
            $roleArray[$eachrole['id']] = $eachrole['name'];
        }
        $designationArray = array();
        $departmentArray = array();
        foreach ($designation as $eachdesignation) {
            $designationArray[$eachdesignation['id']] = $eachdesignation['designation'];
        }
        foreach ($department as $eachdepartment) {
            $departmentArray[$eachdepartment['id']] = $eachdepartment['department_name'];
        }
        $idArray = array();
        $emailArray = array();
        $emailArrayUnique = array();
        $idArrayUnique = array();
        $titleArray = array('Mr.', 'Mrs.', 'Miss.', 'None');
        $maritalStatusArray = array('Single', 'Married', 'Widowed', 'Separated');
        $contractTypeArray = array('Full Time', 'Part Time', 'Permanent', 'Provisional', 'Probation');
        $invalidRole = 0;
        $invalidGender = 0;
        $invalidTitle = 0;
        $invaliddesignation = 0;
        $invaliddepartment = 0;
        $invalidDateFormat = 0;
        $empty = 0;


        foreach ($result as $key => $eachresult) {

            $result[$key]['error'] = 0;
            $result[$key]['reason'] = '';

            array_push($emailArray, '"' . trim($eachresult['email']) . '"');
            array_push($idArray, '"' . trim($eachresult['staff_id']) . '"');
            if (!array_search(trim($eachresult['role']), $roleArray)) {
                $invalidRole += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Role does not exist';
            }
            if ($phase == 2) {
                $result[$key]['role'] = array_search(trim($eachresult['role']), $roleArray);
            }
            if (!array_search(trim($eachresult['department']), $departmentArray)) {
                $invaliddepartment += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Department does not exist';
            }
            if ($phase == 2) {
                $result[$key]['department'] = array_search(trim($eachresult['department']), $departmentArray);
            }
            if (!array_search(trim($eachresult['designation']), $designationArray)) {
                $invaliddesignation += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Designation does not exist';
            }
            if ($phase == 2) {
                $result[$key]['designation'] = array_search(trim($eachresult['designation']), $designationArray);
            }

            if (!in_array(trim($eachresult['title']), $titleArray)) {
                $invalidTitle += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Invalid Name title';
            }
            if (!in_array(trim(trim($eachresult['contract_type'])), $contractTypeArray)) {
                if (trim($eachresult['contract_type']) == '') {
                    $eachresult['contract_type'] = "";
                } else {
                    $invalidTitle += 1;
                    $result[$key]['error'] = 1;
                    $result[$key]['reason'] = 'Invalid Contract Type';
                }
            }
            if (!in_array(trim($eachresult['marital_status']), $maritalStatusArray)) {
                if (trim($eachresult['marital_status']) == '') {
                    $eachresult['marital_status'] = "Not Specified";
                } else {
                    $invalidTitle += 1;
                    $result[$key]['error'] = 1;
                    $result[$key]['reason'] = 'Invalid marital status';
                }
            }
            if (!array_search(trim($eachresult['gender']), array("Male" => "Male", "Female" => "Female"))) {
                $invalidGender += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Invalid Gender "' . $eachresult['gender'] . '"';
            }

            if (($eachresult['staff_id'] == '') || ($eachresult['role'] == '')
                || $eachresult['title'] == '' || $eachresult['first_name'] == '' ||
                $eachresult['gender'] == '' || !isset($eachresult['date_of_birth'])
                || $eachresult['email'] == '' || $eachresult['date_of_joining'] == '') {
                $empty += 1;
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Some of the the Required fields not filled';
            }

            $tmp_parts_dob = explode('/', trim($eachresult['date_of_birth']));
            if (count($tmp_parts_dob) != 3) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Birth Date field is in invalid format';
            }
            if ($tmp_parts_dob[0] > 32 || $tmp_parts_dob[0] < 1 || $tmp_parts_dob[1] > 12 || $tmp_parts_dob[1] < 1 || strlen($tmp_parts_dob[2]) > 4) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Birth Date field is invalid';
            }
            $tmp_parts_join = explode('/', trim($eachresult['date_of_joining']));
            if (count($tmp_parts_join) != 3) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Date of joining field is in invalid format';
            }

            if ($tmp_parts_join[0] > 32 || $tmp_parts_join[0] < 1 || $tmp_parts_join[1] > 12 || $tmp_parts_join[1] < 1 || strlen($tmp_parts_dob[2]) > 4) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Date of joining is invalid';
            }

            if (trim($eachresult['date_of_leaving']) != '') {
                $tmp_parts_join = explode('/', trim($eachresult['date_of_leaving']));
                if (count($tmp_parts_join) != 3) {
                    $result[$key]['error'] = 1;
                    $result[$key]['reason'] = 'Date of leaving field is in invalid format';
                }

                if ($tmp_parts_join[0] > 32 || $tmp_parts_join[0] < 1 || $tmp_parts_join[1] > 12 || $tmp_parts_join[1] < 1 || strlen($tmp_parts_dob[2]) > 4) {
                    $result[$key]['error'] = 1;
                    $result[$key]['reason'] = 'Date of leaving is invalid';
                }
            }

        }

        $idCountArray = array_count_values($idArray);
        $emailCountArray = array_count_values($emailArray);
        $emailArrayUnique = array_unique($emailArray);
        $idArrayUnique = array_unique($idArray);
        $duplicates = $this->staff_model->checkDuplicate($idArray, $emailArray);
        $countDuplicate = $duplicates['count'];
        foreach ($result as $key => $eachresult) {
            if ($idCountArray['"' . trim($eachresult['email']) . '"'] > 1) {
                $result[$key]['error'] = 0;
                $result[$key]['reason'] = 'Duplicate email';
            }
            if ($idCountArray['"' . trim($eachresult['staff_id']) . '"'] > 1) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Duplicate Staff Ids';
            }
            if (array_key_exists('"' . trim($eachresult['email']) . '"', $duplicates['data'])) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Email has been already taken';
            }
            if (array_key_exists('"' . trim($eachresult['staff_id']) . '"', $duplicates['data'])) {
                $result[$key]['error'] = 1;
                $result[$key]['reason'] = 'Staff Id has been already taken';
            }
        }
        return $result;
    }

    public function import()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/import');
        $fields = array('staff_id', 'role', 'department', 'designation', 'title', 'first_name', 'gender', 'date_of_birth',
            'date_of_joining', 'email', 'qualification', 'work_experience', 'surname', 'father_name',
            'mother_name', 'contact_no', 'emergency_contact_number', 'marital_status',
            'permanent_address', 'note', 'pan_no', 'local_address', 'account_title', 'bank_account_no', 'bank_name',
            'bank_branch', 'basic_salary', 'epf_no', 'contract_type', 'shift',
            'location', 'facebook', 'twitter', 'linkedin', 'instagram', 'pf', 'tds', 'bonus', 'date_of_leaving',
            'cit_no','account_balance','account_balance_type'
        );
        $data['fields'] = $fields;
        if (!isset($_FILES["file"]) || empty($_FILES['file']['name'])) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/import', $data);
            $this->load->view('layout/footer', $data);

        } else {

            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);
                $result = $this->prepareDataForImport($result, 1);
                $fields = array('staff_id', 'role', 'department', 'designation', 'title', 'first_name', 'gender', 'date_of_birth',
                    'date_of_joining', 'email', 'qualification', 'work_experience', 'surname', 'father_name',
                    'mother_name', 'contact_no', 'emergency_contact_number', 'marital_status',
                    'permanent_address', 'note', 'pan_no', 'local_address', 'account_title', 'bank_account_no', 'bank_name',
                    'bank_branch', 'basic_salary', 'epf_no', 'contract_type', 'shift',
                    'location', 'facebook', 'twitter', 'linkedin', 'instagram', 'pf', 'tds', 'bonus', 'date_of_leaving',
                    'cit_no','account_balance','account_balance_type', 'error_status', 'error_cause');
                $result['staffData'] = $result;
                $result['fields'] = $fields;

                $this->load->view('layout/header', $result);
                $this->load->view('admin/staff/import_preview', $result);
                $this->load->view('layout/footer', $result);

            } else {
                $this->session->set_flashdata('msg', array('message' => "Only CSV file is supported", 'type' => 'danger'));
                redirect("admin/staff/import");
            }

        }
    }


    function import_confirm()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $data = $this->input->post('data');
            $result = json_decode($data, true);

            $result = $this->prepareDataForImport($result, 2);
            $count = 0;
            $totalCount = 0;
            foreach ($result as $eachresult) {
                $totalCount += 1;
                if ($eachresult['error'] == 0) {
                    if ($this->datechooser == 'bs') {
                        $dobdate = $this->datesFromBS(trim($eachresult['date_of_birth']));
                        $dob_bs = $dobdate['bs'];
                        $dob = $dobdate['ad'];
                        $dojdate = $this->datesFromBS(trim($eachresult['date_of_joining']));
                        $date_of_joining_bs = $dojdate['bs'];
                        $date_of_joining = $dojdate['ad'];
                        if (trim($eachresult['date_of_leaving']) != '') {
                            $doldate = $this->datesFromBS(trim($eachresult['date_of_leaving']));
                            $date_of_leaving_bs = $doldate['bs'];
                            $date_of_leaving = $doldate['ad'];
                        }

                    } else {
                        $dobdate = $this->datesFromAD(trim($eachresult['date_of_birth']));
                        $dob = $dobdate['ad'];
                        $dob_bs = $dobdate['bs'];
                        $dojdate = $this->datesFromAD(trim($eachresult['date_of_joining']));
                        $date_of_joining_bs = $dojdate['bs'];
                        $date_of_joining = $dojdate['ad'];
                        if (trim($eachresult['date_of_leaving']) != '') {
                            $doldate = $this->datesFromAD(trim($eachresult['date_of_leaving']));
                            $date_of_leaving_bs = $doldate['bs'];
                            $date_of_leaving = $doldate['ad'];
                        }
                    }
                    $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                    $data = array(
                        'employee_id' => trim($eachresult['staff_id']),
                        'department' => trim($eachresult['department']),
                        'designation' => trim($eachresult['designation']),
                        'stn_title' => trim($eachresult['title']),
                        'name' => trim($eachresult['first_name']),
                        'gender' => trim($eachresult['gender']),
                        'dob' => $dob,
                        'dob_bs' => $dob_bs,
                        'date_of_joining' => $date_of_joining,
                        'date_of_joining_bs' => $date_of_joining_bs,
                        'email' => trim($eachresult['email']),
                        'is_active' => 1,
                        'password' => $this->enc_lib->passHashEnc($password),
                        'qualification' => isset($eachresult['qualification']) ? trim($eachresult['qualification']) : '',
                        'work_exp' => isset($eachresult['work_experience']) ? trim($eachresult['work_experience']) : '',
                        'surname' => isset($eachresult['surname']) ? trim($eachresult['surname']) : '',
                        'father_name' => isset($eachresult['father_name']) ? trim($eachresult['father_name']) : '',
                        'mother_name' => isset($eachresult['mother_name']) ? trim($eachresult['mother_name']) : '',
                        'contact_no' => isset($eachresult['contact_no']) ? trim($eachresult['contact_no']) : '',
                        'emergency_contact_no' => isset($eachresult['emergency_contact_number']) ? trim($eachresult['emergency_contact_number']) : '',
                        'marital_status' => isset($eachresult['marital_status']) ? trim($eachresult['marital_status']) : '',
                        'local_address' => isset($eachresult['local_address']) ? trim($eachresult['local_address']) : '',
                        'permanent_address' => isset($eachresult['permanent_address']) ? trim($eachresult['permanent_address']) : '',
                        'note' => isset($eachresult['note']) ? trim($eachresult['note']) : '',
                        'pan_no' => isset($eachresult['pan_no']) ? trim($eachresult['pan_no']) : '',
                        'account_title' => isset($eachresult['account_title']) ? trim($eachresult['account_title']) : '',
                        'bank_account_no' => isset($eachresult['bank_account_no']) ? trim($eachresult['bank_account_no']) : '',
                        'bank_name' => isset($eachresult['bank_name']) ? trim($eachresult['bank_name']) : '',
                        'bank_branch' => isset($eachresult['bank_branch']) ? trim($eachresult['bank_branch']) : '',
                        'basic_salary' => isset($eachresult['basic_salary']) ? trim($eachresult['basic_salary']) : '',
                        'epf_no' => isset($eachresult['epf_no']) ? trim($eachresult['epf_no']) : '',
                        'contract_type' => isset($eachresult['contract_type']) ? trim($eachresult['contract_type']) : '',
                        'shift' => isset($eachresult['shift']) ? trim($eachresult['shift']) : '',
                        'location' => isset($eachresult['location']) ? trim($eachresult['location']) : '',
                        'facebook' => isset($eachresult['facebook']) ? trim($eachresult['facebook']) : '',
                        'twitter' => isset($eachresult['twitter']) ? trim($eachresult['twitter']) : '',
                        'linkedin' => isset($eachresult['linkedin']) ? trim($eachresult['linkedin']) : '',
                        'instagram' => isset($eachresult['instagram']) ? trim($eachresult['instagram']) : '',
                        'pf' => isset($eachresult['pf']) ? trim($eachresult['pf']) : '',
                        'tds' => isset($eachresult['tds']) ? trim($eachresult['tds']) : '',
                        'bonus' => isset($eachresult['bonus']) ? trim($eachresult['bonus']) : '',
                        'date_of_leaving' => isset($date_of_leaving) ? trim($date_of_leaving) : '',
                        'date_of_leaving_bs' => isset($date_of_leaving_bs) ? trim($date_of_leaving_bs) : '',
                        'cit_no' => isset($eachresult['cit_no']) ? trim($eachresult['cit_no']) : '',
                        'account_balance' => isset($eachresult['account_balance']) ? trim($eachresult['account_balance']) : 0,
                        'account_balance_type' => isset($eachresult['account_balance_type']) ? trim($eachresult['account_balance_type']) : 'credit',

                    );
                    $insert_id = $this->staff_model->batch_insert($data, trim($eachresult['role']));
                    if ($insert_id) {
                        $count++;
                        $teacher_login_detail = array('id' => $insert_id, 'credential_for' => 'staff', 'username' => $eachresult['email'], 'password' => $password, 'contact_no' => $eachresult['contact_no'], 'email' => $eachresult['email']);
                        $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
                    }
                }
            }
            $this->session->set_flashdata('msg', array('message' => $count . " of " . $totalCount . " data imported Successfully", 'type' => 'success'));
            redirect("admin/staff/import");

        }
        redirect("admin/staff/import");

    }


    public function datesFromBS($bsdate)
    {
        $tmp_parts = explode('/', trim($bsdate));
        if (count($tmp_parts) == 3) {
            try {
                $this->bikram_sambat->setNepaliDate($tmp_parts[2], $tmp_parts[1], $tmp_parts[0]);
                $date_bs = $this->bikram_sambat->toNepaliString();
                $date = $this->bikram_sambat->toEnglishString();
            } catch (Exception $e) {
                //
            }
        }
        return array('bs' => $date_bs, 'ad' => $date);
    }

    public function datesFromAD($addate)
    {
        $val = DateTime::createFromFormat('d/m/Y', trim($addate));
        if ($val) {
            $date = $val->format('Y-m-d');
            try {
                $this->bikram_sambat->setEnglishDate($val->format('Y'), $val->format('n'), $val->format('j'));
                $date_bs = $this->bikram_sambat->toNepaliString();
            } catch (Exception $e) {
                //
            }
        }
        return array('bs' => $date_bs, 'ad' => $date);
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Staff';
        $data['id'] = $id;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $payscaleList = $this->staff_model->getPayroll();
        $leavetypeList = $this->staff_model->getLeaveType();
        $data["leavetypeList"] = $leavetypeList;
        $data["payscaleList"] = $payscaleList;
        $staffRole = $this->staff_model->getStaffRole();
        $data["getStaffRole"] = $staffRole;
        $designation = $this->staff_model->getStaffDesignation();
        $data["designation"] = $designation;
        $department = $this->staff_model->getDepartment();
        $data["department"] = $department;
        $marital_status = $this->marital_status;
        $data["marital_status"] = $marital_status;
        $data['title'] = 'Edit Staff';
        $staff = $this->staff_model->get($id);
        if (!$this->rbac->isSuperAdmin() && $staff['user_type'] == 'Super Admin') {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Access denied</div>');
            redirect('admin/staff/');
        }
        $data['staff'] = $staff;
        $data["contract_type"] = $this->contract_type;

        $staffLeaveDetails = $this->staff_model->getLeaveDetails($id);
        $data['staffLeaveDetails'] = $staffLeaveDetails;
        $data['name_titles'] = $this->name_titles;


        $resume = $this->input->post("resume");
        $joining_letter = $this->input->post("joining_letter");
        $resignation_letter = $this->input->post("resignation_letter");
        $other_document_file = $this->input->post("other_document_file");

        $this->form_validation->set_rules('name', 'lang:name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', 'lang:role', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'lang:gender', 'trim|required|xss_clean');
        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('dob_bs', 'lang:date_of_birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date_of_joining_bs', 'lang:date_of_joining', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('dob', 'lang:date_of_birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date_of_joining', 'lang:date_of_joining', 'trim|required|xss_clean');
        }
        //$this->form_validation->set_rules(
        //      'employee_id', 'Employee Id', array('required',
        //array('check_exists', array($this->staff_model, 'valid_employee_id'))
        //      )
        //);

        $this->form_validation->set_rules(
            'email', 'lang:email', array('required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id'))
            )
        );
        $this->form_validation->set_rules(
            'employee_id', 'lang:staff_id', array('required',
                array('check_exists', array($this->staff_model, 'valid_employee_id'))
            )
        );
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            if ($this->datechooser == 'bs') {
                $this->load->view('admin/staff/staffedit_bs', $data);
            } else {
                $this->load->view('admin/staff/staffedit', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {

            $employee_id = $this->input->post("employee_id");
            $department = $this->input->post("department");
            $designation = $this->input->post("designation");
            $role = $this->input->post("role");
            if (!$this->rbac->isSuperAdmin() && $role == 7) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Access denied</div>');

                redirect('admin/staff');
            }
            $name = $this->input->post("name");
            $gender = $this->input->post("gender");
            $marital_status = $this->input->post("marital_status");
            $dob = $this->input->post("dob");
            $dob_bs = $this->input->post("dob_bs");
            $contact_no = $this->input->post("contactno");
            $emergency_no = $this->input->post("emergency_no");
            $email = $this->input->post("email");
            $date_of_joining = $this->input->post("date_of_joining");
            $date_of_joining_bs = $this->input->post("date_of_joining_bs");
            $date_of_leaving = $this->input->post("date_of_leaving");
            if (!empty($date_of_leaving)) {
                $date_of_leaving = date("Y-m-d", strtotime($date_of_leaving));
            } else {
                $date_of_leaving = '';
            }
            $date_of_leaving_bs = $this->input->post("date_of_leaving_bs");
            $address = $this->input->post("address");
            $qualification = $this->input->post("qualification");
            $work_exp = $this->input->post("work_exp");

            $basic_salary = $this->input->post('basic_salary');
            $account_title = $this->input->post("account_title");
            $bank_account_no = $this->input->post("bank_account_no");
            $bank_name = $this->input->post("bank_name");
            $pan_no = $this->input->post("pan_no");
            $cit_no = $this->input->post("cit_no");
            $bank_branch = $this->input->post("bank_branch");
            $contract_type = $this->input->post("contract_type");
            $shift = $this->input->post("shift");
            $location = $this->input->post("location");
            $leave = $this->input->post("leave");
            $facebook = $this->input->post("facebook");
            $twitter = $this->input->post("twitter");
            $linkedin = $this->input->post("linkedin");
            $instagram = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name = $this->input->post("father_name");
            $surname = $this->input->post("surname");
            $mother_name = $this->input->post("mother_name");
            $note = $this->input->post("note");
            $epf_no = $this->input->post("epf_no");
            $stn_title = $this->input->post("stn_title");
            $pf = $this->input->post('pf');
            $tds = $this->input->post('tds');
            $bonus = $this->input->post('bonus');

            $data1 = array('id' => $id,
                'employee_id' => $employee_id,
                'department' => $department,
                'designation' => $designation,
                'qualification' => $qualification,
                'work_exp' => $work_exp,
                'name' => $name,
                'contact_no' => $contact_no,
                'emergency_contact_no' => $emergency_no,
                'email' => $email,
                'dob_bs' => $dob_bs,
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'marital_status' => $marital_status,
                'date_of_joining_bs' => $date_of_joining_bs,
                'date_of_leaving_bs' => $date_of_leaving_bs,
                'date_of_joining' => date('Y-m-d', $this->customlib->datetostrtotime($date_of_joining)),
                'date_of_leaving' => $date_of_leaving,
                'local_address' => $address,
                'permanent_address' => $permanent_address,
                'note' => $note,
                'surname' => $surname,
                'mother_name' => $mother_name,
                'father_name' => $father_name,
                'gender' => $gender,
                'account_title' => $account_title,
                'bank_account_no' => $bank_account_no,
                'bank_name' => $bank_name,
                'pan_no' => $pan_no,
                'cit_no' => $cit_no,
                'bank_branch' => $bank_branch,
                'payscale' => '',
                'basic_salary' => $basic_salary,
                'epf_no' => $epf_no,
                'contract_type' => $contract_type,
                'shift' => $shift,
                'location' => $location,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'linkedin' => $linkedin,
                'instagram' => $instagram,
                'stn_title' => $stn_title,
                'pf' => $pf,
                'tds' => $tds,
                'bonus' => $bonus
            );
            $insert_id = $this->staff_model->add($data1);
            Neema_events::trigger('on_staff_edit', $data1);

            $role_id = $this->input->post("role");

            $role_data = array('staff_id' => $id, 'role_id' => $role_id);

            $this->staff_model->update_role($role_data);

            $leave_type = $this->input->post("leave_type_id");

            $alloted_leave = $this->input->post("alloted_leave");
            $altid = $this->input->post("altid");
            if (!empty($leave_type)) {
                $i = 0;
                foreach ($leave_type as $key => $value) {

                    if (!empty($altid[$i])) {

                        $data2 = array('staff_id' => $id,
                            'leave_type_id' => $leave_type[$i],
                            'id' => $altid[$i],
                            'alloted_leave' => $alloted_leave[$i],
                        );
                    } else {

                        $data2 = array('staff_id' => $id,
                            'leave_type_id' => $leave_type[$i],
                            'alloted_leave' => $alloted_leave[$i],
                        );
                    }


                    $this->staff_model->add_staff_leave_details($data2);
                    $i++;
                }
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/staff_images/" . $img_name);
                //$data_img = array('id' => $id, 'image' => $img_name);
                //$this->staff_model->add($data_img);
                $staff_image = $img_name;
            } else {
                $staff_image = $this->input->post('old_file');
            }


            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = 'resume';
                $resume_doc = "resume" . $id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $resume_doc;
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
            } else {

                $resume_doc = $resume;
            }

            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["second_doc"]["name"]);
                $first_title = 'joining_letter';
                $joining_letter_doc = "joining_letter" . $id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $joining_letter_doc;
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
            } else {

                $joining_letter_doc = $joining_letter;
            }

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["third_doc"]["name"]);
                $first_title = 'resignation_letter';
                $resignation_letter_doc = "resignation_letter" . $id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $resignation_letter_doc;
                move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
            } else {

                $resignation_letter_doc = $resignation_letter;
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = 'Signature Image';
                $fourth_doc = "signature_image_" . $id . '.' . $fileInfo['extension'];
                $img_name = $uploaddir . $fourth_doc;
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
            } else {
                $fourth_title = 'Other Document';
                $fourth_doc = $other_document_file;
            }

            $data_doc = array('id' => $id, 'resume' => $resume_doc, 'joining_letter' => $joining_letter_doc, 'resignation_letter' => $resignation_letter_doc, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);
            $data_doc['image'] = $staff_image;

            $this->staff_model->add($data_doc);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('Record Updated Successfully') . '</div>');
            redirect('admin/staff/edit/' . $id);
        }
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Staff List';
        $this->staff_model->remove($id);
        $student_id = $id;
        $role = 6;
        $student = $this->student_model_api->getNemmaStudentDetail($student_id, $role);
        $data = array(
            'InstituteCode' => $student->institute_code,
            'InstituteUserID' => $student->institute_userid,
            'RegistrationCode' => $student->registration_code,
            'InstituteRoleID' => $student->institute_roleid,
        );
        $url = Neema_Url . 'DisconnectFromSchool/' . Secret_key;
        $content = json_encode($data);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($response['Success'] = true) {

            $this->student_model_api->deleteNeeemaStudent($student_id, $role);
            $this->session->set_flashdata('msg', '<div student="alert alert-success text-left">Instructor Successfully Disconnect</div>');
            redirect('admin/neemainstructor/search');

        } else {
            $this->session->set_flashdata('msg', '<div student="alert alert-success text-left">Instructor Cannot be Disconnected</div>');
            redirect('admin/neemainstructor/search');
        }

        redirect('admin/staff');
    }

    function disablestaff($id)
    {
        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {

            access_denied();
        }
        $this->staff_model->disablestaff($id);
        redirect('admin/staff/profile/' . $id);
    }

    function enablestaff($id)
    {
        $this->staff_model->enablestaff($id);
        redirect('admin/staff/profile/' . $id);
    }

    function change_password()
    {
        $this->form_validation->set_rules('new_pass', 'lang:password', 'trim|required');
        $this->form_validation->set_rules('confirm_pass', 'lang:confirm_password', 'trim|required|matches[new_pass]');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'new_pass' => form_error('new_pass'),
                'confirm_pass' => form_error('confirm_pass'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('staff_id');
            $password = $this->input->post('confirm_pass');
            //echo $password;
            $data = array(
                //'password' => $password
                'password' => $this->enc_lib->passHashEnc($password)
            );
            $this->staff_model->changePassword($id, $data);
            $msg = $this->lang->line("Password Changed SuccessFully");
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }


        echo json_encode($array);
        //redirect('admin/staff/profile/' . $id);
    }

    function staffLeaveSummary()
    {

        $resultdata = $this->staff_model->getLeaveSummary();
        $data["resultdata"] = $resultdata;


        $this->load->view("layout/header");
        $this->load->view("admin/staff/staff_leave_summary", $data);
        $this->load->view("layout/footer");
    }

    function getEmployeeByRole()
    {

        $role = $this->input->post("role");

        $data = $this->staff_model->getEmployee($role);

        echo json_encode($data);
    }

    function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat) + 1;
    }

    function permission($id)
    {
        $data['title'] = 'Add Role';
        $data['id'] = $id;
        $staff = $this->staff_model->get($id);
        $data['staff'] = $staff;
        $userpermission = $this->userpermission_model->getUserPermission($id);
        $data['userpermission'] = $userpermission;

        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $staff_id = $this->input->post('staff_id');
            $prev_array = $this->input->post('prev_array');
            if (!isset($prev_array)) {
                $prev_array = array();;
            }
            $module_perm = $this->input->post('module_perm');
            $delete_array = array_diff($prev_array, $module_perm);
            $insert_diff = array_diff($module_perm, $prev_array);
            $insert_array = array();
            if (!empty($insert_diff)) {

                foreach ($insert_diff as $key => $value) {
                    $insert_array[] = array(
                        'staff_id' => $staff_id,
                        'permission_id' => $value
                    );
                }
            }

            $this->userpermission_model->getInsertBatch($insert_array, $staff_id, $delete_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('saved_successfully') . '</div>');
            redirect('admin/staff');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/staff/permission', $data);
        $this->load->view('layout/footer');
    }

    public function leaverequest()
    {
        if (!$this->rbac->hasPrivilege('apply_leave', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/leaverequest');
        $userdata = $this->customlib->getUserData();

        $leave_request = $this->leaverequest_model->user_leave_request($userdata["id"]);

        $data["leave_request"] = $leave_request;

        // $LeaveTypes = $this->staff_model->getLeaveType();
        $LeaveTypes = $this->leaverequest_model->allotedLeaveType($userdata["id"]);
        $data["staff_id"] = $userdata["id"];
        $data["leavetype"] = $LeaveTypes;

        $staffRole = $this->staff_model->getStaffRole();
        $data["staffrole"] = $staffRole;
        $data["status"] = $this->status;


        $this->load->view("layout/header", $data);
        if ($this->datechooser == 'bs') {
            $this->load->view("admin/staff/leaverequest_bs", $data);
        } else {
            $this->load->view("admin/staff/leaverequest", $data);
        }
        $this->load->view("layout/footer", $data);
    }

}

?>