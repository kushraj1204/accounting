<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stuattendence extends Admin_Controller {

    private $datechooser;
    private $holidays;
    private $weekend;

    function __construct() {
        parent::__construct();

        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->load->model("classteacher_model");
        $this->datechooser = $this->setting_model->getDatechooser();
        $settings = $this->setting_model->getSetting();
        $weekend = $settings->public_holiday;
        $this->weekend = array_map(function ($d) {
            return strtolower($d);
        }, explode(', ', $weekend));
    }

    function index() {
        //  if(!$this->rbac->hasPrivilege('student_attendance','can_view')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        // if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);


        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/stuattendence/attendenceList_bs', $data);
            } else {
                $this->load->view('admin/stuattendence/attendenceList', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            $date_bs = $this->input->post('date_bs');
            list($year_bs, $month_bs, $day_bs) = explode('-', $date_bs);
            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $data['date_bs'] = $date_bs;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        } else {


                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'date_bs' => $date_bs,
                                'year_bs'=>$year_bs,
                                'month_bs'=>$month_bs,
                                'day_bs'=>$day_bs,
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['attendence_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }
                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {
                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
                redirect('admin/stuattendence/index');
            }
            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;

            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/stuattendence/attendenceList_bs', $data);
            } else {
                $this->load->view('admin/stuattendence/attendenceList', $data);
            }
            $this->load->view('layout/footer', $data);
        }
    }

    function attendencereport() {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'lang:date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/stuattendence/attendencereport_bs', $data);
            } else {
                $this->load->view('admin/stuattendence/attendencereport', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        $arr = array(
                            'id' => $checkForUpdate,
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        $arr = array(
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    }
                }
            }
            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->stuattendence_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/stuattendence/attendencereport_bs', $data);
            } else {
                $this->load->view('admin/stuattendence/attendencereport', $data);
            }
            $this->load->view('layout/footer', $data);
        }
    }

    public function classattendencereport()
    {
        if($this->datechooser == 'bs') {
            $this->classattendencereport_bs();
        } else {
            $this->classattendencereport_ad();
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

    private function classattendencereport_bs() {

        if (!$this->rbac->hasPrivilege('student_attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/classattendencereport');
        $attendencetypes = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['monthlist'] = $this->customlib->getBSMonths();
        $current_bs_session = $this->setting_model->getCurrentSessionNameBS();
        $bs_session_year = explode('-', $current_bs_session);
        $data['yearlist'] = array(
            array('year' => $bs_session_year[0])
        );
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data['month_selected'] = "";
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', 'lang:month', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport_bs', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $month = $this->input->post('month');
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['month_selected'] = $month;
            $students = $this->student_model->searchByClassSection($class, $section);
            $month_number = $month+1;
            $year = $this->input->post('year');
            $num_of_days = $this->bikram_sambat->getLastDayOf($year, $month_number);
            $attendence_array = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            $attendances = $this->stuattendence_model->getAttendanceReportBS($class, $section, $year, $month_number);
            $this->bikram_sambat->setNepaliDate($year, $month_number, 1);
            $first_ad_date_of_bs_month = $this->bikram_sambat->toFormattedEnglishString();
            $this->bikram_sambat->setNepaliDate($year, $month_number, $num_of_days);
            $last_ad_date_of_bs_month = $this->bikram_sambat->toFormattedEnglishString();
            $this->holidays = $this->calendar_model->getHolidaysBetween($first_ad_date_of_bs_month, $last_ad_date_of_bs_month);
            for ($i = 1; $i <= $num_of_days; $i++) {
                $this->bikram_sambat->setNepaliDate($year, $month_number, $i);
                $att_date = $this->bikram_sambat->toFormattedEnglishString();
                $date_bs = $year . '-' . $month_number . '-' . $i;
                $attendence_array[] = array(
                    'day_bs' => sprintf("%02d", $i),
                    'date_bs' => $date_bs,
                    'date_ad' => $att_date,
                    'day' => strtolower(date('l', $this->customlib->dateyyyymmddTodateformat($att_date))),
                    'is_holiday' => $this->_is_holiday($att_date)
                );
                $res = array();

                foreach ($students as $student) {
                    $f = array_filter($attendances, function ($a) use ($i, $student) {
                        return $a['day_bs'] == $i && $a['student_session_id'] == $student['student_session_id'];
                    });
                    if (!empty($f)) {
                        $t = current($f);
                    } else {
                        $t = array(
                            'attendence_type_id' => '',
                            'att_type' => '',
                            'key' => '',
                            'remark' => '',
                        );
                    }
                    $res[] = array_merge($student, $t);
                }
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$date_bs] = $s;
            }

            foreach ($students as $result_k => $result_v) {
                $r = array();
                foreach ($this->config_attendance as $att_key => $att_value) {
                    $s = array_filter($attendances, function ($tt) use ($att_value, $result_v) {
                        if ($tt['attendence_type_id'] == 5) {
                            $o = DateTime::createFromFormat('Y-m-d', $tt['date']);
                            if ($o && (in_array(strtolower($o->format('l')), $this->weekend) || isset($this->holidays[$o->format('Y-m-d')]))) {
                                return false;
                            }
                        }
                        return $tt['student_session_id'] == $result_v['student_session_id'] && $tt['attendence_type_id'] == $att_value;
                    });
                    $r[$att_key] = count($s);
                }
                $monthAttendance[] = array(
                    $result_v['student_session_id'] => $r
                );
            }
            //echo '<pre>';print_r($date_result);print_r($monthAttendance);print_r($attendence_array);exit;
            $data['monthAttendance'] = $monthAttendance;

            $data['resultlist'] = $date_result;
            $data['attendence_array'] = $attendence_array;
            $data['student_array'] = $students;
            $data['holidays'] = $this->holidays;
            $data['total_open_days'] = array_reduce($attendence_array, function ($t, $d) {
                if (!$d['is_holiday']) {
                    $t++;
                }
                return $t;
            }, 0);
            $data['total_holidays'] = $num_of_days - $data['total_open_days'];

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport_bs', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    private function classattendencereport_ad() {

        if (!$this->rbac->hasPrivilege('student_attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/classattendencereport');
        $attendencetypes = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //      if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->stuattendence_model->attendanceYearCount();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data['month_selected'] = "";
        $data['year_selected'] = date('Y');
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', 'lang:month', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $month = $this->input->post('month');
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['month_selected'] = $month;
            $students = $this->student_model->searchByClassSection($class, $section);
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();
            $centenary = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
            $month_number = date("m", strtotime($month));
            $year = $this->input->post('year');
            $data['year_selected'] = $year;
            if (!empty($year)) {

                $year = $this->input->post("year");
            } else {

                if ($month_number >= $startMonth && $month_number <= 12) {
                    $year = $centenary . $year_first_substring;
                } else {
                    $year = $centenary . $year_second_substring;
                }
            }


            $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
            $attendence_array = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            $this->holidays = $this->calendar_model->getHolidaysBetween($year . "-" . $month_number . "-01", $year . "-" . $month_number . "-" . $num_of_days);
            $attendances = $this->stuattendence_model->getAttendanceReportAD($class, $section, $year, $month_number);
            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date = $year . "-" . $month_number . "-" . sprintf("%02d", $i);
                $tmp = DateTime::createFromFormat('Y-m-d', $att_date);
                //$attendence_array[] = $att_date;
                $attendence_array[] = array(
                    'date' => $att_date,
                    'day' => strtolower(date('l', $this->customlib->dateyyyymmddTodateformat($att_date))),
                    'is_holiday' => $this->_is_holiday($tmp->format('Y-n-j'))
                );
                foreach ($students as $student) {
                    $f = array_filter($attendances, function ($a) use ($att_date, $student) {
                        return $a['date'] == $att_date && $a['student_session_id'] == $student['student_session_id'];
                    });
                    if (!empty($f)) {
                        $t = current($f);
                    } else {
                        $t = array(
                            'attendence_type_id' => '',
                            'att_type' => '',
                            'key' => '',
                            'remark' => '',
                        );
                    }
                    $res[] = array_merge($student, $t);
                }
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$att_date] = $s;
            }
            foreach ($students as $result_k => $result_v) {
                $r = array();
                foreach ($this->config_attendance as $att_key => $att_value) {
                    $s = array_filter($attendances, function ($tt) use ($att_value, $result_v) {
                        if ($tt['attendence_type_id'] == 5) {
                            $o = DateTime::createFromFormat('Y-m-d', $tt['date']);
                            if ($o && (in_array(strtolower($o->format('l')), $this->weekend) || isset($this->holidays[$o->format('Y-m-d')]))) {
                                return false;
                            }
                        }
                        return $tt['student_session_id'] == $result_v['student_session_id'] && $tt['attendence_type_id'] == $att_value;
                    });
                    $r[$att_key] = count($s);
                }
                $monthAttendance[] = array(
                    $result_v['student_session_id'] => $r
                );
            }
            //echo '<pre>';print_r($monthAttendance);print_r($attendances);exit;
            $data['monthAttendance'] = $monthAttendance;

            $data['resultlist'] = $date_result;
            $data['attendence_array'] = $attendence_array;
            $data['student_array'] = $students;
            $data['holidays'] = $this->holidays;
            $data['total_open_days'] = array_reduce($attendence_array, function ($t, $d) {
                if (!$d['is_holiday']) {
                    $t++;
                }
                return $t;
            }, 0);
            $data['total_holidays'] = $num_of_days - $data['total_open_days'];

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function monthAttendance($st_month, $no_of_months, $student_id) {

        $record = array();

        $r = array();
        $month = date('m', strtotime($st_month));
        $year = date('Y', strtotime($st_month));

        foreach ($this->config_attendance as $att_key => $att_value) {

            $s = $this->stuattendence_model->count_attendance_obj($month, $year, $student_id, $att_value);


            $attendance_key = $att_key;


            $r[$attendance_key] = $s;
        }

        $record[$student_id] = $r;

        return $record;
    }

}

?>