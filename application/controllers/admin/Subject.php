<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subject extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        //   $this->lang->load('message', 'english');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('subject', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'subject/index');
        $data['title'] = 'Add Subject';
        $subject_result = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/subject/subjectList', $data);
        $this->load->view('layout/footer', $data);
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('subject', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Subject List';
        $subject = $this->subject_model->get($id);
        $data['subject'] = $subject;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/subject/subjectShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('subject', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Subject List';
        $this->subject_model->remove($id);
        redirect('admin/subject/index');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('subject', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add subject';
        $subject_result = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $this->form_validation->set_rules('name', 'lang:subject_name', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('name', 'Subject Name', 'trim|required|xss_clean|callback__check_name_exists');
        $this->form_validation->set_rules('type[]', 'lang:type', 'required|callback__check_subject_type_exists[add]');
        $this->form_validation->set_rules('code', 'lang:code', 'trim|required|xss_clean');
        /*if ($this->input->post('code')) {
            $this->form_validation->set_rules('code', 'Code', 'trim|required|callback__check_code_exists');
        }*/
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/subject/subjectList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $types = $this->input->post('type[]');
            $data = array();
            foreach($types as $type) {
                $data[] = array(
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'type' => $type,
                );
            }
            if(count($data) > 0) {
                $this->db->insert_batch('subjects', $data);
            }
            /*$data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
            );
            $this->subject_model->add($data);*/
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/subject/index');
        }
    }

    function _check_subject_type_exists($type, $action) {
        $subject = $this->security->xss_clean($this->input->post('name'));
        if($action == 'add') {
            $types = $this->input->post('type[]');
            $data1 = array();
            $data1['name'] = $subject;
            $data1['type'] = $types[0];
            if ($this->subject_model->check_data_exists($data1)) {
                $this->form_validation->set_message('_check_subject_type_exists', $this->lang->line("Record already exists"));
                return false;
            }
            if(isset($types[1])) {
                $data2 = array();
                $data2['name'] = $this->security->xss_clean($this->input->post('name'));
                $data2['type'] = $types[1];
                if ($this->subject_model->check_data_exists($data2)) {
                    $this->form_validation->set_message('_check_subject_type_exists', $this->lang->line("Record already exists"));
                    return false;
                }
            }
            return true;
        } else {
            $data = array();
            $data['name'] = $subject;
            $data['type'] = $this->input->post('type');
            if ($this->subject_model->check_data_exists($data)) {
                $this->form_validation->set_message('_check_subject_type_exists', $this->lang->line("Record already exists"));
                return false;
            } else {
                return true;
            }
        }
    }

    function _check_name_exists() {
        $data['name'] = $this->security->xss_clean($this->input->post('name'));
        if ($this->subject_model->check_data_exists($data)) {
            $this->form_validation->set_message('_check_name_exists', $this->lang->line("Record already exists"));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function _check_code_exists() {
        $data['code'] = $this->security->xss_clean($this->input->post('code'));
        if ($this->subject_model->check_code_exists($data)) {
            $this->form_validation->set_message('_check_code_exists', $this->lang->line("Record already exists"));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('subject', 'can_edit')) {
            access_denied();
        }
        $subject_result = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $data['title'] = 'Edit Subject';
        $data['id'] = $id;
        $subject = $this->subject_model->get($id);
        $data['subject'] = $subject;
        $this->form_validation->set_rules('name', 'lang:subject_name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('code', 'lang:code', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/subject/subjectEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
            );
            $this->subject_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/subject/index');
        }
    }

    function getSubjctByClassandSection() {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

}

?>