<?php

/**
 * 
 */
class Staffattendance extends Admin_Controller {

    private $holidays;
    private $weekend;

    function __construct() {

        parent::__construct();
        $this->load->helper('file');
        //  $this->lang->load('message', 'english');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance = $this->config->item('staffattendance');
        $this->load->model("staffattendancemodel");
        $this->load->model("staff_model");
        $this->load->model("payroll_model");
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
        $settings = $this->setting_model->getSetting();
        $weekend = $settings->public_holiday;
        $this->weekend = array_map(function ($d) {
            return strtolower($d);
        }, explode(', ', $weekend));
    }

    function index() {

        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view') )) {
            access_denied();
        }
        //  if(!$this->rbac->hasPrivilege('staff_attendance','can_add')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $data['title'] = 'Staff Attendance List';
        $data['title_list'] = 'Staff Attendance List';
        $user_type = $this->staff_model->getStaffRole();

        $data['classlist'] = $user_type;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $user_type_id = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        if (!(isset($user_type_id))) {

            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/staffattendance/staffattendancelist_bs', $data);
            } else {
                $this->load->view('admin/staffattendance/staffattendancelist', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {

            $user_type = $this->input->post('user_id');
            $date = $this->input->post('date');
            $date_bs = $this->input->post('date_bs');
            list($year_bs, $month_bs, $day_bs) = explode('-', $date_bs);
            $user_list = $this->staffattendancemodel->get();
            $data['userlist'] = $user_list;
            $data['class_id'] = $user_list;
            $data['user_type_id'] = $user_type_id;
            $data['section_id'] = "";
            $data['date'] = $date;
            $data['date_bs'] = $date_bs;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');

            $this->session->set_flashdata('msg', '');

            if ($search == "saveattendence") {

                $user_type_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($user_type_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);

                    if ($checkForUpdate != 0) {


                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'staff_id' => $value,
                                'staff_attendance_type_id' => 5,
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs'=>$date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'staff_id' => $value,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        }

                        $insert_id = $this->staffattendancemodel->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'staff_id' => $value,
                                'staff_attendance_type_id' => 5,
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                                'remark' => ''
                            );
                        } else {


                            $arr = array(
                                'staff_id' => $value,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                                'remark' => $this->input->post("remark" . $value),
                            );
                        }

                        $insert_id = $this->staffattendancemodel->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['staff_attendance_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }



                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {

                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');

                redirect('admin/staffattendance/index');
            }

            $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->staffattendancemodel->searchAttendenceUserType($user_type, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;


            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/staffattendance/staffattendancelist_bs', $data);
            } else {
                $this->load->view('admin/staffattendance/staffattendancelist', $data);
            }
            $this->load->view('layout/footer', $data);
        }
    }

    public function attendancereport()
    {
        if($this->datechooser == 'bs') {
            $this->attendancereport_bs();
        } else {
            $this->attendancereport_ad();
        }
    }

    private function _is_holiday($date)
    {
        $holidays = array_keys($this->holidays);
        $ad_date = DateTime::createFromFormat('Y-n-j', $date);
        if ($ad_date) {
            return in_array(strtolower($ad_date->format('l')), $this->weekend)
                || in_array($ad_date->format('Y-m-d'), $holidays);
        }
        return false;
    }

    private function attendancereport_bs()
    {
        if (!$this->rbac->hasPrivilege('staff_attendance_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/attendancereport');
        $attendencetypes = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data['title'] = 'Attendance Report';
        $data['title_list'] = 'Attendance';
        $data['monthlist'] = $this->customlib->getBSMonths();
        //$data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $current_bs_session = $this->setting_model->getCurrentSessionNameBS();
        $bs_session_year = explode('-', $current_bs_session);
        $data['yearlist'] = array(
            array('year' => $bs_session_year[0] - 57)
        );
        $data['date'] = "";
        $data['month_selected'] = "";
        $data["role_selected"] = "";
        $data['year_selected'] = date('Y');
        $role = $this->input->post("role");
        $this->form_validation->set_rules('month', 'lang:month', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport_bs', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $month = $this->input->post('month');
            $searchyear = $this->input->post('year');
            $data['year_selected'] = $searchyear;
            $searchyear += 57;
            $data['month_selected'] = $month;
            $data["role_selected"] = $role;
            $month_number = $month + 1;

            $num_of_days = $this->bikram_sambat->getLastDayOf($searchyear, $month_number);
            $attendence_array = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            $monthAttendance = array();
            $attendances = $this->staffattendancemodel->getAttendanceReportBS($role, $searchyear, $month_number);
            $staffs = $this->staffattendancemodel->getStaffByRole($role);
            $this->bikram_sambat->setNepaliDate($searchyear, $month_number, 1);
            $first_ad_date_of_bs_month = $this->bikram_sambat->toFormattedEnglishString();
            $this->bikram_sambat->setNepaliDate($searchyear, $month_number, $num_of_days);
            $last_ad_date_of_bs_month = $this->bikram_sambat->toFormattedEnglishString();
            $this->holidays = $this->calendar_model->getHolidaysBetween($first_ad_date_of_bs_month, $last_ad_date_of_bs_month);
            for ($i = 1; $i <= $num_of_days; $i++) {
                $this->bikram_sambat->setNepaliDate($searchyear, $month_number, $i);
                $att_date = $this->bikram_sambat->toFormattedEnglishString();
                $date_bs = $searchyear . '-' . $month_number . '-' . $i;
                $attendence_array[] = array(
                    'day_bs' => sprintf("%02d", $i),
                    'date_bs' => $date_bs,
                    'date_ad' => $att_date,
                    'day' => strtolower(date('l', $this->customlib->dateyyyymmddTodateformat($att_date))),
                    'is_holiday' => $this->_is_holiday($att_date)
                );

                $res = array();
                foreach ($staffs as $staff) {
                    $f = array_filter($attendances, function ($a) use ($i, $staff) {
                        return $a['day_bs'] == $i && $a['id'] == $staff['id'];
                    });
                    if (!empty($f)) {
                        $t = current($f);
                    } else {
                        $t = array(
                            'staff_attendance_type_id' => '',
                            'att_type' => '',
                            'key' => '',
                            'remark' => '',
                        );
                    }
                    $res[] = array_merge($staff, $t);
                }
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['id']] = $result_v;
                }
                $date_result[$date_bs] = $s;
            }

            foreach ($staffs as $result_k => $result_v) {
                $r = array();
                foreach ($this->staff_attendance as $att_key => $att_value) {
                    $s = array_filter($attendances, function ($tt) use ($att_value, $result_v) {
                        if ($tt['staff_attendance_type_id'] == 5) {
                            $o = DateTime::createFromFormat('Y-m-d', $tt['date']);
                            if ($o && (in_array(strtolower($o->format('l')), $this->weekend) || isset($this->holidays[$o->format('Y-m-d')]))) {
                                return false;
                            }
                        }
                        return $tt['id'] == $result_v['id'] && $tt['staff_attendance_type_id'] == $att_value;
                    });
                    $r[$att_key] = count($s);
                }
                $monthAttendance[] = array(
                    $result_v['id'] => $r
                );
            }
            $data['monthAttendance'] = $monthAttendance;
            $data['resultlist'] = $date_result;
            if (!empty($searchyear)) {
                $data['attendence_array'] = $attendence_array;
                $data['student_array'] = $staffs;
            } else {

                $data['attendence_array'] = array();
                $data['student_array'] = array();
            }
            $data['holidays'] = $this->holidays;
            $data['total_open_days'] = array_reduce($attendence_array, function ($t, $d) {
                if (!$d['is_holiday']) {
                    $t++;
                }
                return $t;
            }, 0);
            $data['total_holidays'] = $num_of_days - $data['total_open_days'];
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport_bs', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    private function attendancereport_ad() {
        if (!$this->rbac->hasPrivilege('staff_attendance_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/attendancereport');
        $attendencetypes = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data['title'] = 'Attendance Report';
        $data['title_list'] = 'Attendance';
        $data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $data['date'] = "";
        $data['month_selected'] = "";
        $data["role_selected"] = "";
        $role = $this->input->post("role");
        $this->form_validation->set_rules('month', 'lang:month', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $month = $this->input->post('month');
            $searchyear = $this->input->post('year');
            $data['month_selected'] = $month;
            $data["role_selected"] = $role;
            $staffs = $this->staffattendancemodel->getStaffByRole($role);
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();
            $centenary = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
            $month_number = date("m", strtotime($month));

            if ($month_number >= $startMonth && $month_number <= 12) {
                $year = $centenary . $year_first_substring;
            } else {
                $year = $centenary . $year_second_substring;
            }
            $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
            $attendence_array = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            $monthAttendance = array();
            $attendances = $this->staffattendancemodel->getAttendanceReportAD($role, $searchyear, $month_number);
            $this->holidays = $this->calendar_model->getHolidaysBetween($year . "-" . $month_number . "-01", $year . "-" . $month_number . "-" . $num_of_days);
            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date = $searchyear . "-" . $month_number . "-" . sprintf("%02d", $i);
                $tmp = DateTime::createFromFormat('Y-m-d', $att_date);
                //$attendence_array[] = $att_date;
                $attendence_array[] = array(
                    'date' => $att_date,
                    'day' => strtolower(date('l', $this->customlib->dateyyyymmddTodateformat($att_date))),
                    'is_holiday' => $this->_is_holiday($tmp->format('Y-n-j'))
                );
                $res = array();
                foreach ($staffs as $staff) {
                    $f = array_filter($attendances, function ($a) use ($att_date, $staff) {
                        return $a['date'] == $att_date && $a['id'] == $staff['id'];
                    });
                    if (!empty($f)) {
                        $t = current($f);
                    } else {
                        $t = array(
                            'staff_attendance_type_id' => '',
                            'att_type' => '',
                            'key' => '',
                            'remark' => '',
                        );
                    }
                    $res[] = array_merge($staff, $t);
                }
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['id']] = $result_v;
                }
                $date_result[$att_date] = $s;
            }

            foreach ($staffs as $result_k => $result_v) {
                $r = array();
                foreach ($this->staff_attendance as $att_key => $att_value) {
                    $s = array_filter($attendances, function ($tt) use ($att_value, $result_v) {
                        if ($tt['staff_attendance_type_id'] == 5) {
                            $o = DateTime::createFromFormat('Y-m-d', $tt['date']);
                            if ($o && (in_array(strtolower($o->format('l')), $this->weekend) || isset($this->holidays[$o->format('Y-m-d')]))) {
                                return false;
                            }
                        }
                        return $tt['id'] == $result_v['id'] && $tt['staff_attendance_type_id'] == $att_value;
                    });
                    $r[$att_key] = count($s);
                }
                $monthAttendance[] = array(
                    $result_v['id'] => $r
                );
            }
            $data['monthAttendance'] = $monthAttendance;
            $data['resultlist'] = $date_result;
            if (!empty($searchyear)) {
                $data['attendence_array'] = $attendence_array;
                $data['student_array'] = $staffs;
            } else {

                $data['attendence_array'] = array();
                $data['student_array'] = array();
            }
            $data['holidays'] = $this->holidays;
            $data['total_open_days'] = array_reduce($attendence_array, function ($t, $d) {
                if (!$d['is_holiday']) {
                    $t++;
                }
                return $t;
            }, 0);
            $data['total_holidays'] = $num_of_days - $data['total_open_days'];

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function monthAttendance($st_month, $no_of_months, $emp) {

        $this->load->model("payroll_model");
        $record = array();

        $r = array();
        $month = date('m', strtotime($st_month));
        $year = date('Y', strtotime($st_month));

        foreach ($this->staff_attendance as $att_key => $att_value) {

            $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);

            $r[$att_key] = $s;
        }

        $record[$emp] = $r;

        return $record;
    }

    function profileattendance() {

        $monthlist = $this->customlib->getMonthDropdown();
        $startMonth = $this->setting_model->getStartMonth();
        $data["monthlist"] = $monthlist;
        $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data["role_selected"] = "";
        $j = 0;
        for ($i = 1; $i <= 31; $i++) {

            $att_date = sprintf("%02d", $i);

            $attendence_array[] = $att_date;

            foreach ($monthlist as $key => $value) {

                $datemonth = date("m", strtotime($value));
                $att_dates = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                $date_array[] = $att_dates;
                $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($staff_id = 8, $att_dates);
            }

            $j++;
        }

        $data["resultlist"] = $res;
        $data["attendence_array"] = $attendence_array;
        $data["date_array"] = $date_array;

        $this->load->view("layout/header");
        $this->load->view("admin/staff/staffattendance", $data);
        $this->load->view("layout/footer");
    }

}

?>