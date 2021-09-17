<?php
/**
 * Created by PhpStorm.
 * User: Brainnovation
 * Date: 4/1/2019
 * Time: 4:11 PM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Createpdf extends Admin_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->blood_group = $this->config->item('bloodgroup');
        $this->role;
    }


    function getAvgMarks($student_id,$class_id,$section_id,$subject_id){

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
        foreach($a as $key => $val){
            $c = ($val['get_marks']/$val['full_marks'])*100;
            $b = ($c/100)*($val['weightage']/100);
            $avg_marks = $avg_marks+$b;
        }
        return $avg_marks*100;

    }
    function pdf($id)
    {

        $this->load->helper('pdf_helper');
//        $id = '1';
        $sql = "select s.firstname,s.lastname,s.gender,s.roll_no,c.class,sec.section,ss.class_id,ss.section_id from students s 
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
        $examList = $this->examschedule_model->getExamByClassandSectionOne($id,$class_id, $section_id);
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


        foreach($exam_subjects as $value){
            $final_marks[] = $this->getAvgMarks($id,$class_id,$section_id,$value['subject_id']);
        }
        $data['final_marks'] = $final_marks;

        $data['exam_subjects'] = $exam_subjects;
        $data['gradeList'] = $gradeList;
        $data['exam_list'] = $examList;
        $data['report'] = $report;
        $data['student_detail'] = $std_detail;

        $this->load->view('admin/pdf/pdf_report', $data);
    }
}
?>