<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Income_statement extends Account_Controller
{

    function __construct()
    {

        parent::__construct();

        $this->load->model('setting_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('account/transaction_model');
        $this->load->model('account/openingBalance_model');
        $this->load->model('account/account_category_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/account_model');
        $this->date_system = $this->accountlib->getAccountSetting()->date_system;
        $this->datechooser = $this->setting_model->getDatechooser();
        $opening_balance_date=$this->datechooser=='bs'?$this->accountlib->getAccountSetting()->opening_balance_date_bs:$this->accountlib->getAccountSetting()->opening_balance_date;
        if(!$opening_balance_date){
            $financialYearStart=$this->accountlib->financialYearStart();
            $opening_balance_date=($this->datechooser=='bs'?$financialYearStart->year_starts_bs:$financialYearStart->year_starts);
        }
        $this->opening_balance_date = $opening_balance_date;
        $this->level = $this->accountlib->getAccountSetting()->level;
        $this->load->library('bikram_sambat');
        $this->financial_year = $this->session->userdata('account')['financial_year'];
    }

    public function index($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/income_statement');

        $type=$this->setting_model->getSchoolType();

        if($type->system_type==2)
            redirect("account/receiptAndPayment");

        //Opening Balance
        if($financialYear == 0){
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;

        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach($years as $year){
            $starts=$year->year_starts_bs;
            if($year->id==1){
                $starts=$this->opening_balance_date;
            }
            if($this->datechooser == 'bs'){
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            }else{
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;

        $openingBalances = $this->openingBalance_model->getOpeningBalances($financialYear);
        $balances = array();
        foreach ($openingBalances as $balance) {
            if ($balance->coa_id != 0) {
                $balances[$balance->coa_id] = $balance;
            }
        }
        $this->data['opening_balance'] = $balances;
        //Opening Balance

        //Parent Categories
        $parentCategories = array(1 => 'Assets', 2 => 'Liabilities', 3 => 'Income', 4 => 'Expenses', 5 => 'Equity');
        $this->data['parentCategories'] = $parentCategories;
        //Parent Categories

        //Categories
        $systemCategories = $this->account_category_model->getAllCategories();
        $categories = array();
        $categories[0] = (object)array('id' => 0, 'title' => 'Cash and Banks');
        foreach ($systemCategories as $category) {
            $categories[$category->id] = $category;
        }
        $this->data['categories'] = $categories;
        $assets = $this->account_COA_model->coaListIncomeStatement($financialYear);


        $coaArray = array();

        foreach ($assets as $asset) {
            if(!$asset->coaDebitSum){$asset->coaDebitSum=0;}
            if(!$asset->coaDebitSumPrev){$asset->coaDebitSumPrev=0;}
            if(!$asset->coaCreditSum){$asset->coaCreditSum=0;}
            if(!$asset->coaCreditSumPrev){$asset->coaCreditSumPrev=0;}
            if(!$coaArray[$asset->type]['openingbalance'])
                $coaArray[$asset->type]['openingbalance'] = 0;
            if(!$coaArray[$asset->type][$asset->category]['openingbalance'])
                $coaArray[$asset->type][$asset->category]['openingbalance'] = 0;

            if(!$coaArray[$asset->type]['balance'])
                $coaArray[$asset->type]['balance'] = 0;
            if(!$coaArray[$asset->type][$asset->category]['balance'])
                $coaArray[$asset->type][$asset->category]['balance'] = 0;
            $asset->amount=0;
            $asset->opening_balance=0;
            if (in_array($asset->type, array(3, 4))) {
                $assetBalance=$asset->coaDebitSum>0?$asset->coaDebitSum:$asset->coaCreditSum;
                $balanceMultiplier = 1;
                if (
                    (in_array($asset->type, array(1, 4)) && $asset->coaCreditSum>0)
                    ||
                    (in_array($asset->type, array(2, 3, 5)) && $asset->coaDebitSum>0)
                ) {
                    $balanceMultiplier = -1;
                }
                $asset->amount += ($balanceMultiplier * $assetBalance);
                $asset->balance=$asset->amount;
                $assetPrevBalance=$asset->coaDebitSumPrev>0?$asset->coaDebitSumPrev:$asset->coaCreditSumPrev;
                $balanceMultiplier = 1;
                if (
                    (in_array($asset->type, array(1, 4)) && $asset->coaCreditSumPrev>0)
                    ||
                    (in_array($asset->type, array(2, 3, 5)) && $asset->coaDebitSumPrev>0)
                ) {
                    $balanceMultiplier = -1;
                }
                $asset->opening_balance += ($balanceMultiplier * $assetPrevBalance);


                $coaArray[$asset->type]['balance'] += $asset->amount;
                $coaArray[$asset->type][$asset->category]['balance'] += $asset->amount;

                if ($financialYear == 1) {
                    $asset->openingbalance = 0;
                }
                $coaArray[$asset->type]['openingbalance'] += $asset->opening_balance;
                $coaArray[$asset->type][$asset->category]['openingbalance'] += $asset->opening_balance;
                $coaArray[$asset->type][$asset->category][] = $asset;
            }
        }

        $this->data['coa'] = $coaArray;
        $this->data['profitAmount']=($coaArray[3]['balance'] - $coaArray[4]['balance']);
        $this->data['profitAmountPrev']=($coaArray[3]['openingbalance'] - $coaArray[4]['openingbalance']);


        $this->load->view('layout/header');
        $this->load->view('account/income_statement/index', $this->data);
        $this->load->view('layout/footer');
    }
}

?>