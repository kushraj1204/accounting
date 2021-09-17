<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends Account_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('account/account_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('account/account_category_model');
    }

    function unauthorized() {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index() {

        if (!$this->rbac->hasPrivilege('account', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/accounts');
        $this->data['accounts'] = $this->account_model->get();
        $this->load->view('layout/header');
        $this->load->view('account/ledger', $this->data);
        $this->load->view('layout/footer');
    }

    public function trialbalance(){
        if (!$this->rbac->hasPrivilege('account', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/accounts/trialbalance');
        $this->data['list']  = $this->personnel_model->getPersonnelTrialBalance();

        $this->load->view('layout/header');
        $this->load->view('account/trialbalance/index');
        $this->load->view('layout/footer');
    }
}
?>