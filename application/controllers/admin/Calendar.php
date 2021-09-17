<?php

/**
 *
 */
class Calendar extends Admin_Controller {

    private $datechooser;

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->library('customlib');
        $this->load->model("calendar_model");

        $this->load->library('pagination');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
    }

    public function events() {


        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
            access_denied();
        }

        $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"] = $event_colors;
        $config['base_url'] = base_url() . 'admin/calendar/events';
        $config['total_rows'] = $this->calendar_model->countrows();
        $config['per_page'] = 10;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config["first_link"] = "&laquo;";
        $config["first_tag_open"] = "<li>";
        $config["first_tag_close"] = "</li>";
        $config["last_link"] = "&raquo;";
        $config["last_tag_open"] = "<li>";
        $config["last_tag_close"] = "</li>";
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '<li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '<li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $userdata = $this->customlib->getUserData();
        $data["role"] = $userdata["user_type"];

        $tasklist = $this->calendar_model->getTask($config['per_page'], $this->uri->segment(4), $userdata["id"]);
        $data["tasklist"] = $tasklist;
        $data["title"] = "Event Calendar";
        $data['events'] = $this->getevents(1);
        $this->load->view("layout/header.php");
        if($this->datechooser == 'bs') {
            $this->load->view("setting/eventcalendar_bs.php", $data);
        } else {
            $this->load->view("setting/eventcalendar.php", $data);
        }
        $this->load->view("layout/footer.php");
    }

    public function addtodo() {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('task_title', 'lang:task_title', 'trim|required|xss_clean');

        $this->form_validation->set_rules('task_date', 'lang:date', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'task_title' => form_error('task_title'),
                'task_date' => form_error('task_date'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $userdata = $this->customlib->getUserData();
            $event_title = $this->input->post("task_title");
            $event_description = '';
            $event_type = 'task';
            $event_color = '#000';
            $date = $this->input->post('task_date');
            $start_date = date("Y-m-d H:i:s", strtotime($date));
            $eventid = $this->input->post("eventid");
            if (!empty($eventid)) {

                $eventdata = array('event_title' => $event_title,
                    'event_description' => $event_description,
                    'start_date' => $start_date,
                    'end_date' => $start_date,
                    'event_type' => $event_type,
                    'event_color' => $event_color,
                    'event_for' => $userdata["id"],
                    'id' => $eventid,
                );
                $msg = $this->lang->line('saved_successfully');
            } else {
                $eventdata = array('event_title' => $event_title,
                    'event_description' => $event_description,
                    'start_date' => $start_date,
                    'end_date' => $start_date,
                    'event_type' => $event_type,
                    'event_color' => $event_color,
                    'is_active' => "no",
                    'event_for' => $userdata["id"],
                );
                $msg = $this->lang->line('saved_successfully');
            }
            $d1 = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
            if ($d1) {
                try {
                    $this->bikram_sambat->setEnglishDate($d1->format('Y'), $d1->format('n'), $d1->format('j'));
                    $eventdata['start_date_bs'] = $this->bikram_sambat->toNepaliString();
                    $eventdata['end_date_bs'] = $this->bikram_sambat->toNepaliString();
                } catch (Exception $e) {
                    //
                }
            }
            $this->calendar_model->saveEvent($eventdata);
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function saveevent() {


        $this->form_validation->set_rules('title', 'lang:event_title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'title' => form_error('title'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $event_title = $this->input->post("title");
            $event_description = $this->input->post("description");
            $event_type = $this->input->post("event_type");
            $event_color = $this->input->post("eventcolor");
            $is_holiday = $this->input->post("is_holiday");
            if (empty($event_color)) {
                $event_color = '#337ab7';
            }

            $a = $this->input->post("event_dates");
            $b = explode('-', trim($a));

            $start_date = date("Y-m-d H:i:s", strtotime($b[0]));
            $end_date = date("Y-m-d H:i:s", strtotime($b[1]));
            $event_for = "";


            $userdata = $this->customlib->getUserData();
            if ($event_type == 'private') {

                $event_for = $userdata["id"];
            } else if ($event_type == 'sameforall') {

                $event_for = $userdata["role_id"];
            } else if ($event_type == 'public') {

                $event_for = "0";
            } else if ($event_type == 'protected') {

                $event_for = $userdata["role_id"];
            }
            $eventdata = array('event_title' => $event_title,
                'event_description' => $event_description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'event_type' => $event_type,
                'event_color' => $event_color,
                'is_holiday' => $is_holiday,
                'event_for' => $event_for
            );
            $d1 = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
            $d2 = DateTime::createFromFormat('Y-m-d H:i:s', $end_date);
            if ($d1 && $d2) {
                try {
                    $this->bikram_sambat->setEnglishDate($d1->format('Y'), $d1->format('n'), $d1->format('j'));
                    $eventdata['start_date_bs'] = $this->bikram_sambat->toNepaliString();
                    $this->bikram_sambat->setEnglishDate($d2->format('Y'), $d2->format('n'), $d2->format('j'));
                    $eventdata['end_date_bs'] = $this->bikram_sambat->toNepaliString();
                    $eventdata['start_time'] = $d1->format('h:i A');
                    $eventdata['end_time'] = $d2->format('h:i A');
                } catch (Exception $e) {
                    //
                }
            }

            $insert_id = $this->calendar_model->saveEvent($eventdata);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/events/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/events/' . $img_name);
                $this->calendar_model->saveEvent($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'));

        }
        echo json_encode($array);
    }

    public function saveeventbs()
    {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('title', 'lang:event_title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('event_start_date', 'lang:start_date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('event_end_date', 'lang:end_date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('event_start_time', 'lang:start_time', 'trim|required|xss_clean');
        $this->form_validation->set_rules('event_end_time', 'lang:end_time', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'title' => form_error('title'),
                'event_start_date' => form_error('event_start_date'),
                'event_end_date' => form_error('event_end_date'),
                'event_start_time' => form_error('event_start_time'),
                'event_end_time' => form_error('event_end_time'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post("eventid");
            $event_title = $this->input->post("title");
            $event_description = $this->input->post("description");
            $event_type = $this->input->post("event_type");
            $event_color = $this->input->post("eventcolor");
            $is_holiday = $this->input->post("is_holiday");
            if (empty($event_color)) {
                $event_color = '#337ab7';
            }

            $start_date_bs = $this->input->post("event_start_date");
            $end_date_bs = $this->input->post("event_end_date");
            $start_time = $this->input->post("event_start_time");
            $end_time = $this->input->post("event_end_time");
            $d1 = explode('-', $start_date_bs);
            $d2 = explode('-', $end_date_bs);

            try {
                $this->bikram_sambat->setNepaliDate($d1[0], $d1[1], $d1[2]);
                $start_date_str = $this->bikram_sambat->toEnglishString();
                $start_date_obj = DateTime::createFromFormat('Y-n-j h:i A', $start_date_str . ' ' . $start_time);
                if ($start_date_obj) {
                    $start_date = $start_date_obj->format('Y-m-d H:i:s');
                } else {
                    $start_date = $start_date_str;
                }
                $this->bikram_sambat->setNepaliDate($d2[0], $d2[1], $d2[2]);
                $end_date_str = $this->bikram_sambat->toEnglishString();
                $end_date_obj = DateTime::createFromFormat('Y-n-j h:i A', $end_date_str . ' ' . $end_time);
                if ($end_date_obj) {
                    $end_date = $end_date_obj->format('Y-m-d H:i:s');
                } else {
                    $end_date = $end_date_str;
                }
                if ($start_date_obj > $end_date_obj) {
                    $msg = array(
                        'event_start_date' => '<p>'.$this->lang->line('start_dt_gt_end_dt').'</p>',
                        'event_end_date' => '<p>'.$this->lang->line('end_dt_lt_start_dt').'</p>',
                    );
                    $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
                } else {
                    $event_for = "";


                    $userdata = $this->customlib->getUserData();
                    if ($event_type == 'private') {
                        $event_for = $userdata["id"];
                    } else if ($event_type == 'sameforall') {

                        $event_for = $userdata["role_id"];
                    } else if ($event_type == 'public') {

                        $event_for = "0";
                    } else if ($event_type == 'protected') {

                        $event_for = $userdata["role_id"];
                    }
                    $eventdata = array(
                        'id' => $id,
                        'event_title' => $event_title,
                        'event_description' => $event_description,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'start_date_bs' => $start_date_bs,
                        'end_date_bs' => $end_date_bs,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'event_type' => $event_type,
                        'event_color' => $event_color,
                        'is_holiday' => $is_holiday,
                        'event_for' => $event_for
                    );

                    $insert_id = $this->calendar_model->saveEvent($eventdata);
                    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                        $fileInfo = pathinfo($_FILES["file"]["name"]);
                        $img_name = $insert_id . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/events/" . $img_name);
                        $data_img = array('id' => $insert_id, 'image' => 'uploads/events/' . $img_name);
                        $this->calendar_model->saveEvent($data_img);
                    }
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'));
                }
            } catch(Exception $e) {
                $array = array('status' => 'fail', 'error' => array('Date out of range'), 'message' => '');
            }
        }
        echo json_encode($array);
    }

    public function updateevent() {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('title', 'lang:event_title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'title' => form_error('title'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $event_title = $this->input->post("title");
            $event_description = $this->input->post("description");
            $event_type = $this->input->post("eventtype");
            $event_color = $this->input->post("eventcolor");
            $is_holiday = $this->input->post("is_holidays");
            $id = $this->input->post("eventid");

            $event_for = "";
            $userdata = $this->customlib->getUserData();
            if ($event_type == 'private') {

                $event_for = $userdata["id"];
            } else if ($event_type == 'sameforall') {

                $event_for = $userdata["role_id"];
            } else if ($event_type == 'public') {

                $event_for = "0";
            } else if ($event_type == 'protected') {

                $event_for = $userdata["role_id"];
            }
            $a = $this->input->post("eventdates");
            $b = explode('-', trim($a));

            $start_date = date("Y-m-d H:i:s", strtotime($b[0]));
            $end_date = date("Y-m-d H:i:s", strtotime($b[1]));

            $eventdata = array('id' => $id,
                'event_title' => $event_title,
                'event_description' => $event_description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'event_type' => $event_type,
                'event_color' => $event_color,
                'is_holiday' => $is_holiday,
                'event_for' => $event_for
            );
            $d1 = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
            $d2 = DateTime::createFromFormat('Y-m-d H:i:s', $end_date);
            if ($d1 && $d2) {
                try {
                    $this->bikram_sambat->setEnglishDate($d1->format('Y'), $d1->format('n'), $d1->format('j'));
                    $eventdata['start_date_bs'] = $this->bikram_sambat->toNepaliString();
                    $this->bikram_sambat->setEnglishDate($d2->format('Y'), $d2->format('n'), $d2->format('j'));
                    $eventdata['end_date_bs'] = $this->bikram_sambat->toNepaliString();
                } catch (Exception $e) {
                    //
                }
            }

            $this->calendar_model->saveEvent($eventdata);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/events/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/events/' . $img_name);
                $this->calendar_model->saveEvent($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'));

        }
        echo json_encode($array);
    }

    public function getevents($ret = 0)
    {
        $userdata = $this->customlib->getUserData();
        $result = $this->calendar_model->getEvents();
        $eventdata = array();
        if (!empty($result)) {

            foreach ($result as $key => $value) {

                $event_type = $value["event_type"];

                if ($event_type == 'private') {

                    $event_for = $userdata["id"];
                } else if ($event_type == 'sameforall') {

                    $event_for = $userdata["role_id"];
                } else if ($event_type == 'public') {

                    $event_for = "0";
                } else if ($event_type == 'task') {

                    $event_for = $userdata["id"];
                } else if ($event_type == 'protected') {

                    $event_for = $userdata["role_id"];
                }
                if ($event_for == $value["event_for"]) {
                    $end_date = DateTime::createFromFormat("Y-m-d H:i:s", $value['end_date']);
                    $end_date->setTime(23, 59, 59);
                    $eventdata[] = array('title' => $value["event_title"],
                        'start' => $value["start_date"],
                        'end' => $end_date->format('Y-m-d H:i:s'),
                        'start_date_bs' => $value['start_date_bs'],
                        'end_date_bs' => $value['end_date_bs'],
                        'description' => $value["event_description"],
                        'id' => $value["id"],
                        'backgroundColor' => $value["event_color"],
                        'borderColor' => $value["event_color"],
                        'event_type' => $value["event_type"],
                    );
                }
            }
            if($ret == 1) {
                return $eventdata;
            }
            echo json_encode($eventdata);
        }
    }

    public function view_event($id) {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
            access_denied();
        }
        $result = $this->calendar_model->getEvents($id);
        $start_date = date("m/d/Y H:i:s", strtotime($result["start_date"]));
        $end_date = date("m/d/Y H:i:s", strtotime($result["end_date"]));
        $colorid = trim($result["event_color"], "#");
        $result["colorid"] = $colorid;
        $result["startdate"] = $start_date;
        $result["enddate"] = $end_date;


        echo json_encode($result);
    }

    public function delete_event($id) {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $result = $this->calendar_model->deleteEvent($id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('deleted_successfully'));
        } else {

            $array = array('status' => 'fail', 'error' => '', 'message' => "Cannot Delete this event.");
        }
        echo json_encode($array);
    }

    public function gettaskbyid($id) {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_edit')) {
            access_denied();
        }


        $result = $this->calendar_model->getEvents($id);

        echo json_encode($result);
    }

    public function markcomplete($id) {

        $status = $this->input->post("active");

        $eventdata = array('is_active' => $status, 'id' => $id);
        if (!empty($id)) {

            $this->calendar_model->saveEvent($eventdata);
            $array = array('status' => 'success', 'error' => '', 'message' => "Mark Completed Successfully.");
        } else {

            $array = array('status' => 'fail', 'error' => '', 'message' => "Cannot Mark Complete this event.");
        }
        echo json_encode($array);
    }

    public function download_holiday_csv()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_holidays.csv";
        $data = file_get_contents($filepath);
        $name = 'import_holidays.csv';
        force_download($name, $data);
    }

    public function import_holidays()
    {
        if (!$this->rbac->hasPrivilege('calendar_to_do_list', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('file', 'File', 'callback_handle_holidays_upload');
        if ($this->form_validation->run() == FALSE) {
            redirect('admin/calendar/events');
        } else {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    if (is_array($result) && count($result) > 0) {
                        $data = array();
                        foreach ($result as $r) {
                            $r_start_date = $r['Start Date'] ?? $r['start_date'] ?? '';
                            $r_end_date = $r['End Date'] ?? $r['end_date'] ?? '';
                            $r_title = $r['Title'] ?? $r['title'] ?? 'Holiday';
                            $r_description = $r['Details'] ?? $r['description'] ?? '';;
                            $r_color = $r['Color'] ?? $r['color'] ?? '#fb8c00';
                            $start_date_bs = '';
                            $end_date_bs = '';
                            $start_date = '';
                            $end_date = '';
                            if($this->datechooser == 'bs') {
                                $start_date_parts = explode('/', $r_start_date);
                                $end_date_parts = explode('/', $r_end_date);
                                if (count($start_date_parts) == 3) {
                                    try {
                                        $this->bikram_sambat->setNepaliDate($start_date_parts[2], $start_date_parts[1], $start_date_parts[0]);
                                        $start_date_bs = $this->bikram_sambat->toNepaliString();
                                        $start_date = $this->bikram_sambat->toEnglishString();
                                    } catch (Exception $e) {
                                        //
                                    }
                                }
                                if (count($end_date_parts) == 3) {
                                    try {
                                        $this->bikram_sambat->setNepaliDate($end_date_parts[2], $end_date_parts[1], $end_date_parts[0]);
                                        $end_date_bs = $this->bikram_sambat->toNepaliString();
                                        $end_date = $this->bikram_sambat->toEnglishString();
                                    } catch (Exception $e) {
                                        //
                                    }
                                }
                            } else {
                                $start_date = DateTime::createFromFormat("d/m/Y", $r_start_date);
                                $end_date = DateTime::createFromFormat("d/m/Y", $r_end_date);
                                if($start_date && $end_date) {
                                    $this->bikram_sambat->setEnglishDate($start_date->format('Y'), $start_date->format('n'), $start_date->format('j'));
                                    $start_date_bs = $this->bikram_sambat->toNepaliString();
                                    $this->bikram_sambat->setEnglishDate($end_date->format('Y'), $end_date->format('n'), $end_date->format('j'));
                                    $end_date_bs = $this->bikram_sambat->toNepaliString();
                                    $start_date = $start_date->format('Y-m-d');
                                    $end_date = $end_date->format('Y-m-d');
                                }
                            }

                            $data[] = array(
                                'event_title' => $r_title,
                                'event_description' => $r_description,
                                'start_date' => $start_date ? $start_date : $r_start_date,
                                'end_date' => $end_date ? $end_date : $r_end_date,
                                'start_date_bs' => $start_date_bs,
                                'end_date_bs' => $end_date_bs,
                                'event_type' => 'public',
                                'is_holiday' => '1',
                                'event_color' => !(empty($r_color)) ? $r_color : '#fb8c00',
                                'event_for' => 0,
                            );
                        }
                        if (count($data) > 0) {
                            $this->db->insert_batch('events', $data);
                        }
                    }
                }
            }
            redirect('admin/calendar/events');
        }
    }

    function handle_holidays_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_holidays_upload', $this->lang->line('student_import_csv_only'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_holidays_upload', $this->lang->line('student_import_csv_only'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_holidays_upload', $this->lang->line('student_import_csv_only'));
            return false;
        }
    }

}