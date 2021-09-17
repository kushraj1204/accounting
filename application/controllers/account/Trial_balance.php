<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trial_balance extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
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
        $this->session->set_userdata('sub_menu', 'account/trial_balance');

        //Opening Balance
        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;
        $openingBalances = $this->openingBalance_model->getOpeningBalances($financialYear);

        $personnelTB = $this->personnel_model->getPersonnelTrialBalance($financialYear);


        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach ($years as $year) {
            $starts=$year->year_starts_bs;
            if($year->id==1){
                $starts=$this->opening_balance_date;
            }
            if ($this->datechooser == 'bs') {
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;

        $balances = array();

        foreach ($openingBalances as $balance) {
            //this if was added in accordance to the requirement that opening balance for income and expense is not required
            if ($balance->coatype == '3' || $balance->coatype == '4') {
                $balance->balance = 0;
            }
            if ($balance->coa_id != 0) {
                $balances[$balance->coa_id] = $balance;
            }
        }

        $this->data['opening_balance'] = $balances;
        //Opening Balance

        //Parent Categories
        $parentCategories = array(1 => 'Assets', 2 => 'Liabilities', 3 => 'Incomes', 4 => 'Expenses', 5 => 'Equity');
        $this->data['parentCategories'] = $parentCategories;
        //Parent Categories

        //Categories
        $systemCategories = $this->account_category_model->getAllCategories();
        $categories = array();
        foreach ($systemCategories as $category) {
            $categories[$category->id] = $category;
        }
        $this->data['categories'] = $categories;
        //Categories

        //COA
        $assets = $this->account_COA_model->getCOAListForLedger($financialYear);


        $coaArray = array();

        $profit = 0;

        foreach ($assets as $asset) {
            $asset->amount = is_null($asset->amount) ? 0 : $asset->amount;
            $asset->coaDebitSum = is_null($asset->coaDebitSum) ? 0 : $asset->coaDebitSum;
            $asset->coaCreditSum = is_null($asset->coaCreditSum) ? 0 : $asset->coaCreditSum;

            //this if was added in accordance to the requirement that opening balance for income and expense is not required
            if ($asset->type == '3' || $asset->type == '4') {
                $tempOpeningBalance = $asset->openingbalance;
                $tempOpeningBalancePrev = $asset->openingbalanceprevyear;
                if ($asset->type == '3') {
                    $profit += $asset->openingbalance;
                }
                if ($asset->type == '4') {
                    $profit -= $asset->openingbalance;
                }
                $asset->openingbalance = 0;
            }
            if ($asset->name == '') continue;
            $assetBalance = $balances[$asset->id]->balance;
            $balanceMultiplier = 1;
            if (
                (in_array($asset->type, array(1, 4)) && $balances[$asset->id]->balance_type == 'credit')
                ||
                (in_array($asset->type, array(2, 3, 5)) && $balances[$asset->id]->balance_type == 'debit')
            ) {
                $balanceMultiplier = -1;
            }
            $asset->amount += ($balanceMultiplier * $assetBalance);
            //include opening balance in current year
            $asset->opening_balance = $balances[$asset->id]->balance;
            $asset->opening_balance_type = $balances[$asset->id]->balance_type;
            if (in_array($asset->type, array(1, 4))) {
                if ($asset->amount >= 0) {
                    $asset->debit = $asset->amount;
                } else {
                    $asset->credit = -1 * $asset->amount;
                }
            } elseif (in_array($asset->type, array(2, 3, 5))) {
                if ($asset->amount >= 0) {
                    $asset->credit = $asset->amount;
                } else {
                    $asset->debit = -1 * $asset->amount;
                }
            }
            //addition
//            if($asset->id==500){//500 in test.neemacademy for now 13
            if ($asset->id == 13) {//500 in test.neemacademy for now 13
                $asset->opening_balance = 0;
            }
            //balance calculations
            $coaArray[$asset->type]['openingDebitTotal'] += $asset->opening_balance_type == 'debit' ? $asset->opening_balance : 0;
            $coaArray[$asset->type]['openingCreditTotal'] += $asset->opening_balance_type == 'credit' ? $asset->opening_balance : 0;
            $coaArray[$asset->type][$asset->category]['openingDebitTotal'] += $asset->opening_balance_type == 'debit' ? $asset->opening_balance : 0;
            $coaArray[$asset->type][$asset->category]['openingCreditTotal'] += $asset->opening_balance_type == 'credit' ? $asset->opening_balance : 0;

            $coaArray[$asset->type][$asset->category]['transactionDebitTotal'] += $asset->coaDebitSum;
            $coaArray[$asset->type][$asset->category]['transactionCreditTotal'] += $asset->coaCreditSum;

            $debit = $asset->debit > 0 ? $asset->debit : 0;
            $debit = $debit + ($asset->credit < 0 ? -1 * $asset->credit : 0);
            $credit = $asset->credit > 0 ? $asset->credit : 0;
            $credit = $credit + ($asset->debit < 0 ? -1 * $asset->debit : 0);

            $asset->debit = $debit;
            $asset->credit = $credit;
            $coaArray[$asset->type]['debitTotal'] += $debit;
            $coaArray[$asset->type][$asset->category]['debitTotal'] += $debit;
            $coaArray[$asset->type]['creditTotal'] += $credit;
            $coaArray[$asset->type][$asset->category]['creditTotal'] += $credit;
            //balance calculations

            $asset->link = base_url() . 'account/ledger/detail/' . $asset->id . '/' . $financialYear;

            $coaArray[$asset->type][$asset->category][] = $asset;
            //addition
            if ($asset->type == '3' || $asset->type == '4') {
                $asset->openingbalance = $tempOpeningBalance;
                $asset->opening_balance = $tempOpeningBalance - $tempOpeningBalancePrev;
                $coaArray[$asset->type]['openingDebitTotal'] += $asset->opening_balance_type == 'debit' ? $asset->opening_balance : 0;
                $coaArray[$asset->type]['openingCreditTotal'] += $asset->opening_balance_type == 'credit' ? $asset->opening_balance : 0;
                $coaArray[$asset->type][$asset->category]['openingDebitTotal'] += $asset->opening_balance_type == 'debit' ? $asset->opening_balance : 0;
                $coaArray[$asset->type][$asset->category]['openingCreditTotal'] += $asset->opening_balance_type == 'credit' ? $asset->opening_balance : 0;
            }
//            if($asset->id==500){//500 in test.neemacademy for now 13
            if ($asset->id == 13) {//500 in test.neemacademy for now 13
                $asset->opening_balance = $asset->openingbalanceprevyear;
            }


        }


        $lastAsset = $assets[count($assets) - 1];
        $lastId = $lastAsset->id + 1;
        $customerDetail = $personnelTB[2];

        $receivables = new stdClass();
        if ($customerDetail['debitTotal']) {
            $receivables->debit = $customerDetail['debitTotal'] + $customerDetail['openingDebitTotal'] - $customerDetail['openingCreditTotal'];
            $receivables->credit = 0;
            if ($receivables->debit < 0) {
                $receivables->credit = abs($receivables->debit);
                $receivables->debit = 0;
            }
        }

        if ($customerDetail['creditTotal']) {
            $receivables->credit = $customerDetail['creditTotal'] + $customerDetail['openingCreditTotal'] - $customerDetail['openingDebitTotal'];
            $receivables->debit = 0;
            if ($receivables->credit < 0) {
                $receivables->debit = abs($receivables->credit);
                $receivables->credit = 0;
            }
        }

        if (!$customerDetail['creditTotal'] && !$customerDetail['debitTotal']) {
            $receivables->credit = $customerDetail['creditTotal'] + $customerDetail['openingCreditTotal'] - $customerDetail['openingDebitTotal'];
            $receivables->debit = $customerDetail['debitTotal'] + $customerDetail['openingDebitTotal'] - $customerDetail['openingCreditTotal'];
            if ($receivables->credit < 0) {
                $receivables->debit = abs($receivables->credit);
                $receivables->credit = 0;
            }
            if ($receivables->debit < 0) {
                $receivables->credit = abs($receivables->debit);
                $receivables->debit = 0;
            }
        }

        $receivables->amount = $receivables->debit > 0 ? $receivables->debit : $receivables->credit;
        $receivables->opening_balance = $customerDetail['openingCreditTotal'] > 0 ? $customerDetail['openingCreditTotal'] : $customerDetail['openingDebitTotal'];
        $receivables->opening_balance_type = $customerDetail['openingCreditTotal'] > 0 ? 'credit' : 'debit';
        $receivables->coaDebitSum = abs($customerDetail['transactionDebitTotal']);
        $receivables->coaCreditSum = abs($customerDetail['transactionCreditTotal']);
        $receivables->id = $lastId;
        $receivables->link = base_url() . 'account/personnel/customers';

        $receivables->type = 1;
        $receivables->name = $this->lang->line('receivables');
        $receivables->category = 10;
        $coaArray[1][10][] = $receivables;

        //balance calculations
        $coaArray[2]['openingDebitTotal'] += $receivables->opening_balance_type == 'debit' ? $receivables->opening_balance : 0;
        $coaArray[2]['openingCreditTotal'] += $receivables->opening_balance_type == 'credit' ? $receivables->opening_balance : 0;
        $coaArray[1]['debitTotal'] += $receivables->debit;
        $coaArray[1]['creditTotal'] += $receivables->credit;

        $coaArray[1][10]['openingDebitTotal'] += $receivables->opening_balance_type == 'debit' ? $receivables->opening_balance : 0;
        $coaArray[1][10]['openingCreditTotal'] += $receivables->opening_balance_type == 'credit' ? $receivables->opening_balance : 0;

        $coaArray[1][10]['transactionDebitTotal'] += $receivables->coaDebitSum;
        $coaArray[1][10]['transactionCreditTotal'] += $receivables->coaCreditSum;
        $coaArray[1][10]['debitTotal'] += $receivables->debit;
        $coaArray[1][10]['creditTotal'] += $receivables->credit;
        //balance calculations

        $lastAsset = $assets[count($assets) - 1];
        $lastId = $lastAsset->id + 2;
        $supplierDetail = $personnelTB[1];
        $payables = new stdClass();
        if ($supplierDetail['debitTotal']) {
            $payables->debit = $supplierDetail['debitTotal'] + $supplierDetail['openingDebitTotal'] - $supplierDetail['openingCreditTotal'];
            $payables->credit = 0;
            if ($payables->debit < 0) {
                $payables->credit = abs($payables->debit);
                $payables->debit = 0;
            }
        }
        if ($supplierDetail['creditTotal']) {
            $payables->credit = $supplierDetail['creditTotal'] + $supplierDetail['openingCreditTotal'] - $supplierDetail['openingDebitTotal'];
            $payables->debit = 0;
            if ($payables->credit < 0) {
                $payables->debit = abs($payables->credit);
                $payables->credit = 0;
            }
        }

        if (!$supplierDetail['creditTotal'] && !$supplierDetail['debitTotal']) {
            $payables->debit = $supplierDetail['debitTotal'] + $supplierDetail['openingDebitTotal'] - $supplierDetail['openingCreditTotal'];
            $payables->credit = $supplierDetail['creditTotal'] + $supplierDetail['openingCreditTotal'] - $supplierDetail['openingDebitTotal'];

            if ($payables->credit < 0) {
                $payables->debit = abs($payables->credit);
                $payables->credit = 0;
            }
            if ($payables->debit < 0) {
                $payables->credit = abs($payables->debit);
                $payables->debit = 0;
            }
        }


        $payables->amount = $payables->debit > 0 ? $payables->debit : $payables->credit;
        $payables->opening_balance = $supplierDetail['openingCreditTotal'] > 0 ? $supplierDetail['openingCreditTotal'] : $supplierDetail['openingDebitTotal'];
        $payables->opening_balance_type = $supplierDetail['openingCreditTotal'] > 0 ? 'credit' : 'debit';
        $payables->coaDebitSum = abs($supplierDetail['transactionDebitTotal']);
        $payables->coaCreditSum = abs($supplierDetail['transactionCreditTotal']);
        $payables->id = $lastId;
        $payables->link = base_url() . 'account/personnel/suppliers';

        $payables->type = 1;
        $payables->name = $this->lang->line('payables');
        $payables->category = 11;


        $coaArray[2][11][] = $payables;

        //balance calculations
        $coaArray[2]['openingDebitTotal'] += $payables->opening_balance_type == 'debit' ? $payables->opening_balance : 0;
        $coaArray[2]['openingCreditTotal'] += $payables->opening_balance_type == 'credit' ? $payables->opening_balance : 0;
        $coaArray[2]['debitTotal'] += $payables->debit;
        $coaArray[2]['creditTotal'] += $payables->credit;

        $coaArray[2][11]['openingDebitTotal'] += $payables->opening_balance_type == 'debit' ? $payables->opening_balance : 0;
        $coaArray[2][11]['openingCreditTotal'] += $payables->opening_balance_type == 'credit' ? $payables->opening_balance : 0;

        $coaArray[2][11]['transactionDebitTotal'] += $payables->coaDebitSum;
        $coaArray[2][11]['transactionCreditTotal'] += $payables->coaCreditSum;
        $coaArray[2][11]['debitTotal'] += $payables->debit;
        $coaArray[2][11]['creditTotal'] += $payables->credit;
        //balance calculations

        unset($customerDetail['debitTotal']);
        unset($customerDetail['creditTotal']);
        unset($customerDetail['openingCreditTotal']);
        unset($customerDetail['openingDebitTotal']);
        unset($customerDetail['transactionDebitTotal']);
        unset($customerDetail['transactionCreditTotal']);
        unset($supplierDetail['debitTotal']);
        unset($supplierDetail['creditTotal']);
        unset($supplierDetail['openingCreditTotal']);
        unset($supplierDetail['openingDebitTotal']);
        unset($supplierDetail['transactionDebitTotal']);
        unset($supplierDetail['transactionCreditTotal']);
        $this->data['coa'] = $coaArray;
        //these are to display receivable and payable constituents toggle expand/collapse
        $this->data['customerDetail'] = $customerDetail;
        $this->data['supplierDetail'] = $supplierDetail;
        //COA

        $this->load->view('layout/header');
        $this->load->view('account/trial_balance/index', $this->data);
        $this->load->view('layout/footer');
    }
}
