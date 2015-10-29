<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function sendWeeklyMail()
	{
		$this->load->database();
		
		$this->load->library('email');
		$config['mailtype'] = 'text';
		$config['priority'] = '5'; //Email Priority. 1 = highest. 5 = lowest. 3 = normal.
		$this->email->initialize($config);

		$query = $this->db->query("SELECT * FROM `users` WHERE `event` IN (SELECT `eventcode` FROM `events` WHERE `events`.`date` > ?) AND email_sent_at < ?", array(time(), mktime(0,0,0) - 7*24*60*60 - 10));
		foreach ($query->result() as $row)
		{
		    $this->email->from('noreply@reverb.com', 'Reverb Team');
			$this->email->to($row->email);

			$this->email->subject('Reverb');
			$this->email->message($this->load->view('emails/weekly_' . strtolower($row->event), array("name" => $row->name, "userID" => $row->id), TRUE));

			if($this->email->send()){
				$this->db->set('email_sent_at', time());
				$this->db->where('id', $row->id);
				$this->db->update('users');
				echo "sent to " . $row->id;
			}
			
		}

	}

	public function sendReminderMail () 
	{
		$daysBeforeEmail = 3; // Number of days before the event, this email should be sent.
		$this->load->database();
		
		$this->load->library('email');
		$config['mailtype'] = 'text';
		$config['priority'] = '3'; //Email Priority. 1 = highest. 5 = lowest. 3 = normal.
		$this->email->initialize($config);

		$query = $this->db->query("SELECT * FROM `users` WHERE `event` IN (SELECT `eventcode` FROM `events` WHERE `date` - ? <= ?) ", array(time(), $daysBeforeEmail * 24 * 60 *60 ));

		foreach ($query->result() as $row)
		{
		    $this->email->from('noreply@reverb.com', 'Reverb Team');
			$this->email->to($row->email);

			$this->email->subject('Reverb');
			$this->email->message($this->load->view('emails/reminder_' . strtolower($row->event), array("name" => $row->name, "userID" => $row->id), TRUE));

			if($this->email->send()){
				$this->db->set('email_sent_at', time());
				$this->db->where('id', $row->id);
				$this->db->update('users');
				echo "sent to " . $row->id;
			}
			
		}
	}
}
