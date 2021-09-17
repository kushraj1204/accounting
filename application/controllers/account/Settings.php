<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends Account_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('setting_model');
        $this->load->model('account/account_model');
        $this->load->model('account/account_category_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/openingBalance_model');
        $this->load->library('bikram_sambat');
        $this->date_system = $this->accountlib->getAccountSetting()->date_system;
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->response = array('status' => 'failure', 'data' => '');
        $this->accountSetting = $this->accountlib->getAccountSetting();
        $this->level = $this->accountSetting->level;
        $this->financial_year = $this->session->userdata('account')['financial_year'];

    }

    function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function financialyear()
    {
        if (!$this->rbac->hasPrivilege('account_general_setting', 'can_view')) {
            access_denied();
        }
//        die($this->financial_year);
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/financialyear');
        $this->data['financialyearlist'] = $this->account_model->getFinancialYearList();

        $settings = $this->account_model->getGeneralSettings();
        $this->data['date_system'] = $settings->date_system;
        $this->load->view('layout/header');
        $this->load->view('account/settings/financialyear', $this->data);
        $this->load->view('layout/footer');
    }

    public function closeFinancialYear($id)
    {
        if (!$this->rbac->hasPrivilege('account_general_setting', 'can_view')) {
            access_denied();
        }
        $curdate = $this->customlib->getCurrentTime('Y-m-d');
        $currentfinancialyear = $this->account_model->getFinancialyear($id);
        $enddate = $currentfinancialyear->year_ends;
        if (strtotime($curdate) >= strtotime($enddate)) {
            $this->account_model->closeFinancialyear((array)$currentfinancialyear);
            $currentFinancialYearID = $this->account_model->getCurrentFinancialYearID();
            $this->account_COA_model->closeCOABalances($currentFinancialYearID);

            $this->personnel_model->closePersonnelBalances($currentFinancialYearID);

            $account['financial_year'] = $currentFinancialYearID;
            $this->session->set_userdata('account', $account);
        } else {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cant_close_financial_year'), 'type' => 'error'));
        }
        redirect("account/settings/financialyear");
    }

    public function general()
    {
        if (!$this->rbac->hasPrivilege('account_general_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/general');
        $this->data['settings'] = $this->account_model->getGeneralSettings();
        $this->load->view('layout/header');
        $this->load->view('account/settings/general', $this->data);
        $this->load->view('layout/footer');
    }

    function getSchoolType()
    {
        $schoolType = $this->setting_model->getSchoolType();
        echo json_encode($schoolType->system_type);
        exit;
    }

    public function saveGeneralSettings()
    {
        $query = $this->db->get('acc_general_settings');
        $settings = (array)$query->result()[0];
        if (isset($settings)) {
            if (!$this->rbac->hasPrivilege('account_general_setting', 'can_edit')) {
                access_denied();
            }
        }
        /*if (!$this->rbac->hasPrivilege('account_general_setting', 'can_add')) {
            access_denied();
        }*/

        $this->form_validation->set_rules('level', $this->lang->line('level'), 'numeric|required|greater_than_equal_to[3]|less_than_equal_to[5]');

        $input = $this->input;
        $system_type = $input->post('system_type', 1);
        $round_to = $input->post('round_to', 1);
        $allow_payment_edit = $input->post('allow_payment_edit', 0);
        $allow_receipt_edit = $input->post('allow_receipt_edit', 0);
        $allow_journal_edit = $input->post('allow_journal_edit', 0);
        //$invoice_generation_on = $input->post('invoice_generation_on', 1);
        $invoice_prefix = $input->post('invoice_prefix', '');
        $use_invoice_prefix = $input->post('use_invoice_prefix', '0');
        $invoice_start = $input->post('invoice_start', '1');
        $journal_prefix = $input->post('journal_prefix', '');
        $use_journal_prefix = $input->post('use_journal_prefix', '0');
        $journal_start = $input->post('journal_start', '1');
        $general_payment_prefix = $input->post('general_payment_prefix', '');
        $use_general_payment_prefix = $input->post('use_general_payment_prefix', '0');
        $general_payment_start = $input->post('general_payment_start', '1');
        $general_receipt_prefix = $input->post('general_receipt_prefix', '');
        $use_general_receipt_prefix = $input->post('use_general_receipt_prefix', '0');
        $general_receipt_start = $input->post('general_receipt_start', '1');
        $cash_receipt_prefix = $input->post('cash_receipt_prefix', '');
        $use_cash_receipt_prefix = $input->post('use_cash_receipt_prefix', '0');
        $cash_receipt_start = $input->post('cash_receipt_start', '1');
        $level = $input->post('level', '4');
        $due_date_duration = $input->post('due_date_duration', '7');

        $data = array();
        $data['system_type'] = $system_type;
        $data['round_to'] = $round_to;
        $data['allow_payment_edit'] = $allow_payment_edit;
        $data['allow_receipt_edit'] = $allow_receipt_edit;
        $data['allow_journal_edit'] = $allow_journal_edit;
        //$data['invoice_generation_on'] = $invoice_generation_on;
        $data['invoice_prefix'] = $invoice_prefix;
        $data['use_invoice_prefix'] = isset($use_invoice_prefix) ? $use_invoice_prefix : 0;
        $data['invoice_start'] = $invoice_start;
        $data['journal_prefix'] = $journal_prefix;
        $data['use_journal_prefix'] = isset($use_journal_prefix) ? $use_journal_prefix : 0;
        $data['journal_start'] = $journal_start;
        $data['general_payment_prefix'] = $general_payment_prefix;
        $data['use_general_payment_prefix'] = isset($use_general_payment_prefix) ? $use_general_payment_prefix : 0;
        $data['general_payment_start'] = $general_payment_start;
        $data['general_receipt_prefix'] = $general_receipt_prefix;
        $data['use_general_receipt_prefix'] = isset($use_general_receipt_prefix) ? $use_general_receipt_prefix : 0;
        $data['general_receipt_start'] = $general_receipt_start;
        $data['cash_receipt_prefix'] = $cash_receipt_prefix;
        $data['use_cash_receipt_prefix'] = isset($use_cash_receipt_prefix) ? $use_cash_receipt_prefix : 0;
        $data['cash_receipt_start'] = $cash_receipt_start;
        $data['level'] = $level;
        $data['due_date_duration'] = $due_date_duration;
        $data['id'] = 1;
        $data['is_settings_saved'] = 1;

        if ($this->form_validation->run() == TRUE) {
            $settings = $this->account_model->getGeneralSettings();
            if ($settings->is_settings_saved) {
                unset($data['system_type']);
                unset($data['round_to']);
                unset($data['level']);
            }
            $this->account_model->saveGeneralSettings($data);
            $msg = $this->lang->line('record_updated');
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $msg . '</div>');
        } else {
            echopreexit(validation_errors());
        }
        redirect("account/settings/general");
    }

    public function saveDateSettings()
    {
        $settings = (array)$this->setting_model->getSetting();
        $startmonth = $settings['start_month']; //Session start month
        unset($settings);
        $query = $this->db->get('acc_general_settings');
        $settings = (array)$query->result()[0];
        if ($settings['is_year_saved'] && $settings['opening_balance_date']) {
            redirect("account/settings/general");
        }
        /*if (!$this->rbac->hasPrivilege('account_general_setting', 'can_add')) {
            access_denied();
        }*/
        $input = $this->input;
        $date_system = $input->post('date_system', 1);

        $opening_balance_date = $input->post('opening_balance_date', '');
        $opening_balance_date_bs = $input->post('opening_balance_date_bs', '');

        $year_start = (int)$input->post('year_start', 1);
        $year_end = (int)$input->post('year_end', 0);
        if ($year_start > 12 || $year_start < 1) {
            $year_start = 12;
        }
        $year_end = $year_start - 1;
        if ($year_end < 1) {
            $year_end = 12;
        }

        $data = array();
        $data['id'] = 1;
        $data['date_system'] = $date_system;
        $data['year_start'] = $year_start;
        $data['year_end'] = $year_end;
        $data['opening_balance_date'] = $opening_balance_date;
        $data['opening_balance_date_bs'] = $opening_balance_date_bs;
        if ($opening_balance_date != '') {
            $datetemp = DateTime::createFromFormat($this->customlib->getSchoolDateFormat(), $opening_balance_date);
            $data['opening_balance_date'] = $datetemp->format('Y-m-d');
        }

        $curyear = $this->customlib->getCurrentTime('Y');
        $todaymth = $this->customlib->getCurrentTime('m');

        if ($year_start < $todaymth) {
            $curyear = $curyear;
            $nextyear = $curyear + 1;
            if ($startmonth < $year_start) { //if session month < acccounting_start_month, need to start from the past financial year
                $curyear = $curyear - 1;
                $nextyear = $curyear + 1;
            }
        } else { //if startingmonth > this_month, need to start from the past financial year
            $curyear = $curyear - 1;
            $nextyear = $curyear + 1;
        }

        $findata['year_starts'] = date('Y-m-d', strtotime($curyear . '-' . $year_start . '-1'));
        $nxt = date('Y-m-d', strtotime($nextyear . '-' . $year_start . '-1'));
        $findata['year_ends'] = date('Y-m-d', strtotime('-1 day', strtotime($nxt)));
        $findata['is_current'] = 1;

        $this->bikram_sambat->setEnglishDate($curyear, $year_start, 1);
        $yearstartnepali = $this->bikram_sambat->toNepaliString();
        $findata['year_starts_bs'] = $yearstartnepali;

        $yearendyr = date('Y', strtotime($findata['year_ends']));
        $yearendmon = date('m', strtotime($findata['year_ends']));
        $yearendday = date('d', strtotime($findata['year_ends']));

        $this->bikram_sambat->setEnglishDate($yearendyr, $yearendmon, $yearendday);
        $yearendnepali = $this->bikram_sambat->toNepaliString();
        $findata['year_ends_bs'] = $yearendnepali;

        if ($date_system == '2') {

            $todayyr = $this->customlib->getCurrentTime('Y');
            $todaymth = $this->customlib->getCurrentTime('m');
            $todayday = $this->customlib->getCurrentTime('d');

            $this->bikram_sambat->setEnglishDate($todayyr, $todaymth, $todayday);
            $todaynepali = $this->bikram_sambat->toNepaliString();
            list($todaynepyear, $todaynepmonth, $todaynepday) = explode('-', $todaynepali);
            $findata['year_starts_bs'] = ($todaynepyear - 1) . '-' . $year_start . '-1';
            $this->bikram_sambat->setNepaliDate(($todaynepyear - 1), $year_start, 1);
            $yearstartenglish = $this->bikram_sambat->toEnglishString();
            $findata['year_starts'] = $yearstartenglish;
            $startmonthacc = (int)(date('m', strtotime($findata['year_starts'])));
            $endyear = $todaynepyear;
            $endmonth = $year_start - 1;
            if ($year_start == 1) {
                $endmonth = 12;
                $endyear = $endyear - 1;
            }
            $lastdayofmonth = $this->bikram_sambat->getLastDayOf($endyear, $endmonth);
            $findata['year_ends_bs'] = ($endyear) . '-' . $endmonth . '-' . $lastdayofmonth;
            $this->bikram_sambat->setNepaliDate($endyear, $endmonth, $lastdayofmonth);
            $findata['year_ends'] = $this->bikram_sambat->toEnglishString();
            if (strtotime($findata['year_ends']) < strtotime($this->customlib->getCurrentTime('Y-m-d'))
//                && ($startmonth > $startmonthacc)
            ) {
                //&& condition above for the case if session month < acccounting_start_month, need to start from the past financial year
                $findata['year_starts_bs'] = ($todaynepyear) . '-' . $year_start . '-1';
                $this->bikram_sambat->setNepaliDate(($todaynepyear), $year_start, 1);
                $yearstartenglish = $this->bikram_sambat->toEnglishString();
                $findata['year_starts'] = $yearstartenglish;

                $endyear = $todaynepyear + 1;
                $endmonth = $year_start - 1;
                if ($year_start == 1) {
                    $endmonth = 12;
                    $endyear = $endyear - 1;
                }
                $lastdayofmonth = $this->bikram_sambat->getLastDayOf($endyear, $endmonth);
                $findata['year_ends_bs'] = ($endyear) . '-' . $endmonth . '-' . $lastdayofmonth;

                $this->bikram_sambat->setNepaliDate($endyear, $endmonth, $lastdayofmonth);
                $findata['year_ends'] = $this->bikram_sambat->toEnglishString();

            }

        }
        if ($settings['is_year_saved'] == 1) {
            unset($data['year_start']);
            unset($data['year_end']);
            unset($data['date_system']);
        }
        $data['is_year_saved'] = 1;
//        $data['opening_balance_date'] = $opening_balance_date == '' ? $findata['year_starts'] : $opening_balance_date;
//        $data['opening_balance_date_bs'] = $opening_balance_date_bs == '' ? $findata['year_starts_bs'] : $opening_balance_date_bs;

        $financial_year = $this->account_model->saveGeneralSettings($data);
        if ($settings['is_year_saved'] != 1) {
            $this->account_model->setUpFinancialYear($findata);
        }
        $account['financial_year'] = $financial_year;
        $this->session->set_userdata('account', $account);

        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_updated') . '</div>');
        redirect("account/settings/general");
    }

    public function chart()
    {
        if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/chart');
        $this->load->view('layout/header');
        $this->load->view('account/settings/chart_of_accounts', $this->data);
        $this->load->view('layout/footer');
    }

    public function categories()
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/categories');
        $this->data["title"] = "Categories";
        $this->data['categories'] = $this->account_category_model->getAllCategories();
        $this->load->view('layout/header');
        $this->load->view('account/settings/categories', $this->data);
        $this->load->view('layout/footer');
    }

    public function add_category()
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_add')) {
            access_denied();
        }
        $this->data['categories'] = $this->account_category_model->getAllCategories();
        $this->load->view("layout/header");
        $this->load->view("account/settings/add_category", $this->data);
        $this->load->view("layout/footer");
    }

    function save_category($data)
    {
        if (!$this->rbac->hasPrivilege('account_categories', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('parent_id', $this->lang->line('parent_category'), 'required');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
        if ($this->form_validation->run() == TRUE) {
            $input = $this->input;
            $parent_id = $input->post('parent_id', 0);
            $title = $input->post('title', '');
            $id = $input->post('id', '');

            $parent = $this->account_category_model->getCategoryDetail($parent_id);
            $level = isset($parent) ? (int)$parent->level + 1 : 0;
            $data = array(
                'parent_id' => $parent_id,
                'title' => $title,
                'id' => $id,
                'level' => $level,
            );
            if ($id == 0) {
                $data['published'] = 1;
            }
            $this->account_category_model->saveCategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added') . '</div>');
            redirect("account/settings/categories");
        } else {
            $this->data['categories'] = $this->account_category_model->getAllCategories();
            $this->load->view("layout/header");
            $this->load->view("account/settings/add_category", $this->data);
            $this->load->view("layout/footer");
        }

    }

    public function add_coa($type = NULL)
    {
        if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/chart');

        $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense', '5' => 'equity');
        // the master categories have an expexted id of the above keys
        if (!in_array($type, $typearray)) {
            $this->load->view("layout/header");
            $this->load->view("account/settings/chart_of_accounts", $this->data);
            $this->load->view("layout/footer");
        }

        $this->data['typeid'] = array_search($type, $typearray);
        $tier = 2;
        $categorylist = array();
        $subcategorylist = array();
        $categories = $this->account_category_model->getNthTierAndChildCategories($tier);
        foreach ($categories as $key => $eachcategory) {
            if ($eachcategory->level == $tier && $eachcategory->parent_id != array_search($type, $typearray)) {
                array_diff($categories, $eachcategory);
            } else {
                if ($eachcategory->level == $tier) {
                    array_push($categorylist, $eachcategory);
                } else {
                    array_push($subcategorylist, $eachcategory);
                }
            }
        }
        $this->data['categories'] = $categorylist;
        $this->data['subcategories'] = $subcategorylist;
        $this->data['type'] = ucwords($type);
        $this->load->view("layout/header");
        $this->load->view("account/settings/add_coa", $this->data);
        $this->load->view("layout/footer");
    }

    public function save_coa()
    {
        $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense', '5' => 'equity');
        $input = $this->input;

        $id = $input->post('id', 0);
        if ($id > 0) {
            if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_edit')) {
                access_denied();
            }
        } else {
            if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_add')) {
                access_denied();
            }
        }
        $type = $input->post('type', '1');
        $is_unique = '|is_unique[acc_chart_of_accounts_detail.code]';
        if (empty($typearray[$type])) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('wrong_type_passed'), 'type' => 'danger'));
        }

        if ($id == 0) {
            $created_by = $this->session->userdata['admin']['id'];
            $created_at = $this->customlib->getCurrentTime();
            $modified_by = $created_by;
            $modified_at = $created_at;
        } else {
            $modified_by = $this->session->userdata['admin']['id'];
            $modified_at = $this->customlib->getCurrentTime();

            $query = $this->db->get_where('acc_chart_of_accounts_detail', array('id' => $id));
            $original_value = $query->row();
            $created_by = $original_value->created_by;
            $created_at = $original_value->created_at;

            if ($input->post('code') == $original_value->code) {
                $is_unique = '';
            }
        }

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required|xss_clean');
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'required');
        if ($this->level >= 4) {
            $this->form_validation->set_rules('subcategory1', $this->lang->line('sub_category_1'), 'required');
        }
        if ($this->level >= 5) {
            $this->form_validation->set_rules('subcategory2', $this->lang->line('sub_category_2'), 'required');
        }
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required' . $is_unique);
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'xss_clean');
        /*if ($this->input->post('type') == 5) {  //case of charges and taxes
            $this->form_validation->set_rules('rate', 'Rate', 'required|numeric');
        }*/
        $this->session->set_userdata('coa_selected', $type);
        if ($this->form_validation->run() == TRUE) {
            $name = $input->post('name', '');
            $category = $input->post('category', 0);
            $subcategory1 = $input->post('subcategory1', 0);
            $subcategory2 = $input->post('subcategory2', 0);
            $code = $input->post('code', '');
            $description = $input->post('description', '');
            $rate = $input->post('rate', '0');
            $status = $input->post('status');
            $is_bank = $input->post('is_bank', 0);
            $is_cash = $input->post('is_cash', 0);
            $is_default_bank = $input->post('is_default_bank', 0);
            //$status = isset($status) ? 1 : 0;
            $is_bank = isset($is_bank) ? 1 : 0;
            $is_cash = isset($is_cash) ? 1 : 0;
            if ($is_bank) {
                $is_default_bank = isset($is_default_bank) ? 1 : 0;
            } else {
                $is_default_bank = 0;
            }

            $data = array(
                'id' => $id,
                'name' => $name,
                'type' => $type,
                'category' => $category,
                'subcategory1' => $subcategory1,
                'subcategory2' => $subcategory2,
                'code' => $code,
                'description' => $description,
                'rate' => $rate ? $rate : 0,
                'status' => 1,
                'is_bank' => $is_bank,
                'is_cash' => $is_cash,
                'is_defaultBank' => $is_default_bank,
                'created_at' => $created_at,
                'created_by' => $created_by,
                'modified_at' => $modified_at,
                'modified_by' => $modified_by,

            );
            $insert = $this->account_COA_model->saveCOAItem($data);
            $msg = $this->lang->line('record_added');
            if ($id > 0) {
                $msg = $this->lang->line('record_updated');
            }
            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/settings/chart");
        } else {
            $this->data['typeid'] = $type;
            $type = $typearray[$type];

            $tier = 2;
            $categorylist = array();
            $subcategorylist = array();
            $categories = $this->account_category_model->getNthTierAndChildCategories($tier);
            foreach ($categories as $key => $eachcategory) {
                if ($eachcategory->level == $tier && $eachcategory->parent_id != array_search($type, $typearray)) {
                    array_diff($categories, $eachcategory);
                } else {
                    if ($eachcategory->level == $tier) {
                        array_push($categorylist, $eachcategory);
                    } else {
                        array_push($subcategorylist, $eachcategory);
                    }
                }
            }
            $this->data['categories'] = $categorylist;
            $this->data['subcategories'] = $subcategorylist;

            if ($id > 0) {
                $this->data['coa'] = $this->account_COA_model->get($id);
                $this->data['coa'] = $this->data['coa'][0];

                $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense', '5' => 'equity');
                $type = $this->data['coa']->type;
                $type = $typearray[$type];
            }

            /*if ($type == "charges") {
                $type = "Charge/ Tax";
            }*/
            $this->data['type'] = ucwords($type);

            $this->load->view("layout/header");
            $this->load->view("account/settings/add_coa", $this->data);
            $this->load->view("layout/footer");
        }


    }

    public function edit_coa($id)
    {
        if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/chart');

        $this->data['coa'] = $this->account_COA_model->get($id);


        $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense', '5' => 'equity');
        $type = $this->data['coa']->type;
        $type = $typearray[$type];

        /*if ($type == "charges") {
            $type = "Charge/ Tax";
        }*/

        $tier = 2;
        $categorylist = array();
        $subcategorylist = array();
        $categories = $this->account_category_model->getNthTierAndChildCategories($tier);
        foreach ($categories as $key => $eachcategory) {
            if ($eachcategory->level == $tier && $eachcategory->parent_id != array_search($type, $typearray)) {
                array_diff($categories, $eachcategory);
            } else {
                if ($eachcategory->level == $tier) {
                    array_push($categorylist, $eachcategory);
                } else {
                    array_push($subcategorylist, $eachcategory);
                }
            }
        }
        $this->data['categories'] = $categorylist;
        $this->data['subcategories'] = $subcategorylist;

        $this->data['type'] = ucwords($type);

        $this->load->view('layout/header');
        $this->load->view('account/settings/add_coa', $this->data);
        $this->load->view('layout/footer');
    }

    public function coaList(){

        $postData = $this->input->post();

        $data = $this->account_COA_model->getCOAList($postData);

        echo json_encode($data);
    }

    function ajax_getCOAListing()
    {
        if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/chart');

        $input = $this->input;
        $target = $input->post('target');
        switch ($target) {
            case '#tab_assets':
                $type = 1;
                break;
            case '#tab_liabilities':
                $type = 2;
                break;
            case '#tab_incomes':
                $type = 3;
                break;
            case '#tab_expenses':
                $type = 4;
                break;
            default:
                $type = 5;
                break;
        }
        $page = $input->post('page');
        $result = $this->account_COA_model->getCOAListing($page, $type);
        foreach ($result['result'] as $eachresult) {
            $eachresult->minidescription = substr($eachresult->description, 0, 100);
            $eachresult->deletable = true;
            if ($eachresult->is_deletable == 0) {
                $eachresult->deletable = false;
            }
        }
        echo json_encode($result);
    }

    function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/asset.csv";
        $data = file_get_contents($filepath);
        $name = 'accounts.csv';
        force_download($name, $data);
    }

    function delete_coa($id)
    {
        if (!$this->rbac->hasPrivilege('account_chart_of_accounts', 'can_delete')) {
            echo json_encode($this->response);
        }
        $result = $this->account_COA_model->deleteCOAItem($id);
        $this->response['data'] = 1;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
    }

    function import()
    {
        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/import');
        $fields = array('code', 'name', 'category', 'sub_category_1', 'sub_category_2', 'opening_balance', 'description', 'is_bank', 'is_cash');
        $type = array('1' => 'Assets', '2' => 'Liabilities', '3' => 'Incomes', '4' => 'Expenses');
        $data['fields'] = $fields;
        $data['types'] = $type;
        $this->load->view('layout/header', $data);
        $this->load->view('account/settings/import', $data);
        $this->load->view('layout/footer', $data);
    }


    function import_coa()
    {

        $this->session->set_userdata('top_menu', 'Account_Settings');
        $this->session->set_userdata('sub_menu', 'account/settings/import');
        $input = $this->input;
        $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense');

        $type = $input->post('type');

        if (!array_key_exists($type, $typearray)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('invalid_type'), 'type' => 'danger'));
            redirect("account/settings/import");
        }
        $typeKey = $type;


        if (isset($_FILES['fileupload']) && $_FILES['fileupload']['tmp_name']) {
            if (!$_FILES['fileupload']['error']) {
                $extension = strtoupper(pathinfo($_FILES['fileupload']['name'], PATHINFO_EXTENSION));
                if ($extension == 'CSV') {
                    $file = $_FILES['fileupload']['tmp_name'];
                    $this->load->library('CSVReader');
                    $rows = $this->csvreader->parse_file($file);
                    $rows = $this->testInsertionValidity($rows, $typeKey);
                    $fields = array('code', 'name', 'category', 'sub_category_1', 'sub_category_2', 'opening_balance', 'description', 'is_bank', 'is_cash', 'error', 'error_cause');
                    $data['fields'] = $fields;
                    $data['type'] = $typearray[$typeKey];
                    $data['typeKey'] = $typeKey;
                    $data['data'] = $rows;
                    $this->load->view('layout/header', $data);
                    $this->load->view('account/settings/import_preview', $data);
                    $this->load->view('layout/footer', $data);
                } else {
                    $this->session->set_flashdata('msg', array('message' => $this->lang->line('please_upload_a_csv_file'), 'type' => 'danger'));
                    redirect("account/settings/import");
                }
            } else {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('error_in_importing'), 'type' => 'danger'));
                redirect("account/settings/import");
            }
        } else {
            redirect("account/settings/import");
        }

    }


    public function import_confirm()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $data = $this->input->post('data');
            $typeKey = $this->input->post('type');
            $result = json_decode($data, true);
            $typearray = array('1' => 'assets', '2' => 'liabilities', '3' => 'income', '4' => 'expense');
            if (!array_key_exists($typeKey, $typearray)) {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('invalid_type'), 'type' => 'danger'));
                redirect("account/settings/import");
            }
            $rows = $this->testInsertionValidity($result, $typeKey, 2);
            $rows = $rows;

            $created_by = $this->session->userdata['admin']['id'];
            $created_at = $this->customlib->getCurrentTime();
            $modified_by = $created_by;
            $modified_at = $created_at;
            $insertData = array();
            $count = 0;
            $totalCount = 0;
            $codeArray = array();
            foreach ($rows as $row) {
                $totalCount += 1;
                if ($row['error'] == 0) {
                    $count += 1;
                    array_push($codeArray, "'" . $row['code'] . "'");
                    $data = array(
                        'name' => $row['name'],
                        'type' => $typeKey,
                        'category' => $row['category'],
                        'subcategory1' => $row['sub_category_1'],
                        'subcategory2' => $row['sub_category_2'],
                        'code' => $row['code'],
                        'description' => $row['description'],
                        'rate' => 0,
                        'status' => 1,
                        'is_bank' => strtolower($row['is_bank']) == 'yes' ? 1 : 0,
                        'is_cash' => strtolower($row['is_bank']) == 'yes' ? 0 : (strtolower($row['is_cash']) == 'yes' ? 1 : 0),
                        'is_defaultBank' => 0,
                        'created_at' => $created_at,
                        'created_by' => $created_by,
                        'modified_at' => $modified_at,
                        'modified_by' => $modified_by,
                    );
                    $insertData[] = $data;
                }
            }


            if ($count > 0) {
                $status = $this->account_COA_model->batchInsert($insertData, $rows, $codeArray, $typeKey);
                if (!$status) {
                    $count = 0;
                    $this->session->set_flashdata('msg', array('message' => $this->lang->line('error_please_try_again'), 'type' => 'danger'));
                    redirect("account/settings/import");
                }
            }

            $this->session->set_flashdata('msg', array('message' => $count . $this->lang->line('of') . $totalCount . $this->lang->line('data_imported_successfully'), 'type' => 'success'));
            redirect("account/settings/import");
        }
        redirect("account/settings/import");
    }


    public function testInsertionValidity($rows, $typeKey, $phase = 1)
    {

        $allCategories = $this->account_category_model->getNthTierAndChildCategories(2);
        $categoriesName = array();
        $subcategories1Name = array();
        $subcategories2Name = array();
        $parentChildCategoryPair = array();
        $allchildren = array();
        $codeArray = array();
        foreach ($allCategories as $category) {

            if ($category->parent_id == $typeKey || in_array($category->parent_id, $allchildren)) {
                array_push($allchildren, $category->id);
                if ($category->level == 2) {
                    $categoriesName[$category->id] = $category->title;
                }
                if ($category->level == 3) {
                    $subcategories1Name[$category->id] = $category->title;
                }
                if ($category->level == 4) {
                    $subcategories2Name[$category->id] = $category->title;
                }
                $parentChildCategoryPair[$category->id] = $category->parent_id;
            }
        }

        foreach ($rows as $key => $row) {
            $rows[$key]['error'] = 0;
            $rows[$key]['error_cause'] = '';
            $categoryName = trim($rows[$key]['category']);
            $subCategory1 = trim($rows[$key]['sub_category_1']);
            $subCategory2 = trim($rows[$key]['sub_category_2']);
            if ($this->level > 4) {

                if (!array_search($row['sub_category_2'], $subcategories2Name)) {
                    $rows[$key]['error'] = 1;
                    $rows[$key]['error_cause'] = 'Provided sub category 2 doesnt exist';
                }
                $rows[$key]['sub_category_2'] = array_search($row['sub_category_2'], $subcategories2Name);
            } else {
                $rows[$key]['sub_category_2'] = 0;
            }
            if ($this->level > 3) {
                if (!array_search($row['sub_category_1'], $subcategories1Name)) {
                    $rows[$key]['error'] = 1;
                    $rows[$key]['error_cause'] = 'Provided sub category 1 doesnt exist';
                }
                $rows[$key]['sub_category_1'] = array_search($row['sub_category_1'], $subcategories1Name);

            } else {
                $rows[$key]['sub_category_1'] = 0;
            }
            $rows[$key]['category'] = array_search($row['category'], $categoriesName);

            if (!array_search($row['category'], $categoriesName)) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Provided category doesnt exist';
            }


            if ($parentChildCategoryPair[$rows[$key]['category']] != $typeKey) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Provided category is not of the mentioned type';
            }
            if ($this->level > 3) {
                if ($parentChildCategoryPair[$rows[$key]['sub_category_1']] != $rows[$key]['category']) {
                    $rows[$key]['error'] = 1;
                    $rows[$key]['error_cause'] = 'Provided sub category 1 does not belong to provided category';

                }
            }
            if ($this->level > 4) {
                if ($parentChildCategoryPair[$rows[$key]['sub_category_2']] != $rows[$key]['sub_category_1']) {
                    $rows[$key]['error'] = 1;
                    $rows[$key]['error_cause'] = 'Provided sub category 2 does not belong to provided sub category 1';

                }
            }
            if ($typeKey != 1) {
                $rows[$key]['is_bank'] = 'No';
                $rows[$key]['is_cash'] = 'No';
            }
            if ($row['code'] == '') {
                $rows[$key]['code'] = strtoupper(mb_substr(rows[$key]['name'], 0, 3)) . substr(md5(rand()), 0, 4) . time() . substr(md5(rand()), 0, 4);
                $row['code'] = $rows[$key]['code'];
            }
            if ($row['is_bank'] == '') {
                $rows[$key]['is_bank'] = 'No';
                $row['is_bank'] = $rows[$key]['is_bank'];
            }

            if ($row['is_cash'] == '') {
                $rows[$key]['is_cash'] = 'No';
                $row['is_cash'] = $rows[$key]['is_cash'];
            }
            $ifcondition = ($row['code'] == '' || $row['name'] == '' || $row['category'] == ''
                || $row['opening_balance'] == '' || $row['is_bank'] == '' || $row['is_cash'] == '');

            if ($this->level == 4) {
                $ifcondition = ($row['code'] == '' || $row['name'] == '' || $row['category'] == ''
                    || $row['opening_balance'] == '' || $row['is_bank'] == '' || $row['is_cash'] == ''
                    || $row['sub_category_1'] == '');
            }
            if ($this->level == 5) {
                $ifcondition = ($row['code'] == '' || $row['name'] == '' || $row['category'] == ''
                    || $row['opening_balance'] == '' || $row['is_bank'] == '' || $row['is_cash'] == ''
                    || $row['sub_category_1'] == '' || $row['sub_category_2'] == '');
            }
            if ($ifcondition) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'All required fields are not provided';
            }

            array_push($codeArray, "'" . $row['code'] . "'");
            if ($phase == 1) {
                $rows[$key]['category'] = $categoryName;
                $rows[$key]['sub_category_1'] = $subCategory1;
                $rows[$key]['sub_category_2'] = $subCategory2;

            }
        }

        $duplicates = $this->account_COA_model->checkDuplicate($codeArray);
        $codeCountArray = array_count_values($codeArray);
        foreach ($rows as $key => $row) {
            if ($codeCountArray['"' . $row['code'] . '"'] > 1) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Duplicate code in spreadsheet';
            }
            if (array_key_exists('"' . $row['code'] . '"', $duplicates['data'])) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Code has already been used';
            }
        }

        return $rows;
    }

}

?>