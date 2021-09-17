<?php

class Admitcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('admitcard_model');
        $this->data = array(
            'upload_path' => 'uploads/student_admit_card/',
        );
        $this->data['cards'] = $this->admitcard_model->getAll();
        $this->data['datechooser'] = $this->setting_model->getDatechooser();
    }

    public function index($id = "")
    {
        if (!$this->rbac->hasPrivilege('student_id_card', 'can_view')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->data['card'] = $this->admitcard_model->find($id);
            $this->data['form_title'] = 'Edit Admit Card';
        } else {
            $this->data['card'] = array();
            $this->data['form_title'] = 'Add Admit Card';
        }
        $this->_handleSubmit();
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/admitcard');
        $this->load->view('layout/header');
        $this->load->view('admin/admitcard/index', array('data' => $this->data));
        $this->load->view('layout/footer');
    }

    private function _handleSubmit()
    {
        if ($this->input->method(true) === 'POST') {
            if (!$this->rbac->hasPrivilege('student_id_card', 'can_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('school_name', 'lang:school_name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('address', 'lang:address_phone_email', 'trim|required|xss_clean');
            $this->form_validation->set_rules('title', 'lang:title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('examination', 'lang:examination', 'trim|required|xss_clean');
            if ($this->form_validation->run() !== FALSE) {
                $roll_no = 0;
                $admission_no = 0;
                $studentname = 0;
                $class = 0;
                $guardianname = 0;
                $fathername = 0;
                $mothername = 0;
                $address = 0;
                $phone = 0;
                $dob = 0;
                $bloodgroup = 0;

                if ($this->input->post('is_active_admission_no') == 1) {
                    $admission_no = $this->input->post('is_active_admission_no');
                }
                if ($this->input->post('is_active_roll_no') == 1) {
                    $roll_no = $this->input->post('is_active_roll_no');
                }
                if ($this->input->post('is_active_student_name') == 1) {
                    $studentname = $this->input->post('is_active_student_name');
                }
                if ($this->input->post('is_active_class') == 1) {
                    $class = $this->input->post('is_active_class');
                }
                if ($this->input->post('is_active_guardian_name') == 1) {
                    $guardianname = $this->input->post('is_active_guardian_name');
                }
                if ($this->input->post('is_active_father_name') == 1) {
                    $fathername = $this->input->post('is_active_father_name');
                }
                if ($this->input->post('is_active_mother_name') == 1) {
                    $mothername = $this->input->post('is_active_mother_name');
                }
                if ($this->input->post('is_active_address') == 1) {
                    $address = $this->input->post('is_active_address');
                }
                if ($this->input->post('is_active_phone') == 1) {
                    $phone = $this->input->post('is_active_phone');
                }
                if ($this->input->post('is_active_dob') == 1) {
                    $dob = $this->input->post('is_active_dob');
                }
                if ($this->input->post('is_active_blood_group') == 1) {
                    $bloodgroup = $this->input->post('is_active_blood_group');
                }
                $data = array(
                    'title' => $this->input->post('title'),
                    'school_name' => $this->input->post('school_name'),
                    'school_address' => $this->input->post('address'),
                    'header_color' => $this->input->post('header_color'),
                    'enable_admission_no' => $admission_no,
                    'enable_roll_no' => $roll_no,
                    'enable_student_name' => $studentname,
                    'enable_class' => $class,
                    'enable_guardian_name' => $guardianname,
                    'enable_fathers_name' => $fathername,
                    'enable_mothers_name' => $mothername,
                    'enable_address' => $address,
                    'enable_phone' => $phone,
                    'enable_dob' => $dob,
                    'enable_blood_group' => $bloodgroup,
                    'status' => 1,
                    'examination' => $this->input->post('examination'),
                    'faculty' => $this->input->post('faculty'),
                );
                if (!empty($this->data['card']) && isset($this->data['card']->id)) {
                    $data['id'] = $this->data['card']->id;
                }
                $id = $this->admitcard_model->save($data);
                if ($id) {
                    if (!empty($_FILES['logo']['name'])) {
                        $config['overwrite'] = true;
                        $config['upload_path'] = $this->data['upload_path'];
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['file_name'] = "logo" . $id;
                        //Load upload library and initialize configuration
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $uploadData = $this->upload->data();
                            $logo = $uploadData['file_name'];
                        } else {
                            $logo = $this->input->post('old_logo');
                        }
                    } else {
                        $logo = $this->input->post('old_logo');
                    }

                    if (!empty($_FILES['principal_sign']['name'])) {
                        $config['overwrite'] = true;
                        $config['upload_path'] = $this->data['upload_path'];
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['file_name'] = "principal_sign" . $id;
                        //Load upload library and initialize configuration
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('principal_sign')) {
                            $uploadData = $this->upload->data();
                            $principal_sign = $uploadData['file_name'];
                        } else {
                            $principal_sign = $this->input->post('old_principal_sign');
                        }
                    } else {
                        $principal_sign = $this->input->post('old_principal_sign');
                    }

                    if (!empty($_FILES['verifier_sign']['name'])) {
                        $config['overwrite'] = true;
                        $config['upload_path'] = $this->data['upload_path'];
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';

                        $config['file_name'] = "verifier_sign" . $id;
                        //Load upload library and initialize configuration
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('verifier_sign')) {
                            $uploadData = $this->upload->data();
                            $verifier_sign = $uploadData['file_name'];
                        } else {
                            $verifier_sign = $this->input->post('old_verifier_sign');
                        }
                    } else {
                        $verifier_sign = $this->input->post('old_verifier_sign');
                    }

                    $upload_data = array('id' => $id, 'logo' => $logo, 'principal_sign' => $principal_sign, 'verifier_sign' => $verifier_sign);
                    $this->admitcard_model->save($upload_data);
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
                    redirect('admin/admitcard');
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-error text-left">Error occurred</div>');
                }
            }
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_id_card', 'can_delete')) {
            access_denied();
        }
        $this->admitcard_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('deleted_successfully').'</div>');
        redirect('admin/admitcard/index');
    }

    public function generate()
    {
        if (!$this->rbac->hasPrivilege('generate_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/admitcard/generate');
        $this->data['classes'] = $this->class_model->get();
        if ($this->input->method(true) === 'POST') {
            $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
            $this->form_validation->set_rules('admit_card', 'lang:admit_card', 'trim|required|xss_clean');
            if ($this->form_validation->run() !== FALSE) {
                $class = $this->input->post('class_id');
                $section = $this->input->post('section_id');
                $admit_card = $this->input->post('admit_card');
                $this->data['card'] = $this->admitcard_model->find($admit_card);
                $this->data['students'] = $this->student_model->searchByClassSection($class, $section);
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/admitcard/generate', $this->data);
        $this->load->view('layout/footer');
    }

    public function generatepdf()
    {
        if (!$this->rbac->hasPrivilege('generate_id_card', 'can_view') || $this->input->method(true) !== 'POST') {
            access_denied();
        }
        $std_arr = $this->input->post('data[]');
        $admin_card_id = $this->input->post('admit_card_id');
        $this->data['sch_setting'] = $this->setting_model->get();
        $this->data['admit_card'] = $this->admitcard_model->find($admin_card_id);
        $this->data['students'] = $this->student_model->getStudentsByArray($std_arr);
        $view = 'admin/admitcard/pdf';
        //$this->load->view($view, $this->data);
        $id_cards = $this->load->view($view, $this->data, true);
        $file_name = 'admit_cards-' . $this->data['admit_card']->title . '-' . date('Y-m-d');
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
        $this->m_pdf->pdf->shrink_tables_to_fit = 1;
        $this->m_pdf->pdf->WriteHTML($id_cards);
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }
}