<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Balance_sheet extends Account_Controller
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
        $this->level = $this->accountlib->getAccountSetting()->level;
        $this->date_system = $this->accountlib->getAccountSetting()->date_system;
        $this->datechooser = $this->setting_model->getDatechooser();
        $opening_balance_date=$this->datechooser=='bs'?$this->accountlib->getAccountSetting()->opening_balance_date_bs:$this->accountlib->getAccountSetting()->opening_balance_date;
        if(!$opening_balance_date){
            $financialYearStart=$this->accountlib->financialYearStart();
            $opening_balance_date=($this->datechooser=='bs'?$financialYearStart->year_starts_bs:$financialYearStart->year_starts);
        }
        $this->opening_balance_date = $opening_balance_date;
        $this->load->library('bikram_sambat');
        $this->financial_year = $this->session->userdata('account')['financial_year'];
    }

    public function index($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/balance_sheet');
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
        foreach ($systemCategories as $category) {
            $categories[$category->id] = $category;
        }
        $this->data['categories'] = $categories;
        //Categories

        //COA
        $assets = $this->account_COA_model->getCOAListForLedger($financialYear);


        $personnel = $this->personnel_model->getPersonnelTrialBalance($financialYear);

        $personnelbalances=array('supplierpayables'=>0,'supplierpayablesprev'=>0,'customerreceivables'=>0,'customerreceivablesprev'=>0);
        foreach ($personnel as $key=>$person){
            if($key==1){
                $personnelbalances['supplierpayablesprev']=$person['openingCreditTotal'] - $person['openingDebitTotal'];
                $personnelbalances['supplierpayables']=$person['creditTotal'] - $person['debitTotal'] + $personnelbalances['supplierpayablesprev'] ;
               if($financialYear == 1){
                    $personnelbalances['supplierpayablesprev']=0;
                }

            }
            if($key==2){
                $personnelbalances['customerreceivablesprev']=$person['openingDebitTotal'] - $person['openingCreditTotal'];
                $personnelbalances['customerreceivables']=$person['debitTotal'] - $person['creditTotal'] + $personnelbalances['customerreceivablesprev'];
               if($financialYear == 1){
                    $personnelbalances['customerreceivablesprev']=0;
                }
            }
        }
///////////////////////////
/// This section is introduced to compute the profit/loss as visible from Income statement
        $coaArraytmp = array();
        $assetstemp=$assets;
        foreach ($assetstemp as $asset) {
            if (in_array($asset->type, array(3, 4))) {
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
                $asset->balance = $asset->amount;
//                $asset->balance = $asset->openingbalance + $asset->amount;

                $coaArraytmp[$asset->type]['balance'] += $asset->balance;
                $coaArraytmp[$asset->type][$asset->category]['balance'] += $asset->balance;

                if ($financialYear == 1) {
                    $asset->openingbalance = 0;
                }
                $coaArraytmp[$asset->type]['openingbalance'] += $asset->opening_balance;
                $coaArraytmp[$asset->type][$asset->category]['openingbalance'] += $asset->opening_balance;
                $coaArraytmp[$asset->type][$asset->category][] = $asset;
            }
        }
//        echopreexit($coaArraytmp);
        $this->data['profitAmount']=$coaArraytmp[3]['balance']- $coaArraytmp[4]['balance'];


/// ///////////////////////

        //HERE
        $coaArray = array();
        foreach ($assets as $asset) {
        if(in_array($asset->type,array(1,2,5))) {
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
            $asset->balance =  $asset->amount;

            $coaArray[$asset->type]['balance'] += $asset->balance;
            $coaArray[$asset->type][$asset->category]['balance'] += $asset->balance;

            if ($financialYear == 1) {
                $asset->openingbalance = 0;
            }
            $coaArray[$asset->type]['openingbalance'] += $asset->openingbalance;
            $coaArray[$asset->type][$asset->category]['openingbalance'] += $asset->openingbalance;
            $coaArray[$asset->type][$asset->category][] = $asset;
        }
        }
//        echopreexit($coaArray);
        $coaArray[1]['balance']=$coaArray[1]['balance'] + $personnelbalances['customerreceivables'];
        $coaArray[1]['openingbalance']=$coaArray[1]['openingbalance'] + $personnelbalances['customerreceivablesprev'];
        $coaArray[1][10][] = (object)array('name'=>'Customer Receivables','balance'=>$personnelbalances['customerreceivables'],'openingbalance'=>$personnelbalances['customerreceivablesprev'],'link'=>'account/personnel/customers');
        $coaArray[1][10]['balance']=$coaArray[1][10]['balance']+$personnelbalances['customerreceivables'];
        $coaArray[1][10]['openingbalance']=$coaArray[1][10]['openingbalance']+$personnelbalances['customerreceivablesprev'];
        $coaArray[2]['balance']=$personnelbalances['supplierpayables'] + $coaArray[2]['balance'];
        $coaArray[2]['openingbalance']=$coaArray[2]['openingbalance'] + $personnelbalances['supplierpayablesprev'];
        $coaArray[2][11][] = (object)array('name'=>'Supplier Payables','balance'=>$personnelbalances['supplierpayables'],'openingbalance'=>$personnelbalances['supplierpayablesprev'], 'link'=>'account/personnel/suppliers');
        $coaArray[2][11]['balance']=$coaArray[2][11]['balance']+$personnelbalances['supplierpayables'];
        $coaArray[2][11]['openingbalance']=$coaArray[2][11]['openingbalance']+$personnelbalances['supplierpayablesprev'];
        $this->data['coa'] = $coaArray;
//        echopreexit($coaArray);


        //COA


        $this->load->view('layout/header');
        $this->load->view('account/balance_sheet/index', $this->data);
        $this->load->view('layout/footer');
    }
}

?>