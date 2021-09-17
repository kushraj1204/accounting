<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marksheet extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('class_model');
        $this->load->model('marksheet_model');
        $this->load->model('student_rank_model');
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function index()
    {
        $this->search();
    }

    function getAvgMarks($student_id, $class_id, $section_id, $subject_id)
    {

        $sql = "select er.get_marks,es.passing_marks,es.full_marks,e.id as exam_id,e.name as exam_name,s.name as subject_name,s.id,ew.weightage from exam_results er
        join exam_schedules es on er.exam_schedule_id = es.id
        join exams e on e.id = es.exam_id
        join teacher_subjects ts on es.teacher_subject_id = ts.id
        join subjects s on ts.subject_id = s.id
        join class_sections cs on ts.class_section_id = cs.id
        join exam_weightage ew on ew.exam_id = e.id and ew.class_id = cs.class_id and ew.section_id = cs.section_id 
        where er.student_id = '$student_id' and cs.class_id = '$class_id' and cs.section_id = '$section_id' and s.id = '$subject_id'";
        $query = $this->db->query($sql);
        $a = $query->result_array();
        $avg_marks = 0;
        foreach ($a as $key => $val) {
            $c = ($val['get_marks'] / $val['full_marks']) * 100;
            $b = ($c / 100) * ($val['weightage'] / 100);
            $avg_marks = $avg_marks + $b;
        }
        return $avg_marks * 100;

    }

    function generate_marksheet($id)
    {
        if (!$this->rbac->hasPrivilege('marksheet', 'can_view')) {
            access_denied();
        }

        $setting = $this->setting_model->getSetting();
        $grade_setting = $setting->is_conventional;

        $sql = "select s.id,s.firstname,s.lastname,s.gender,s.roll_no,c.class,sec.section,ss.class_id,ss.section_id from students s 
                join student_session ss on s.id = ss.student_id 
                join classes c on ss.class_id = c.id
                join sections sec on sec.id = ss.section_id  
                where s.id = '$id'";
        $query = $this->db->query($sql);
        $std_detail = $query->row_array();
        $class_id = $std_detail['class_id'];
        $section_id = $std_detail['section_id'];


        $sql = "select s.name as subject_name,avg(`full_marks`) as full_marks,avg(`passing_marks`) as passing_marks,avg(`get_marks`) as get_marks, 
sum(g.credit_point*ts.credit_hour) as total_grade,sum(ts.credit_hour) as total_credit_hour  
from exams e 
            join exam_schedules es on e.id = es.exam_id 
            join exam_results er on es.id = er.exam_schedule_id 
            join tbl_exam_result_publish erp on erp.id = er.exam_result_publish_id
            join teacher_subjects ts on ts.id = es.teacher_subject_id
            join subjects as s on ts.subject_id = s.id
            join tbl_grade g on er.grade_id = g.id
            where er.student_id = '$id' and erp.is_active = '1'
            group by s.id";

        $query = $this->db->query($sql);
        $report = $query->result_array();
        $gradeList = $this->grade_model->get();
//                $examList = $this->examschedule_model->getExamByClassandSection($class_id, $section_id);
        $examList = $this->examschedule_model->getExamByClassandSectionOne($id, $class_id, $section_id);
        //echo '<pre>';print_r($examList);exit;
        $data['examSchedule'] = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $id);

                foreach ($exam_subjects as $key => $value) {
                    $exam_array = array();
                    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                    $exam_array['exam_id'] = $value['exam_id'];
                    $exam_array['full_marks'] = $value['full_marks'];
                    $exam_array['passing_marks'] = $value['passing_marks'];
                    $exam_array['exam_name'] = $value['name'];
                    $exam_array['exam_type'] = $value['type'];
                    $exam_array['attendence'] = $value['attendence'];
                    $exam_array['get_marks'] = $value['get_marks'];
                    $x[] = $exam_array;
                }
                $array['exam_name'] = $ex_value['exam_name'];
                $array['exam_result'] = $x;
                $new_array[] = $array;
            }
            $data['examSchedule'] = $new_array;
        }
