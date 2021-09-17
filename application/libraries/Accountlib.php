<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accountlib
{

    var $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('user_agent');
        $this->CI->load->model('account/account_model', '', TRUE);

        if (!$this->CI->session->userdata('financial_year')) {
            $currentFinancialYearID = $this->CI->account_model->getCurrentFinancialYearID();
            $account = array(
                'financial_year' => $currentFinancialYearID
            );
            $this->CI->session->set_userdata('account', $account);
        }
    }

    function getAccountSetting()
    {
        $settings = $this->CI->account_model->getGeneralSettings();
        if (count($settings) > 0) {
            return $settings;
        } else {
            return FALSE;
        }
    }

    function financialYearStart($id = 1)
    {
        $financial_year = $this->CI->account_model->getFinancialYear($id);
        if ($financial_year > 0) {
            return $financial_year;
        } else {
            return FALSE;
        }
    }

    function roundOffAmount($amount)
    {
        $settings = $this->CI->account_model->getGeneralSettings();
        switch ($settings->round_to) {
            case 1:
                $amount = round($amount, 2);
                break;
            case 2:
                $amount = ceil($amount);
                break;
            default:
                $amount = (int)$amount;
                break;
        }
        return $amount;
    }

    function checkEditPermission($created_date, $financial_year, $field)
    {
        $permission = false;
        $settings = $this->CI->account_model->getGeneralSettings();
        $field_value = $settings->{$field};
        switch ($field_value) {
            case 1:
                $adminUser = $this->CI->session->userdata('admin');
                if ($adminUser['username'] == "Admin" || !strcmp(strtolower(trim($adminUser['username'])), strtolower("Super Admin"))) {
                    $permission = true;
                }
                break;
            case 2:
                $currentDate = strtotime($this->CI->customlib->getCurrentTime());
                $days30 = $this->CI->customlib->addDaysReturnStrtotime($created_date, 30);
                if ($days30 >= $currentDate) {
                    $permission = true;
                }
                break;
            case 3:
                if ($financial_year == $this->CI->session->userdata('account')['financial_year']) {
                    $permission = true;
                }
                break;
            default:
                $permission = false;
                break;
        }
        return $permission;
    }

    function currencyFormat($amount, $showSymbol = true, $decimals = 2, $decimalpoint = '.', $separator = ',', $space = true)
    {
        $space = $space ? " " : "";
        //$amount = $this->roundOffAmount($amount);
        $currency_symbol = $showSymbol ? $this->CI->customlib->getSchoolCurrencyFormat() : '';
        return $currency_symbol . $space . number_format($amount, $decimals, $decimalpoint, $separator);
    }
}
