<?php
/**
 * Created by PhpStorm.
 * User: Brainnovation
 * Date: 2/6/2019
 * Time: 4:54 PM
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gradecpga extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('grade_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'cpga_grade/index');
        $data['title'] = 'Add Cpga Grade';
        $data['title_list'] = 'Grade Details';
        $listgrade = $this->gradecpga_model->get();
        $data['listgrade'] = $listgrade;
        $this->load->view('layout/header');

        $this->load->view('admin/cpga_grade/createcpgagrade', $data);
        $this->load->view('layout/footer');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('cpga_grade', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Grade';
        $data['title_list'] = 'Grade Details';
        $this->form_validation->set_rules('grade_letter', 'Grade', 'trim|required|xss_clean');
        $this->form_validation->set_rules('credit_point', 'credit_point', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listgrade = $this->gradecpga_model->get();
            $data['listgrade'] = $listgrade;
            $this->load->view('layout/header');
            $this->load->view('admin/cpga_grade/createcpgagrade', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'grade_letter' => $this->input->post('grade_letter'),
                'credit_point' => $this->input->post('credit_point'),
                'description' => $this->input->post('description')
            );
            $this->gradecpga_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Grade added successfully</div>');
            redirect('admin/gradecpga/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('cpga_grade', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Grade';
        $data['title_list'] = 'Grade Details';
        $data['id'] = $id;
        $editgrade = $this->gradecpga_model->get($id);
        $data['editgrade'] = $editgrade;
        $this->form_validation->set_rules('grade_letter', 'Grade', 'trim|required|xss_clean');
        $this->form_validation->set_rules('credit_point', 'Credit Point', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listgrade = $this->gradecpga_model->get();
            $data['listgrade'] = $listgrade;
            $this->load->view('layout/header');
            $this->load->view('admin/cpga_grade/editcpgagrade', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'grade_letter' => $this->input->post('grade_letter'),
                'credit_point' => $this->input->post('credit_point'),
                'description' => $this->input->post('description')
            );
            $this->gradecpga_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Grade updated successfully</div>');
            redirect('admin/gradecpga/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('cpga_grade', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Grade List';
        $this->gradecpga_model->remove($id);
        redirect('admin/gradecpga/index');
    }

}

?>