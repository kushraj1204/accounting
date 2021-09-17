<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ExamSchedule extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->datechooser = $this->setting_model->getDatechooser();
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examschedule/index');
        $data['title'] = 'Exam Schedule';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $examSchedule = $this->examschedule_model->getExamByClassandSection($data['class_id'], $data['section_id']);
            $data['examSchedule'] = $examSchedule;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function view($id)
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Exam Schedule List';
        $exam = $this->exam_model->get($id);
        $data['exam'] = $exam;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam_schedule/examShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id)
    {
        //  if(!$this->rbac->hasPrivilege('exam_schedule','can_delete')){
        // access_denied();
        // }
        $data['title'] = 'Exam Schedule List';
        $this->exam_model->remove($id);
        redirect('admin/exam_schedule/index');
    }

    function create()
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_add')) {
            access_denied();
        }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //     if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'lang:exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'lang:section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/exam_schedule/examCreate_bs', $data);
            } else {
                $this->load->view('admin/exam_schedule/examCreate', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            $exam_publish = $this->exam_model->getDetailExamPublish($class_id, $section_id, $exam_id);
            $data['examSchedule'] = $examSchedule;
            $data['exam_publish'] = $exam_publish;
            if ($this->input->post('save_exam') == "save_exam") {
                $exam_schedule = array(
                    'session_id' => $session,
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'exam_id' => $this->input->post('exam_id'),
                    'is_active' => $this->input->post('is_active')
                );
                $data_exam = $this->exam_model->check_exam_publish($exam_schedule);
                if (!empty($data_exam)) {
                    $id = $data_exam['id'];
                    $this->exam_model->update_exam_publish($id, $exam_schedule);
                } else {
                    $id = $this->exam_model->add_exam_publish($exam_schedule);
                }
                $i = $this->input->post('i');
                foreach ($i as $key => $value) {
                    $data = array(
                        'session_id' => $session,
                        'teacher_subject_id' => $value,
                        'exam_id' => $this->input->post('exam_id'),
                        'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $value))),
                        'date_of_exam_bs' => $this->input->post('date_bs_'.$value),
                        'start_to' => $this->input->post('stime_' . $value),
                        'end_from' => $this->input->post('etime_' . $value),
                        'room_no' => $this->input->post('room_' . $value),
                        'full_marks' => $this->input->post('fmark_' . $value),
                        'passing_marks' => $this->input->post('pmarks_' . $value),
                        'exam_publish_id' => $id,
                    );

                    $this->exam_model->add_exam_schedule($data);
                }
                redirect('admin/examschedule');
            }
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/exam_schedule/examCreate_bs', $data);
            } else {
                $this->load->view('admin/exam_schedule/examCreate', $data);
            }
            $this->load->view('layout/footer', $data);
        }
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Exam Schedule';
        $data['id'] = $id;
        $exam = $this->exam_model->get($id);
        $data['exam'] = $exam;
        $this->form_validation->set_rules('name', 'lang:exam_schedule', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div exam="alert alert-success text-center">Employee details added to Database!!!</div>');
            redirect('admin/exam_schedule/index');
        }
    }

    function getexamscheduledetail()
    {
        $exam_id = $this->input->post('exam_id');
        $section_id = $this->input->post('section_id');
        $class_id = $this->input->post('class_id');
        $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
        echo json_encode($examSchedule);
    }


}