//        $sql = "select s.name as subject_name,s.id as subject_id from subjects s
//                                join teacher_subjects ts on s.id = ts.subject_id
//                                join class_sections cs on ts.class_section_id = cs.id
//                                where cs.class_id = '$class_id' and cs.section_id = '$section_id'";
//        $query = $this->db->query($sql);
//        $a = $query->result_array();
        foreach ($exam_subjects as $value) {
            $final_marks[] = $this->getAvgMarks($id, $class_id, $section_id, $value['subject_id']);
        }
        //echo '<pre>';print_r($final_marks);exit;
        $data['final_marks'] = $final_marks;

        $data['exam_subjects'] = $exam_subjects;
        $data['gradeList'] = $gradeList;
        $data['exam_list'] = $examList;
        $data['report'] = $report;
        $data['student_detail'] = $std_detail;
        $data['grade_setting'] = $grade_setting;
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'marksheet/search');
        $data['title'] = 'Student Marksheet';

        $this->load->view('layout/header', $data);
        $this->load->view('admin/marksheet/markSheet', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('marksheet', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'marksheet/search');

        $data = array();
        $data['classes'] = $this->class_model->get();
        $data['sections'] = $this->marksheet_model->getAllSectionsGroupByClass();
        $data['exams'] = $this->marksheet_model->getResultPublishedExamsForCurrentSession(true);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/marksheet/search', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getExamAttendedStudents()
    {
        $this->_ajaxCheck();
        if (!$this->rbac->hasPrivilege('marksheet', 'can_view')) {
            access_denied();
        }
        $data = $this->input->get(null, true);
        $exam_schedule = $this->examschedule_model->getDetailbyClsandSection($data['cid'], $data['sid'], $data['eid']);
        $students = $this->marksheet_model->getExamAttendedStudents($data['erid']);
        $rank_rows = $this->student_rank_model->checkRankGenerated();
        $result = array(
            'exam_schedule' => array(
                'start' => '',
                'end' => '',
                'ep_id' => '',
            ),
            'students' => array(),
            'rank_generated' => $rank_rows['rows'] > 0,
        );
        if (count($exam_schedule) > 0) {
            $result = array(
                'exam_schedule' => array(
                    'start' => $exam_schedule[0]['date_of_exam'],
                    'end' => $exam_schedule[count($exam_schedule) - 1]['date_of_exam'],
                    'ep_id' => $exam_schedule[0]['exam_publish_id'],
                ),
                'students' => $students,
            );
        }
        $this->_jsonResponse($result);
    }

    public function generate()
    {
        if (!$this->rbac->hasPrivilege('marksheet', 'can_view')) {
            access_denied();
        }
        $attendance_end_date = $this->input->post('start');
        $exam_id = (int)$this->input->post('exam_id');
        $exam_result_publish_id = (int)$this->input->post('result_publish_id');
        $student_id = (int)$this->input->post('student_id');
        $data = $this->marksheet_model->generateMarksheetDataCombined($student_id, $exam_id, $exam_result_publish_id);
        /*if (isset($data['weightage']) && count($data['weightage']) > 0) {
            $last_exam = end($data['weightage']);
            $att_start_date = $last_exam['exam']['exam_start_date'];
        } else {
            $session_detail = $this->session_model->get($this->current_session);
            $att_start_date = $session_detail['start_date'];
        }*/
        $session_detail = $this->session_model->get($this->current_session);
        $att_start_date = $session_detail['start_date'];
        $attendance = $this->marksheet_model->getAttendance($att_start_date, $attendance_end_date, $student_id);
        $data['attendance'] = $attendance;
        $this->load->helper('marksheet');
        $data['grade_list'] = $this->grade_model->get();
        $data['mgl'] = 5;
        $data['mgr'] = 5;
        /*echo '<pre>';
        print_r($data);
        exit;*/
        $layout = 'A4';
        if (isset($data['weightage']) && count($data['weightage']) > 0) {
            $layout = 'A4-L';
            $view = 'admin/marksheet/cpdf_weightage';
        } else {
            $layout = 'A4-L';
            $view = 'admin/marksheet/cpdf_no_weightage_land';
        }
        /*if ($data['settings']['show_marks'] != 1) {
            if (isset($data['weightage']) && count($data['weightage']) > 0) {
                $view = 'admin/marksheet/pdf_no_marks';
            } else {
                $view = 'admin/marksheet/pdf_no_marks_no_weightage';
            }
        } else {
            if ($data['settings']['show_grade'] == 1) {
                $view = 'admin/marksheet/pdf';
            } else {
                $view = 'admin/marksheet/pdf_no_grade';
            }
        }*/
        //$this->load->view($view, $data);
        $html = $this->load->view($view, $data, true);
        $file_name = $data['student']['firstname'] . "_" . $data['student']['lastname'] . "_" . $data['student']['admission_no'] . "_" . $data['exam']['exam_name'];
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $pdfFilePath = $file_name . ".pdf";
        $this->load->library('m_pdf', array(
            'mode' => 'utf-8',
            'format' => $layout,
            'mgl' => $data['mgl'],
            'mgr' => $data['mgr'],
            'mgt' => 0,
            'mgb' => 0,
            'mgh' => 0,
            'mgf' => 0,
        ));
        $this->m_pdf->pdf->SetWatermarkImage(base_url() . '/uploads/school_content/logo/' . $data['settings']['image'], 0.1);
        $this->m_pdf->pdf->showWatermarkImage = true;
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    public function generateRank()
    {
        if (!$this->rbac->hasPrivilege('marksheet', 'can_view')) {
            access_denied();
        }
        $exam_id = (int)$this->input->post('exam_id');
        $exam_result_publish_id = (int)$this->input->post('result_publish_id');
        $data = $this->student_rank_model->generateRank($exam_id, $exam_result_publish_id);
        $this->_jsonResponse($data);
    }
}