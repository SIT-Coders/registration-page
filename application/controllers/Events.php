<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

	public function register()
	{
		$this->load->helper('url');
		$this->load->database();

		$this->db->select('eventcode, eventname');

		return $this->load->view('events/register', array("events" => $this->db->get('events')));
	}

	public function enroll()
	{
		$this->load->database();
		$this->load->library('email');

		//TO_CODE: Valiadation of Inputs

		$name = $this->input->post('name', TRUE);
		$email = $this->input->post('email', TRUE);
		$phone = $this->input->post('phone', TRUE);
		$event = $this->input->post('event', TRUE);

		$data = array('name' => $name, 'email' => $email, 'phone' => $phone, 'event' => $event, 'timestamp' => time());
		$str = $this->db->insert('users', $data);
		$userid = $this->db->insert_id();

		$this->email->from('noreply@reverb.com', 'Reverb Team');
		$this->email->to($email);
		$this->email->subject('Registration Confirmation');
		$this->email->message("Hey $name,\n\nThank you for Registering.\nYour User-ID is $event$userid.\n\nRegards,\nReverb Team.");
		$this->email->send();

		return $this->load->view('events/register_success', array("insert_id"=> $userid, "event" => $event));

	}
}
