<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Route extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("classteacher_model");
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('routes', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'route/index');
        $listroute = $this->route_model->listroute();
        $data['listroute'] = $listroute;
        $this->load->view('layout/header');
        $this->load->view('admin/route/createroute', $data);
        $this->load->view('layout/footer');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('routes', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Route';
        $this->form_validation->set_rules('route_title', 'lang:route_title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('stop[]', 'lang:stop', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount[]', 'lang:amount', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listroute = $this->route_model->listroute();
            $data['listroute'] = $listroute;
            $this->load->view('layout/header');
            $this->load->view('admin/route/createroute', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'route_title' => $this->input->post('route_title'),
            );
            $route_id = $this->route_model->add($data);
            $stops = $this->input->post('stop[]');
            $stops = array_filter($stops, function ($s) {
                return strlen($s) > 0;
            });
            $amounts = $this->input->post('amount[]');
            $stop_data = array();
            foreach ($stops as $ind => $stop) {
                if (empty($stop) || empty($amounts[$ind])) {
                    continue;
                }
                $stop_data[] = array(
                    'stop_name' => $stop,
                    'fare' => $amounts[$ind],
                    'route_id' => $route_id,
                );
            }
            $this->db->insert_batch('route_stops', $stop_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/route/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('routes', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Add Route';
        $data['id'] = $id;
        $editroute = $this->route_model->get($id);
        $data['stops'] = $this->route_model->getStops($id);
        $data['editroute'] = $editroute;
        $this->form_validation->set_rules('route_title', 'lang:route_title', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listroute = $this->route_model->listroute();
            $data['listroute'] = $listroute;
            $this->load->view('layout/header');
            $this->load->view('admin/route/editroute', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'route_title' => $this->input->post('route_title'),
            );
            $this->route_model->add($data);
            //new stops
            $stops = $this->input->post('stop[]');
            $stops = array_filter($stops, function ($s) {
                return strlen($s) > 0;
            });
            $amounts = $this->input->post('amount[]');
            $stop_data = array();
            foreach ($stops as $ind => $stop) {
                if (empty($stop)) {
                    continue;
                }
                $stop_data[] = array(
                    'stop_name' => $stop,
                    'fare' => $amounts[$ind],
                    'route_id' => $data['id'],
                );
            }
            $this->db->insert_batch('route_stops', $stop_data);
            //old stops
            $old_stops = $this->input->post('stop_edit[]');
            $old_amounts = $this->input->post('amount_edit[]');
            $old_stop_data = array();
            foreach($old_stops as $stop_id => $stop) {
                if (empty($old_stops) || empty($old_amounts[$stop_id])) {
                    continue;
                }
                $old_stop_data[] = array(
                    'id' => $stop_id,
                    'stop_name' => $stop,
                    'fare' => $old_amounts[$stop_id],
                );
            }
            $this->db->update_batch('route_stops', $old_stop_data, 'id');
            //delete stops
            $deletes = $this->input->post('stop_delete[]');
            if(!empty($deletes)) {
                $this->db->where_in('id', $deletes);
                $this->db->delete('route_stops');
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/route/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('routes', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->route_model->remove($id);
        redirect('admin/route/index');
    }

    function studenttransportdetails() {

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'admin/route/studenttransportdetails');
        $data['title'] = 'Student Hostel Details';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        //    if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        // $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        //   }
        $listroute = $this->route_model->listroute();
        $data['listroute'] = $listroute;

        $listvehicle = $this->route_model->listvehicles();
        $data['listvehicle'] = $listvehicle;


        $section_id = $this->input->post("section_id");
        $class_id = $this->input->post("class_id");
        $route_title = $this->input->post("route_title");
        $vehicle_no = $this->input->post("vehicle_no");

        //$this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');

        if (isset($_POST["search"])) {

            $details = $this->route_model->searchTransportDetails($section_id, $class_id, $route_title, $vehicle_no);
            $data["resultlist"] = $details;
        } else {

            $details = $this->route_model->studentTransportDetails($carray);
            $data["resultlist"] = $details;
        }

        $this->load->view("layout/header", $data);
        $this->load->view("admin/route/studentroutedetails", $data);
        $this->load->view("layout/footer", $data);
    }

}

?>