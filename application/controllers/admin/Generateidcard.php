<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Generateidcard extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->datechooser = $this->setting_model->getDatechooser();
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('generate_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generateidcard');
        //$this->data['certificateList'] = $this->Generatecertificate_model->certificateList();
        $idcardlist = $this->Generateidcard_model->getstudentidcard();
        $data['idcardlist'] = $idcardlist;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/generateidcard', $data);
        $this->load->view('layout/footer', $data);
    }

    function search()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generateidcard');
        //$data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $idcardlist = $this->Generateidcard_model->getstudentidcard();
        $data['idcardlist'] = $idcardlist;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/Generateidcard', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $id_card = $this->input->post('id_card');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');

                $this->form_validation->set_rules('id_card', 'lang:id_card', 'trim|required|xss_clean');
                if ($this->form_validation->run() == FALSE) {

                } else {
                    $data['searchby'] = "filter";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $id_card = $this->input->post('id_card');
                    $idcardResult = $this->Generateidcard_model->getidcardbyid($id_card);
                    $data['idcardResult'] = $idcardResult;
                    $resultlist = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist'] = $resultlist;
                    $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generateidcard', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $idcard)
    {
        //print_r($idcard); exit();
        $idcardlist = $this->Generateidcard_model->getidcardbyid($idcard);
        $data['idcardlist'] = $idcardlist;
        $resultlist = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist'] = $resultlist;
        //$this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/studentidcard', $data);
        //$this->load->view('layout/footer', $data);
    }

    public function generatemultiple()
    {
        $std_arr = $this->input->post('data[]');
        $idcard = $this->input->post('id_card_id');
        $data = array();
        $data['datechooser'] = $this->datechooser;
        $data['sch_setting'] = $this->setting_model->get();
        $data['id_card'] = $this->Generateidcard_model->getidcardbyid($idcard);

        $data['students'] = $this->student_model->getStudentsByArray($std_arr);
        $view = 'admin/certificate/generatemultiple';
        if($data['id_card'][0]->layout == 2) {
            $view = 'admin/certificate/generatemultiple_v';
        }

        //$this->load->view($view, $data);
        $id_cards = $this->load->view($view, $data, true);
        $file_name = 'id_cards-' . $data['id_card'][0]->title . '-' . date('Y-m-d');
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $pdfFilePath = $file_name . ".pdf";
        $this->load->library('m_pdf', array(
            'mode' => 'utf-8',
            'format' => 'A4',
            'mgl' => 0,
            'mgr' => 0,
            'mgt' => 0,
            'mgb' => 0,
            'mgh' => 0,
            'mgf' => 0,
        ));
        //$this->m_pdf->pdf->showImageErrors = true;
        $this->m_pdf->pdf->WriteHTML($id_cards);
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

}