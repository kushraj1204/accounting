<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FeeGroup extends Admin_Controller {

    private $datechooser;
    private $system_calendar;

    function __construct() {
        parent::__construct();
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->system_calendar = $this->config->item('system_calendar');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feegroup');
        $data['title'] = 'Add FeeGroup';
        $data['title_list'] = 'Recent FeeGroups';
        if($this->system_calendar == 'bs') {
            $data['months'] = $this->customlib->getBSMonths();
        } else {
            $data['months'] = $this->customlib->getADMonths();
        }

        $this->form_validation->set_rules(
                'name', 'lang:name', array(
            'required',
            array('check_exists', array($this->feegroup_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'fee_month' => $this->input->post('fee_month'),
                'description' => $this->input->post('description')
            );
            $this->feegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/feegroup/index');
        }
        $feegroup_result = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup_result;

        $this->load->view('layout/header', $data);
        if($this->datechooser == 'bs') {
            $this->load->view('admin/feegroup/feegroupList_bs', $data);
        } else {
            $this->load->view('admin/feegroup/feegroupList', $data);
        }

        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->feegroup_model->remove($id);
        redirect('admin/feegroup/index');
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feegroup');
        $data['id'] = $id;
        $feegroup = $this->feegroup_model->get($id);
        $data['feegroup'] = $feegroup;
        $feegroup_result = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup_result;
        if($this->system_calendar == 'bs') {
            $data['months'] = $this->customlib->getBSMonths();
        } else {
            $data['months'] = $this->customlib->getADMonths();
        }
        $this->form_validation->set_rules(
                'name', 'lang:name', array(
            'required',
            array('check_exists', array($this->feegroup_model, 'check_exists'))
                )
        );

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            if($this->datechooser == 'bs') {
                $this->load->view('admin/feegroup/feegroupEdit_bs', $data);
            } else {
                $this->load->view('admin/feegroup/feegroupEdit', $data);
            }
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'fee_month' => $this->input->post('fee_month'),
                'description' => $this->input->post('description'),
            );
            $this->feegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/feegroup/index');
        }
    }

}