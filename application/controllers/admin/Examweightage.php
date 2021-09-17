<?php
/**
 * Created by PhpStorm.
 * User: Brainnovation
 * Date: 2/21/2019
 * Time: 10:08 AM
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ExamWeightage extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->helper('url');
        $this->load->model('class_model');
        $this->load->model('exam_model');
        $this->load->model('examweightage_model');
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examination_weightage/index');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('examination_weightage', 'can_view')) {
            access_denied();
        }
        $this->data = array(
            'can_add' => $this->rbac->hasPrivilege('examination_weightage', 'can_add')
        );
        $this->data['classlist'] = $this->class_model->get();
        $this->data['examlist'] = $this->exam_model->get();
        $this->load->view('layout/header', $this->data);
        $this->load->view('admin/exam_weightage/examweightForm', $this->data);
        $this->load->view('layout/footer', $this->data);
    }

    public function save()
    {
        $this->_ajaxCheck();
        if (!$this->rbac->hasPrivilege('examination_weightage', 'can_add')) {
            die("access denied");
        }
        $data = $this->input->post(null, true);
        $required = array('class_id', 'exam_id', 'weightage', 'weight_exam');
        foreach ($required as $req) {
            if (!isset($data[$req])) {
                $this->_jsonResponse(array('success' => false, 'message' => $req . ' is required'));
            }
        }
        $result = $this->examweightage_model->save($data);
        $this->_jsonResponse(array('success' => $result === true, 'message' => 'Error'));
    }

    public function delete()
    {
        $this->_ajaxCheck();
        if (!$this->rbac->hasPrivilege('examination_weightage', 'can_delete')) {
            access_denied();
        }
        $data = $this->input->post(null, true);
        $required = array('class_id', 'exam_id');
        foreach ($required as $req) {
            if (!isset($data[$req])) {
                $this->_jsonResponse(array('success' => false, 'message' => $req . ' is required'));
            }
        }
        $success = $this->examweightage_model->delete($data['class_id'], $data['exam_id']);
        $message = 'Delete Success';
        if (!$success) {
            $message = $this->db->error();
            $message = isset($message['message']) ? $message['message'] : "Delete Error";
        }
        $this->_jsonResponse(array('success' => $success, 'message' => $message));
    }

    public function getValuesByClass()
    {
        $this->_ajaxCheck();
        if (!$this->rbac->hasPrivilege('examination_weightage', 'can_view')) {
            access_denied();
        }
        $data = $this->input->get(null, true);
        $result = $this->examweightage_model->getValuesByClass($data['class_id'], $data['exam_id']);
        $this->_jsonResponse($result);
    }
}