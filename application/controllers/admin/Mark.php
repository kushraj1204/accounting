<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mark extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("classteacher_model");
        $this->load->model('grade_model');
    }

    function index()
    {
        // if(!$this->rbac->hasPrivilege('marks_register','can_view')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'mark/index');
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Marks';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get();
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        // $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        //     }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'lang:exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            $studentList = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['examSchedule'] = array();
            if (!empty($examSchedule)) {
                $new_array = array();
                $data['examSchedule']['status'] = "yes";
                foreach ($studentList as $stu_key => $stu_value) {
                    $array = array();
                    $array['student_id'] = $stu_value['id'];
                    $array['roll_no'] = $stu_value['roll_no'];
                    $array['firstname'] = $stu_value['firstname'];
                    $array['lastname'] = $stu_value['lastname'];
                    $array['admission_no'] = $stu_value['admission_no'];
                    $array['dob'] = $stu_value['dob'];
                    $array['father_name'] = $stu_value['father_name'];
                    $x = array();
                    foreach ($examSchedule as $ex_key => $ex_value) {
                        $exam_array = array();
                        $exam_array['exam_schedule_id'] = $ex_value['id'];
                        $exam_array['exam_id'] = $ex_value['exam_id'];
                        $exam_array['full_marks'] = $ex_value['full_marks'];
                        $exam_array['passing_marks'] = $ex_value['passing_marks'];
                        $exam_array['exam_name'] = $ex_value['name'];
                        $exam_array['exam_type'] = $ex_value['type'];
                        $student_exam_result = $this->examresult_model->get_result($ex_value['id'], $stu_value['id']);

                        if (empty($student_exam_result)) {

                        } else {
                            $exam_array['attendence'] = $student_exam_result->attendence;
                            $exam_array['get_marks'] = $student_exam_result->get_marks;
                            $exam_array['is_na'] = $student_exam_result->is_na;
                        }
                        $x[] = $exam_array;
                    }
                    if (empty($x)) {
                        $data['examSchedule']['status'] = "no";
                    }
                    $array['exam_array'] = $x;
                    $new_array[] = $array;
                }

                $data['examSchedule']['result'] = $new_array;
            } else {
                $s = array('status' => 'no');
                $data['examSchedule'] = $s;
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function view($id)
    {
        if (!$this->rbac->hasPrivilege('marks_register', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Mark List';
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/mark/markShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id)
    {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/mark/index');
    }

    function create()
    {
        // if(!$this->rbac->hasPrivilege('marks_register','can_add')){
        // access_denied();
        // }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get();
        //$grade = $this->grade_model->get();
        //$data['grade'] = $grade;
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassbyteacher($userdata["id"]);
        // } 
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'lang:exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $userdata = $this->customlib->getUserData();
            $getTeacherSubjects = array();
            if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                $getTeacherSubjects = $this->examschedule_model->getTeacherSubjects($class_id, $section_id, $userdata["id"]);
            }
            if(!empty($getTeacherSubjects)) {
                $data["teacher_subjects"] = array();
                foreach ($getTeacherSubjects as $ts) {
                    $data['teacher_subjects'][$ts['subject_id']] = $ts;
                }
            }
            $data['is_super_admin'] = $this->rbac->isSuperAdmin();
            $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);

            $studentList = $this->student_model->searchByClassSection($class_id, $section_id);
            $gradeList = $this->grade_model->get();

            if (!empty($examSchedule)) {
                $new_array = array();
                foreach ($studentList as $stu_key => $stu_value) {
                    $array = array();
                    $array['student_id'] = $stu_value['id'];
                    $array['admission_no'] = $stu_value['admission_no'];
                    $array['roll_no'] = $stu_value['roll_no'];
                    $array['firstname'] = $stu_value['firstname'];
                    $array['lastname'] = $stu_value['lastname'];
                    $array['dob'] = $stu_value['dob'];
                    $array['father_name'] = $stu_value['father_name'];
                    $x = array();
                    foreach ($examSchedule as $ex_key => $ex_value) {
                        $exam_array = array();
                        $exam_array['exam_schedule_id'] = $ex_value['id'];
                        $exam_array['exam_id'] = $ex_value['exam_id'];
                        $exam_array['subject_id'] = $ex_value['subject_id'];
                        $exam_array['full_marks'] = $ex_value['full_marks'];
                        $exam_array['passing_marks'] = $ex_value['passing_marks'];
                        $exam_array['exam_name'] = $ex_value['name'];
                        $exam_array['exam_type'] = $ex_value['type'];
                        $student_exam_result = $this->examresult_model->get_exam_result($ex_value['id'], $stu_value['id']);
                        $exam_array['attendence'] = $student_exam_result->attendence;
                        $exam_array['get_marks'] = $student_exam_result->get_marks;
                        $exam_array['is_na'] = $student_exam_result->is_na;
                        //$exam_array['grade_id'] = $student_exam_result->grade_id;
                        $x[] = $exam_array;
                    }
                    $array['exam_array'] = $x;
                    $new_array[] = $array;
                }
                $data['examSchedule'] = $new_array;
                $exam_result_publish = $this->examresult_model->ExamResultPublishDetail($session, $class_id, $exam_id, $section_id);
                $data['exam_result_publish'] = $exam_result_publish;
            }
            if ($this->input->post('save_exam') == "save_exam") {

                $exam_id = $this->input->post('exam_id');
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $exam_publish = array(
                    'session_id' => $session,
                    'exam_id' => $exam_id,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'is_active' => $this->input->post('is_active'),
                );
                $data_exam = $this->examresult_model->checkExamResultPublish($exam_publish);
                if (!empty($data_exam)) {
                    $id = $data_exam['id'];
                    $this->examresult_model->updateExamResultPublish($id, $exam_publish);
                } else {
                    $id = $this->examresult_model->addExamResultPublish($exam_publish);
                }

                $ex_array = array();
                $exam_id = $this->input->post('exam_id');
                $student_array = $this->input->post('student');
                $exam_array = $this->input->post('exam_schedule');

                foreach ($student_array as $key => $student) {
                    foreach ($exam_array as $key => $exam) {
                        $record = array(
                            'get_marks' => 0,
                            'grade_id' => 0,
                            'attendence' => 'pre',
                        );
                        if ($this->input->post('student_absent' . $student . "_" . $exam) == "") {
                            $record['get_marks'] = $this->input->post('student_number' . $student . "_" . $exam);
                            //$record['grade_id'] = $this->input->post('student_number_one' . $student . "_" . $exam);
                        } else {
                            $record['attendence'] = $this->input->post('student_absent' . $student . "_" . $exam);
                        }
                        $record['is_na'] = $this->input->post('is_na_' . $student . "_" . $exam);
                        if (empty($record['is_na'])) {
                            $record['is_na'] = 0;
                        }
                        $f_exam = array_filter($examSchedule, function ($e) use ($exam) {
                            return $e['id'] == $exam;
                        });
                        $exam_detail = current($f_exam);
                        if (count($gradeList) > 0 && !empty($exam_detail)) {
                            $c_mark = ($record['get_marks'] / $exam_detail['full_marks']) * 100;
                            $f_grade = array_filter($gradeList, function ($g) use ($c_mark) {
                                return $g['mark_from'] <= $c_mark && $g['mark_upto'] >= $c_mark;
                            });
                            if (!empty($f_grade)) {
                                $grade = current($f_grade);
                                $record['grade_id'] = $grade['id'];
                            }
                        }
                        $record['exam_schedule_id'] = $exam;
                        $record['student_id'] = $student;
                        $record['exam_result_publish_id'] = $id;
                        $inserted_id = $this->examresult_model->add_exam_result($record);


                        if ($this->input->post('is_active') == 1) {
                            $ex_array[$student] = $exam_id;
                        }
                    }
                }
                if (!empty($ex_array)) {
                    $this->mailsmsconf->mailsms('exam_result', $ex_array, NULL, $exam_array);
                }

                redirect('admin/mark');
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('marks_register', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Mark';
        $data['id'] = $id;
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->form_validation->set_rules('name', 'lang:mark', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/mark/index');
        }
    }

}