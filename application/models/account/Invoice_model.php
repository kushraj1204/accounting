<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Invoice_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Accountlib');
        $this->tableName = 'acc_invoice';
        $this->financial_year = $this->session->userdata('account')['financial_year'];

        $this->level = $this->accountlib->getAccountSetting()->level;
        //foreign models
        $this->load->model('account/personnel_model');
        $this->load->model('account/transaction_model');
        $this->load->model('account/account_COA_model');
        $this->load->library('bikram_sambat');

    }

    function getLastId()
    {
        $this->db->select('invoice.id');
        $this->db->from($this->tableName . ' as invoice');
        $query = $this->db->get();
        $row = $query->last_row();
        return (int)$row->id;
    }

    function getInvoiceDetail($id)
    {
        $this->db->select('invoice.*');
        $this->db->select('personnel.name, personnel.code as personnel_code, personnel.type as personnel_type, personnel.email');
        $this->db->from($this->tableName . ' as invoice');
        $this->db->join('acc_personnel as personnel', 'personnel.id = invoice.customer_id', 'left');
        $this->db->where('invoice.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getInvoiceList($postData = null)
    {

        $response = array();
//    echopreexit($postData);
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        ## Total number of records without filtering
        $records = $this->getRecords('', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($searchValue, $rowperpage, $start);

        $data = array();

        foreach ($records as $key => $record) {
            $invoice_date = $this->datechooser == 'bs' ? $record->invoice_date_bs : $this->customlib->formatDate($record->invoice_date);
            $actionbuttons = '';
            if ($this->rbac->hasPrivilege('account_invoice', 'can_view')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/invoice/view/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('account_invoice', 'can_edit')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/invoice/edit/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('account_invoice', 'can_delete')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/invoice/delete/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '"
                                                   onclick="return confirm(\'' . $this->lang->line("delete_confirm") . '\');">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

            }

            $pagenum = $start / $rowperpage + 1;
            if ($record->amount > 0) {
                $data[] = array(
                    "count" => ($key + 1) + ($rowperpage * ($pagenum - 1)),
                    "created_date" => $invoice_date,
                    "invoice_no" => $record->code,
                    "customer_code" => $record->customer_code,
                    "customer_name" => $record->customer_name,
                    "amount" => $this->accountlib->currencyFormat($record->amount, true, 2, '.', ',', true),
                    "action" => $actionbuttons

                );
            }
        }
//        echopreexit($records);
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

    function getRecords($searchValue, $rowperpage, $start)
    {
        $this->db->select('ABS(SUM(CASE WHEN amount_type = "credit" THEN amount ELSE 0 END)) - ABS(SUM(CASE WHEN amount_type = "debit" THEN amount ELSE 0 END)) as amount');
        $this->db->select('parent_id');
        $this->db->from('acc_transaction_logs');
        $this->db->where(array('parent_type' => 'invoice', 'category_type' => 'coa', 'status' => 1));
        $this->db->group_by('parent_id');
        $sum_clause = $this->db->get_compiled_select();

        $this->db->select('invoice.*');
        $this->db->select('logs.amount');
        $this->db->select('personnel.code as customer_code, personnel.name as customer_name');
        $this->db->from($this->tableName . ' as invoice');
        $this->db->join('(' . $sum_clause . ') as logs', 'invoice.id = logs.parent_id', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = invoice.customer_id', 'left');
        $this->db->group_by('invoice.id');
        $this->db->where('invoice.financial_year', $this->financial_year);
        if ($searchValue != '') {
            $this->db->like('personnel.name', $searchValue);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }

        return $this->db->get()->result();
    }

    function getInvoices()
    {
        $this->db->select('ABS(SUM(CASE WHEN amount_type = "credit" THEN amount ELSE 0 END)) - ABS(SUM(CASE WHEN amount_type = "debit" THEN amount ELSE 0 END)) as amount');
        $this->db->select('parent_id');
        $this->db->from('acc_transaction_logs');
        $this->db->where(array('parent_type' => 'invoice', 'category_type' => 'coa', 'status' => 1));
        $this->db->group_by('parent_id');
        $sum_clause = $this->db->get_compiled_select();

        $this->db->select('invoice.*');
        $this->db->select('logs.amount');
        $this->db->select('personnel.code as customer_code, personnel.name as customer_name');
        $this->db->from($this->tableName . ' as invoice');
        $this->db->join('(' . $sum_clause . ') as logs', 'invoice.id = logs.parent_id', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = invoice.customer_id', 'left');
        $this->db->group_by('invoice.id');
        $this->db->where('invoice.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->result();
    }

    function getInvoiceEntries($id)
    {
        $this->db->select('entry.*, invoice.description as narration,invoice.code as invoicecode,invoice.due_date as invoiceduedate');
        $this->db->select('coa.category, coa.subcategory1, coa.subcategory2, coa.type as coa_type, coa.code as coa_code');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);
        /*$this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa_categories.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);*/


        $this->db->from('acc_invoice_entry as entry');
        $this->db->join('acc_invoice as invoice', 'invoice.id = entry.invoice_id', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory2', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id != 0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'subcategory2.id = coa.subcategory2', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id != 0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'subcategory1.id = coa.subcategory1', 'left');
        } elseif ($this->level >= 3) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN category.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as category', 'category.id = coa.category', 'left');
        }
        $this->db->where('invoice_id', $id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function saveInvoice($data)
    {
        $data['financial_year'] = $this->financial_year;

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $id = $data['id'];
            unset($data['id']);
            unset($data['code']);
            $this->db->update($this->tableName, $data);
        } else {
            unset($data['id']);
            $this->db->insert($this->tableName, $data);
            $id = $this->db->insert_id();
        }
        $this->updateInvoiceEntry($id);
        return $id;
    }

    function updateInvoiceEntry($id)
    {
        $input = $this->input;
        $customer_id = $input->post('customer_id', 0);
        $customer_type = $input->post('personnel_type', 'customer');
        $coa_id = $input->post('coa_id[]', []);
        $coa_type = $input->post('coa_type[]', []);
        $quantity = $input->post('quantity[]', []);
        $rate = $input->post('rate[]', []);
        $entry_id = $input->post('entry_id[]', []);
        $is_new = $input->post('is_new[]', []);
        if (count($coa_id) == 0) {
            return false;
        }
        $insertData = [];
        $updateData = [];
        $updateIds = [];
        $amount = 0;
        for ($i = 0; $i < count($coa_id); $i++) {
            $coaId = 0;
            $personnelId = 0;
            $multiplier = 1;
            if ($coa_type[$i] == 'coa') {
                $coaId = $coa_id[$i];
                $coas = $this->account_COA_model->get();
                $entryCoas = array();
                foreach ($coas as $item) {
                    $entryCoas[$item->id] = $item;
                }
                $type = $this->checkDebitCredit($entryCoas[$coaId]->type);
                $multiplier = $this->transaction_model->getMultiplier('coa', $entryCoas[$coaId]->type, $type);
            } elseif ($coa_type[$i] == 'personnel') {
                $personnelId = $coa_id[$i];
            }
            $amount += $multiplier * $quantity[$i] * $rate[$i];
            if ($is_new[$i] == 1) {
                $insertData[] = array(
                    'coa_id' => $coaId,
                    'personnel_id' => $personnelId,
                    'quantity' => $quantity[$i],
                    'rate' => $rate[$i],
                    'invoice_id' => $id,
                );
            } else {
                $updateIds[] = $entry_id[$i];
                $updateData[] = array(
                    'coa_id' => $coaId,
                    'personnel_id' => $personnelId,
                    'quantity' => $quantity[$i],
                    'rate' => $rate[$i],
                    'id' => $entry_id[$i],
                );
            }
        }

        if (count($updateData) > 0) {
            $this->db->update_batch('acc_invoice_entry', array_filter($updateData), 'id');
        }
        if (count($updateData) >= 0) {
            if (count($updateIds) > 0) {
                $this->db->where_not_in('id', $updateIds);
            }
            $this->db->where('invoice_id', $id);
            $this->db->delete('acc_invoice_entry');
        }

        if (count($insertData) > 0) {

            $this->db->insert_batch('acc_invoice_entry', array_filter($insertData));
        }

        $currentDateTime = $this->customlib->getCurrentTime();

        $data = array();

        if ($customer_id > 0) {
            $data[] = array(
                'parent_id' => $id,
                'parent_type' => 'invoice',
                'category_id' => $customer_id,
                'category_type' => 'customer',
                'status' => 1,
                'amount' => abs($amount),
                'amount_type' => $customer_type == 'customer' ? 'debit' : 'credit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            //update logs
        }

        //update logs for entries
        $entries = $this->getInvoiceEntries($id);
        foreach ($entries as $entry) {
            $category_id = ($entry->coa_id > 0) ? $entry->coa_id : $entry->personnel_id;
            $category_type = ($entry->coa_id > 0) ? 'coa' : $entry->coa_category;
            $entryAmount = $entry->rate * $entry->quantity;
            $type = 'credit';
            $parent_category = $entry->coa_type;
            if ($category_type == 'coa') {
                $type = $this->checkDebitCredit($parent_category);
            }

            $multiplier = $this->transaction_model->getMultiplier($category_type, $parent_category, $type);
            $data[] = array(
                'parent_id' => $id,
                'parent_type' => 'invoice',
                'category_id' => $category_id,
                'category_type' => $category_type,
                'status' => 1,
                'amount' => $multiplier * $entryAmount,
                'amount_type' => $type,
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
        }
        if (count($data)) {
            $this->transaction_model->updateLogs($id, 'invoice', $currentDateTime, $data);
        }
    }

    function delete($id)
    {
        $this->db->delete('acc_transaction_logs', array('parent_id' => $id, 'parent_type' => 'invoice'));
        $this->db->delete('acc_invoice_entry', array('invoice_id' => $id));
        $this->db->delete($this->tableName, array('id' => $id));
        return true;
    }

    function getDueInvoiceFor($customer_id = 0, $all = true, $feeonly = false, $outstanding = 0)
    {
        $this->db->select('"Invoice" as dueType, invoice.description as narration, invoice.*');
//        ABS(tlogs.amount) as amount,
        $this->db->select('SUM(entry.rate) as amount, 
                SUM(rdt.received_amount) as partialpaidamount, 
                GROUP_CONCAT(dt.id) as pastpaymentids, 
                GROUP_CONCAT(dt.receipt_no) as pastpaymentcodes
        ');
        $this->db->from('acc_invoice_entry as entry');
        $this->db->select('CASE
                           WHEN invoice.status = 0 THEN "Unpaid"
                           WHEN invoice.status = -1 THEN "Partialpaid"
                           END as paymentType', FALSE);
        $this->db->join('acc_invoice as invoice', 'invoice.id = entry.invoice_id', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
//        $this->db->join('acc_transaction_logs as tlogs', 'tlogs.parent_id = invoice.id AND tlogs.parent_type="Invoice" AND tlogs.category_type IN ("customer","supplier") AND tlogs.category_id="' . $customer_id . '"', 'inner');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_receipt_details as rdt', '(rdt.invoice_id = invoice.id AND rdt.status=0)', 'left');
        $this->db->join('acc_receipt as dt', '(dt.id = rdt.receipt_id)', 'left');
        $this->db->where('invoice.customer_id', $customer_id);
        if (!$all) {
            $this->db->where('invoice.fee_id = 0');
        }
        if ($feeonly) {
            $this->db->where('invoice.fee_id != 0');
        }
        if ($outstanding > 0) {
            $this->db->where('entry.coa_id = ' . $outstanding);
        }
        $this->db->where_in('invoice.status', array(0, -1));
        $this->db->group_by('invoice.id');
        $this->db->order_by('id', 'ASC');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getDueInvoiceEntries($ids)
    {
        $this->db->select('entry.*,invoice.code');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);

        $this->db->from('acc_invoice_entry as entry');
        $this->db->join('acc_invoice as invoice', 'invoice.id = entry.invoice_id', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->where_in('invoice_id', $ids);
        $this->db->order_by('invoice.code', 'ASC');
        $this->db->order_by('id', 'ASC');

        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        $query = $this->db->get();
        return $query->result();
    }

    function markAsUncleared($id)
    {
        $data = array('status' => 0);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }

        $this->db->update('acc_invoice', $data);
        return;
    }

    function markAsCleared($id)
    {

        $data = array('status' => 1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_invoice', $data);
        return;
    }

    function markAsPartiallyPaid($id)
    {
        $data = array('status' => -1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_invoice', $data);
        return;
    }

    function markAsCompletelyPaid($id)
    {
        $data = array('status' => 1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_invoice', $data);
        return;
    }

    function updateInvoiceStatus($data)
    {

        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_invoice', $data);
        return;
    }

    function changeStatus($data)
    {
        $this->db->update_batch('acc_invoice', $data, 'id');
        return;
    }

    public function pendingInitialFees($student_id)
    {
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_invoice', $data);
        return;
    }

    public function generateFeeInvoice($data)
    {
        $student = $data['student'];
        $fee = $data['fee'];
        $settings = $this->accountlib->getAccountSetting();
        $invoice_prefix = '';
        if ($settings->use_invoice_prefix) {
            $invoice_prefix = $settings->invoice_prefix;
        }
        $lastId = $this->getLastId();
        $lastId = $settings->invoice_start + $lastId;
        $code = $invoice_prefix . $lastId;

        $bs_year = $fee['due_date_bs'];
        $bs_date = explode('-', $bs_year);

        $customer = $this->personnel_model->checkStudent($student['id'], 'student');

        $created_on = date("Y-m-d", strtotime($fee['created_on']));
        $curyear = date('Y', strtotime($created_on));
        $month = date('m', strtotime($created_on));
        $day = date('d', strtotime($created_on));
        $this->bikram_sambat->setEnglishDate($curyear, $month, $day);
        $created_on_bs = $this->bikram_sambat->toNepaliString();

        if ($customer->id > 0) {
            $formValues = array(
                'code' => $code,
                'invoice_date' => date('Y-m-d', strtotime($fee['created_on'])),
                'due_date' => $fee['due_date'],
                'invoice_date_bs' => $created_on_bs,
                'due_date_bs' => $fee['due_date_bs'],
                'reference_no' => $fee['id'],
                'fee_id' => $fee['id'],
                'registered_no' => '',
                'customer_id' => $customer->id,
                'description' => $this->lang->line('fee_of') . ' ' . $fee['fee_month_name'],
                'bs_year' => $bs_date[0],
                'bs_month' => $bs_date[1],
                'bs_day' => $bs_date[2],
                'financial_year' => $this->financial_year,
                'status' => 0,
                'created_date' => $this->customlib->getCurrentTime(),
                'created_by' => $this->session->userdata['admin']['id'] ? $this->session->userdata['admin']['id'] : 0,
            );

            $this->db->insert($this->tableName, $formValues);
            $invoice_id = $this->db->insert_id();

            $feeData['invoice_id'] = $invoice_id;
            $this->db->where('id', $fee['id']);
            $this->db->update('student_session_fees', $feeData);

            $discountCOA = $this->account_COA_model->getCOAbyItemType('discount');

            $fee_data = json_decode($fee['fee_data']);
            $total_discount = 0;
            $amount = 0;
            $discount = array();
            foreach ($fee_data as $fee_item) {
                $discount_amount = 0;
                if ($fee_item->amount > 0) {
                    $fee_id = $fee_item->feetype_id;
                    $checkType = implode('_', explode(' ', strtolower($fee_item->type)));
                    if (strcmp('hostel_fee', $checkType) == 0) {
                        $fee_id = 8;
                    } else if (strcmp('transport_fee', $checkType) == 0) {
                        $fee_id = 9;
                    }
                    $insertData[] = array(
                        'coa_id' => $fee_id,
                        'personnel_id' => 0,
                        'quantity' => 1,
                        'rate' => $fee_item->amount,
                        'invoice_id' => $invoice_id,
                    );
                }
                $amount += $fee_item->amount;
                foreach ($fee_item->discount_particulars as $discount_item) {
                    if ($discount_item->amount > 0) {
                        $currentDiscount = $discountCOA[$discount_item->fees_discount_id];
                        $insertData[] = array(
                            'coa_id' => $currentDiscount->id,
                            'personnel_id' => 0,
                            'quantity' => 1,
                            'rate' => $discount_item->amount,
                            'invoice_id' => $invoice_id,
                        );
                    }
                    $discount_amount += $discount_item->amount;
                }

                $total_discount += $discount_amount;
            }
            $total_amount = $amount - $total_discount;

            //for fine
            $insertData[] = array(
                'coa_id' => 7,
                'personnel_id' => 0,
                'quantity' => 1,
                'rate' => 0,
                'invoice_id' => $invoice_id,
            );

            $currentDateTime = $this->customlib->getCurrentTime();
            if (count($insertData) > 0) {

                $this->db->insert_batch('acc_invoice_entry', array_filter($insertData));

                if ($customer->id > 0) {
                    $transactionData[] = array(
                        'parent_id' => $invoice_id,
                        'parent_type' => 'invoice',
                        'category_id' => $customer->id,
                        'category_type' => 'customer',
                        'status' => 1,
                        'amount' => $total_amount,
                        'amount_type' => 'debit',
                        'created_date' => $currentDateTime,
                        'created_by' => $this->session->userdata['admin']['id'] ? $this->session->userdata['admin']['id'] : 0,
                        'financial_year' => $this->financial_year,
                    );
                    //update logs
                }

                //update logs for entries
                $entries = $this->getInvoiceEntries($invoice_id);
                foreach ($entries as $entry) {
                    $category_id = ($entry->coa_id > 0) ? $entry->coa_id : $entry->personnel_id;
                    $category_type = ($entry->coa_id > 0) ? 'coa' : $entry->coa_category;
                    $entryAmount = $entry->rate * $entry->quantity;

                    $type = 'credit';
                    $parent_category = $entry->coa_type;
                    if ($category_type == 'coa') {
                        $type = $this->checkDebitCredit($parent_category);
                    }

                    $multiplier = $this->transaction_model->getMultiplier($category_type, $parent_category, $type);
                    $transactionData[] = array(
                        'parent_id' => $invoice_id,
                        'parent_type' => 'invoice',
                        'category_id' => $category_id,
                        'category_type' => $category_type,
                        'status' => 1,
                        'amount' => $multiplier * $entryAmount,
                        'amount_type' => $type,
                        'created_date' => $currentDateTime,
                        'created_by' => $this->session->userdata['admin']['id'],
                        'financial_year' => $this->financial_year,
                    );
                }
                if (count($transactionData)) {
                    $this->transaction_model->updateLogs($invoice_id, 'invoice', $currentDateTime, $transactionData);
                }
            }

            if (count($discount) > 0) {
                $invoice = $this->getInvoiceDetail($invoice_id);
                $invoiceData['description'] = ($invoice->description .
                    "\nTotal Discount: " . $total_discount);
                $this->db->where('id', $invoice_id);
                $this->db->update($this->tableName, $invoiceData);
            }
        }
    }


    public function addOpeningBalanceAsOutStandingFees($data)
    {

        $settings = $this->accountlib->getAccountSetting();

        $invoice_prefix = '';
        if ($settings->use_invoice_prefix) {
            $invoice_prefix = $settings->invoice_prefix;
        }
        $lastId = $this->getLastId();
        $lastId = $settings->invoice_start + $lastId;
        $code = $invoice_prefix . $lastId;
        $customer = $this->checkStudent($data['parent_id'], 'student');
        $created_on = date("Y-m-d");
        $curyear = date('Y', strtotime($created_on));
        $month = date('m', strtotime($created_on));
        $day = date('d', strtotime($created_on));
        $this->bikram_sambat->setEnglishDate($curyear, $month, $day);
        $created_on_bs = $this->bikram_sambat->toNepaliString();
        $bs_year = $created_on_bs;
        $bs_date = explode('-', $bs_year);

        $due_date = date('Y-m-d', strtotime("+1 week"));
        $dueyear = date('Y', strtotime($due_date));
        $duemonth = date('m', strtotime($due_date));
        $dueday = date('d', strtotime($due_date));
        $this->bikram_sambat->setEnglishDate($dueyear, $duemonth, $dueday);
        $due_bs = $this->bikram_sambat->toNepaliString();
        $due_bs_year = $due_bs;
        $bs_due_date = explode('-', $due_bs_year);


        if ($customer->id > 0) {
            $formValues = array(
                'code' => $code,
                'invoice_date' => $created_on,
                'due_date' => $due_date,
                'invoice_date_bs' => $created_on_bs,
                'due_date_bs' => $due_bs,
                'reference_no' => $customer->id . '#accrued#' . $data['parent_id'],
                'fee_id' => 0,
                'registered_no' => '',
                'customer_id' => $customer->id,
                'description' => 'Outstanding fees',
                'bs_year' => $bs_date[0],
                'bs_month' => $bs_date[1],
                'bs_day' => $bs_date[2],
                'financial_year' => $this->financial_year,
                'status' => 0,
                'created_date' => $this->customlib->getCurrentTime(),
                'created_by' => $this->session->userdata['admin']['id'] ? $this->session->userdata['admin']['id'] : 0,
            );
            $this->db->insert($this->tableName, $formValues);
            $invoice_id = $this->db->insert_id();
            //14 is set for outstanding income
            $insertData[] = array(
                'coa_id' => 14,
                'personnel_id' => 0,
                'quantity' => 1,
                'rate' => $data['balance'],
                'invoice_id' => $invoice_id,
            );

            $currentDateTime = $this->customlib->getCurrentTime();
            if (count($insertData) > 0) {
                $this->db->insert_batch('acc_invoice_entry', array_filter($insertData));
//                if ($customer->id > 0) {
//                    $transactionData[] = array(
//                        'parent_id' => $invoice_id,
//                        'parent_type' => 'invoice',
//                        'category_id' => $customer->id,
//                        'category_type' => 'customer',
//                        'status' => 1,
//                        'amount' => $data['balance'],
//                        'amount_type' => 'debit',
//                        'created_date' => $currentDateTime,
//                        'created_by' => $this->session->userdata['admin']['id'] ? $this->session->userdata['admin']['id'] : 0,
//                        'financial_year' => $this->financial_year,
//                    );
//                    //update logs
//                }

                //update logs for entries
//                $entries = $this->getInvoiceEntries($invoice_id);
//                foreach ($entries as $entry) {
//                    $category_id = ($entry->coa_id > 0) ? $entry->coa_id : $entry->personnel_id;
//                    $category_type = ($entry->coa_id > 0) ? 'coa' : $entry->coa_category;
//                    $entryAmount = $entry->rate * $entry->quantity;
//
//                    $type = 'credit';
//                    $parent_category = $entry->coa_type;
//                    if ($category_type == 'coa') {
//                        $type = $this->checkDebitCredit($parent_category);
//                    }
//
//                    $multiplier = $this->transaction_model->getMultiplier($category_type, $parent_category, $type);
//                    $transactionData[] = array(
//                        'parent_id' => $invoice_id,
//                        'parent_type' => 'invoice',
//                        'category_id' => $category_id,
//                        'category_type' => $category_type,
//                        'status' => 1,
//                        'amount' => $multiplier * $entryAmount,
//                        'amount_type' => $type,
//                        'created_date' => $currentDateTime,
//                        'created_by' => $this->session->userdata['admin']['id'],
//                        'financial_year' => $this->financial_year,
//                    );
//                }
//                if (count($transactionData)) {
//                    $this->transaction_model->updateLogs($invoice_id, 'invoice', $currentDateTime, $transactionData);
//                }
            }


        }
    }

    public function checkStudent($parent_id, $category)
    {
        $this->db->select('*')->from('acc_personnel')->where(array('parent_id' => $parent_id, 'category' => $category));
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function checkOutstandingInvoiceForStudent($student_id)
    {
        $this->db->select('recpt.*,invent.rate as invoice_amount')
            ->from('acc_invoice as inv')
            ->join('acc_invoice_entry as invent', '(inv.id = invent.invoice_id AND invent.coa_id = 14)', 'inner')
            ->join('acc_personnel as person', '(inv.customer_id = person.id AND person.category = "student" AND person.parent_id="' . $student_id . '")', 'inner')
            ->join('acc_receipt_details as recpt', '(recpt.invoice_id = inv.id AND recpt.status = 0)', 'left')
            ->where('inv.status < 1')
            ->order_by('id', 'desc')
            ->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        if (!$result->id && $result->invoice_amount) {
            $result->total = $result->invoice_amount;
            $result->remaining_amount = $result->invoice_amount;
            $result->received_amount = 0;
        }
        return $result;
    }


    function getConcernedCustomerId($invoice_id)
    {
        $this->db->select('invoice.customer_id');
        $this->db->from($this->tableName . ' as invoice');
        $this->db->where('id', $invoice_id);
        $query = $this->db->get();
        $row = $query->row();
        return (int)$row->customer_id;
    }

    function checkDebitCredit($root_parent)
    {
        switch ($root_parent) {
            case 1: //assets
            case 2: //liabilities
            case 3: //income
            case 5: //equity
                $type = 'credit';
                break;
            case 4: //expenses
                $type = 'debit';
                break;
        }
        return $type;
    }

    function updateFineData($fine, $student_id, $invoice_id)
    {
        $this->db->select('id');
        $this->db->from('acc_personnel as per');
        $this->db->where('per.parent_id', $student_id);
        $this->db->where('per.category', 'student');
        $query = $this->db->get();
        $personnel_id = $query->row()->id;
        //fetched the personnel id

        $this->db->set('rate', 'rate+' . $fine, FALSE);
        $this->db->where('coa_id', 7);
        $this->db->where('personnel_id', 0);
        $this->db->where('quantity', 1);
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where(array(
            'coa_id' => 7,
            'personnel_id' => 0,
            'quantity' => 1,
            'invoice_id' => $invoice_id
        ));
        $queryinvoiceentry = $this->db->update('acc_invoice_entry');
//        echo "invoice entry".last_query();
        //updated fine amount in invoice_entry table

        $this->db->set('amount', 'amount+' . $fine, FALSE);
        $this->db->where('parent_id', $invoice_id);
        $this->db->where('parent_type', 'invoice');
        $this->db->where('category_id', 7);
        $this->db->where('category_type', 'coa');
        $this->db->where('amount_type', 'credit');
        $this->db->where('status', 1);
        $querytransactionlogs = $this->db->update('acc_transaction_logs');
        //updated fine entry amount in log table
//        echo "log entry 1".last_query();

        $this->db->where('parent_id', $invoice_id);
        $this->db->where('parent_type', 'invoice');
        $this->db->where('category_id', $personnel_id);
        $this->db->where('category_type', 'customer');
        $this->db->set('amount', 'amount+' . $fine, FALSE);
        $querytransactionlogs = $this->db->update('acc_transaction_logs');
//        echo "log entry 2".last_query();
        //updated receivable amount in log table by adding fine
    }
}