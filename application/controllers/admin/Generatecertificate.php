<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class Generatecertificate extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('Customlib');
        $this->datechooser = $this->setting_model->getDatechooser();
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('generate_certificate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');
        //$this->data['certificateList'] = $this->Generatecertificate_model->certificateList();
        $certificateList = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/generatecertificate', $data);
        $this->load->view('layout/footer', $data);
    }

    function search() {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');
        //$data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $certificateList = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $certificate = $this->input->post('certificate_id');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');

                $this->form_validation->set_rules('certificate_id', 'lang:certificate', 'trim|required|xss_clean');




                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    $data['searchby'] = "filter";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $certificate = $this->input->post('certificate_id');
                    $data['certificate_id'] = $certificate;
                    $certificateResult = $this->Generatecertificate_model->getcertificatebyid($certificate);
                    $data['certificateResult'] = $certificateResult;
                    $resultlist = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist'] = $resultlist;
                    $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate_pdf', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $certificate) {
        $certificateResult = $this->Generatecertificate_model->getcertificatebyid($certificate);
        $data['certificateResult'] = $certificateResult;
        $resultlist = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist'] = $resultlist;
        //$this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/transfercertificate', $data);
        //$this->load->view('layout/footer', $data);
    }

    public function generatemultipledd() {
        //echo "<pre>"; print_r($_POST); echo "</pre>"; exit();
        $studentid = $this->input->post('data');
        $student_array = json_decode($studentid);
        $class = $this->input->post('class_id');
        $results = array();
        foreach ($student_array as $key => $value) {
            $student = $value->student_id;
            $result = $this->student_model->searchByClassStudent($class, $student);
            $results[] = $result;
            $certificate = $this->input->post('certificate_id');
            $certificateResult = $this->Generatecertificate_model->getcertificatebyid($certificate);
            $data['certificateResult'] = $certificateResult;
        }
        $data['resultlist'] = $results;
        $this->load->view('admin/certificate/stugeneratecertificate', $data);
        //$this->load->view('print/printFeesByGroupArray', $data);
    }

    public function generatemultiple() {

        $studentid = $this->input->post('data');
        $student_array = json_decode($studentid);
        $certificate_id = $this->input->post('certificate_id');
        $class = $this->input->post('class_id');
        $data = array();
        $results = array();
        $std_arr = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['certificate'] = $this->Generatecertificate_model->getcertificatebyid($certificate_id);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }
        $data['students'] = $this->student_model->getStudentsByArray($std_arr);
        //$certificates = $this->load->view('admin/certificate/printcertificate', $data, true);
        $certificates = $this->load->view('admin/certificate/certificate_design', $data, true);
        //$certificates = $this->load->view('admin/certificate/certificate_design_table', $data, true);
        echo $certificates;
    }

    public function generatemultiple_pdf()
    {
        $student_array = $this->input->post('data[]');
        $certificate_id = $this->input->post('certificate_id');
        $data = array();
        $data['datechooser'] = $this->datechooser;
        $data['sch_setting'] = $this->setting_model->get();
        $data['certificate'] = $this->Generatecertificate_model->getcertificatebyid($certificate_id);
        $students = $this->student_model->getStudentsByArray($student_array);
        $file_name = 'certificates-' . $data['certificate'][0]->certificate_name . '-' . date('Y-m-d');
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $pdfFilePath = $file_name . ".pdf";
        /*$layout = 'A4';
        $pdf_template = 'admin/certificate/certificate_design_pdf_mm';
        if ($data['certificate'][0]->layout == 2) {
            $layout = 'A4-L';
            $pdf_template = 'admin/certificate/certificate_design_pdf_landscape_mm';
        }*/
        $layout = 'A4-L';
        $pdf_template = 'admin/certificate/certificate_design_pdf_landscape_mm';
        $this->load->library('m_pdf', array(
            'mode' => 'utf-8',
            'format' => $layout,
            'mgl' => 0,
            'mgr' => 0,
            'mgt' => 0,
            'mgb' => 0,
            'mgh' => 0,
            'mgf' => 0,
        ));
        //$data['student'] = $students[0];
        //$this->load->view($pdf_template, $data);
        $today = $this->customlib->getCurrentTime('Y-m-d');
        $data['today_date'] = $today;
        if($this->datechooser == 'bs') {
            $data['today_date'] = $this->customlib->getBSDate($today);
        }
        $i = 1;
        foreach ($students as $student) {
            $data['student'] = $student;
            $html = $this->load->view($pdf_template, $data, true);
            if ($i > 1) {
                $this->m_pdf->pdf->AddPage();
            }
            /*if (!empty($data['certificate'][0]->background_image)) {
                $this->m_pdf->pdf->SetDefaultBodyCSS('background', "url('" . base_url('uploads/certificate/' . $data['certificate'][0]->background_image) . "')");
                $this->m_pdf->pdf->SetDefaultBodyCSS('background-image-resize', 6);
            }*/
            $this->m_pdf->pdf->SetDefaultBodyCSS('background', "url('" . base_url('uploads/certificate_bg_static/eshikshya_cert_bg_hori.jpg') . "')");
            $this->m_pdf->pdf->SetDefaultBodyCSS('background-image-resize', 6);
            $this->m_pdf->pdf->WriteHTML($html);
            $i++;
        }
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

}