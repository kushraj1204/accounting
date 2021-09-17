<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/account_category_model');
    }

    function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index()
    {
        //category_rebuild('acc_coa_categories');
        if (!$this->rbac->hasPrivilege('account_categories', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/categories');
        $this->data["title"] = "Categories";
        $this->data['categories'] = $this->account_category_model->getAllCategoriesBySystemLevel($minus = 1);
        $this->load->view('layout/header');
        $this->load->view('account/categories/categories', $this->data);
        $this->load->view('layout/footer');
    }

    public function add_category()
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_add')) {
            access_denied();
        }
        $this->data['categories'] = $this->account_category_model->getAllCategoriesBySystemLevel($minus = 2);

        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/categories');

        $this->load->view("layout/header");
        $this->load->view("account/categories/add_category", $this->data);
        $this->load->view("layout/footer");
    }

    public function edit($id)
    {
        //$id = $this->input->get('id', 0);
        $this->load->library('form_validation');
        if (!$this->rbac->hasPrivilege('account_categories', 'can_edit')) {
            access_denied();
        }
        if ($id == 0) {
            redirect("account/categories");
        }
        $category = $this->account_category_model->getCategoryDetail($id);
        $data['form'] = $category;
        $data['categories'] = $this->account_category_model->getAllCategoriesForEditBySystemLevel($category->lft, $category->rgt);

        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/categories');

        $this->form_validation->set_rules('parent_id', $this->lang->line('parent_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            //form values
            $this->load->view("layout/header", $data);
            $this->load->view("account/categories/add_category", $data);
            $this->load->view("layout/footer", $data);
        }
    }

    function save_category()
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_add')) {
            access_denied();
        }
        $input = $this->input;
        $id = $input->post('id', 0);
        $this->form_validation->set_rules('parent_id', $this->lang->line('parent_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $parent_id = $input->post('parent_id', 0);
            $title = $input->post('title', '');

            $formValues = array(
                'parent_id' => $parent_id,
                'title' => $title,
                'deletable' => 1,
                'id' => $id,
            );
            if($id == 0){
                $formValues['published'] = 1;
                $formValues['created_user_id'] = $this->session->userdata['admin']['id'];
                $formValues['created_on'] = $this->customlib->getCurrentTime();
            }else{
                $formValues['modified_on'] = $this->customlib->getCurrentTime();
            }
            $this->account_category_model->saveCategory($formValues);
            $msg = $this->lang->line('record_added');
            if($id > 0){
                $msg = $this->lang->line('record_updated');
            }
            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/categories");
        } else {
            if ($id > 0) {
                $category = $this->account_category_model->getCategoryDetail($id);
                $this->data['form'] = $category;
                $this->data['categories'] = $this->account_category_model->getAllCategoriesForEditBySystemLevel($category->lft, $category->rgt);
            } else {
                $this->data['categories'] = $this->account_category_model->getAllCategoriesBySystemLevel($minus = 2);
            }
            $this->load->view("layout/header");
            $this->load->view("account/categories/add_category", $this->data);
            $this->load->view("layout/footer");
        }
    }

    function getLevel($parent_id)
    {
        $parent = $this->account_category_model->getCategoryDetail($parent_id);
        $level = isset($parent) ? (int)$parent->level + 1 : 0;
        return $level;
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_delete')) {
            access_denied();
        }
        $this->account_category_model->delete($id);
        $this->session->set_flashdata('msg', array('message' => $this->lang->line('record_deleted'), 'type' => 'success'));
        redirect("account/categories");
    }
}

?>