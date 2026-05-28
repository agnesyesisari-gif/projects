<?php

class Dashboard extends CI_Controller
{
	public function index()
	{
		$this->load->model('jadwal ibadah_model');
		$this->load->model('program kerja_model');
		
		$data = [
			"article_count" => $this->jadwal ibadah_model->count(),
			"feedback_count" => $this->proram kerja_model->count()
		];

		$this->load->view('user/dashboard.php', $data);
	}
}