<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feetype extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->event('fee_events');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('fees_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feetype/index');
        $data['title'] = 'Add Feetype';
        $data['title_list'] = 'Recent FeeType';

        $this->form_validation->set_rules(
                'code', 'lang:code', array(
            'required',
            array('check_exists', array($this->feetype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('name', 'lang:name', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $data = array(
                'type' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $inserted_id = $this->feetype_model->add($data);
            Neema_events::trigger('on_feetype_add', array_merge($data, array('id' => $inserted_id)));
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/feetype/index');
        }
        $feegroup_result = $this->feetype_model->get();
        $data['feetypeList'] = $feegroup_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feetype/feetypeList', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('fees_type', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $feetype = $this->feetype_model->get($id);
        if($feetype) {
            $this->feetype_model->remove($id);
            Neema_events::trigger('on_feetype_delete', $feetype);
        }
        redirect('admin/feetype/index');
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('fees_type', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feetype/index');
        $data['id'] = $id;
        $feetype = $this->feetype_model->get($id);
        $data['feetype'] = $feetype;
        $feegroup_result = $this->feetype_model->get();
        $data['feetypeList'] = $feegroup_result;
        $this->form_validation->set_rules(
                'code', 'lang:code', array(
            'required',
            array('check_exists', array($this->feetype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('name', 'lang:name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feetype/feetypeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'type' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->feetype_model->add($data);
            Neema_events::trigger('on_feetype_edit', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/feetype/index');
        }
    }

}

?>