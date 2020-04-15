<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_problem extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');	

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;
		} else {
            redirect('');
        }
    }
	
    public function index() {    
		$this->data['active_menu'] = "all_problem";
		
		$array = array(
			array(
				"nama" => "tes1",
				"time" => date("Y-m-d")." 17:56:00",
			),
			array(
				"nama" => "tes2",
				"time" => date("Y-m-d")." 17:00:00",
			),
			array(
				"nama" => "tes3",
				"time" => date("Y-m-d")." 16:00:00",
			)
		);
		
		$sql_statusflm = "
			SELECT 
				*, 
				flm_trouble_ticket.status as status_ticket,
				flm_trouble_ticket.id as id,
				id_ticket,
				bank,
				data_solve,
				entry_date,
				accept_time,
				end_apply,
				problem_type
			FROM flm_trouble_ticket 
			LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id) 
			WHERE id_ticket NOT IN (SELECT id_ticket FROM slm_trouble_ticket)";
        $row_statusflm = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql_statusflm), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		
		$list = array();
		$i = 0;
		foreach($row_statusflm as $r) {
			
			$ary = array();
			foreach(json_decode($r->problem_type) as $arr) {
				// $ary[] = $db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
				$ary[] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>'
					SELECT nama_sub_kategori FROM sub_kategori WHERE id_sub_kategori="'.$arr.'"
				'), array(CURLOPT_BUFFERSIZE => 10)))->nama_sub_kategori;
			}
			
			$list[$i]['id'] = $r->id;
			$list[$i]['id_ticket'] = $r->id_ticket;
			$list[$i]['status_ticket'] = $r->status_ticket;
			$list[$i]['bank'] = $r->bank;
			$list[$i]['data_solve'] = $r->data_solve;
			$list[$i]['entry_date'] = $r->entry_date;
			$list[$i]['selisih1'] = $this->diff($r->entry_date, $r->accept_time);
			$list[$i]['accept_time'] = $r->accept_time;
			$list[$i]['selisih2'] = $this->diff($r->accept_time, $r->end_apply);
			$list[$i]['end_apply'] = $r->end_apply;
			$list[$i]['problem'] = implode(", ",$ary);
			$i++;
		}
		
		// echo "<pre>";
		// print_r($row_statusflm);
		
		$this->data['demo'] = $list;
		
		return view('admin/all_problem/index', $this->data);
    }
	
	function diff($a1, $a2) {
        $awal  = date_create($a1);
        $akhir = date_create($a2); // waktu sekarang
        $diff  = date_diff($awal, $akhir);

        // return $diff;
        return sprintf('%02d', $diff->h).':'.sprintf('%02d', $diff->i).':'.sprintf('%02d', $diff->s);
    }
	
	function check_data() {
		$sql = "
			SELECT 
				COUNT(*) as cnt
			FROM flm_trouble_ticket 
			WHERE updated='true'";
        $row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		echo $row;
	}
	
	function update_status() {
		$sql = "UPDATE `flm_trouble_ticket` SET `updated`='false' WHERE 1";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		if ($res) {
			echo "failed";
		} else {
			echo "success";
		}
	}
	
	function show_table() {
		$date = date("Y-m-d");
		$date = "";
		$sql_statusflm = "
			SELECT 
				*, 
				flm_trouble_ticket.status as status_ticket,
				flm_trouble_ticket.id as id,
				id_ticket,
				bank,
				data_solve,
				entry_date,
				accept_time,
				end_apply,
				problem_type,
				(SELECT id_karyawan FROM karyawan LEFT JOIN teknisi ON(teknisi.nik=karyawan.nik) WHERE teknisi.id_teknisi=flm_trouble_ticket.teknisi_1) as id_karyawan,
				(SELECT nama FROM karyawan LEFT JOIN teknisi ON(teknisi.nik=karyawan.nik) WHERE teknisi.id_teknisi=flm_trouble_ticket.teknisi_1) as custody
			FROM flm_trouble_ticket 
			LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id) 
			WHERE 
				id_ticket NOT IN (SELECT id_ticket FROM slm_trouble_ticket)
				AND entry_date LIKE '%".$date."%'";
        $row_statusflm = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql_statusflm), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($row_statusflm);
		
		$list = array();
		$i = 0;
		foreach($row_statusflm as $r) {
			
			$list[$i]['id'] = $r->id;
			$list[$i]['id_ticket'] = $r->id_ticket;
			$list[$i]['status_ticket'] = $r->status_ticket;
			$list[$i]['bank'] = $r->bank;
			$list[$i]['data_solve'] = $r->data_solve;
			$list[$i]['entry_date'] = $r->entry_date;
			$list[$i]['selisih1'] = $this->diff($r->entry_date, $r->accept_time);
			$list[$i]['accept_time'] = $r->accept_time;
			$list[$i]['selisih2'] = $this->diff($r->accept_time, $r->end_apply);
			$list[$i]['end_apply'] = $r->end_apply;
			$list[$i]['problem'] = $r->problem_type;
			$list[$i]['wsid'] = $r->wsid;
			$list[$i]['team'] = "(".$r->id_karyawan.") ".$r->custody;
			$i++;
		}
		
		
		$table = '';
		$table_ctnt = '';
		$table_status = '';
		
		$no = 1; 
		foreach ($list as $d) {
			if($d['accept_time']=="" && $d['data_solve']=="") {
				$table_status = '<span class="badge_style b_pending">Waiting PIC</span>';
			} else if($d['accept_time']!=="" && $d['data_solve']=="") {
				$table_status = '<span class="badge_style b_medium">Job Accepted</span>';
			}  else if($d['accept_time']!=="" && $d['data_solve']!=="") {
				if($d['status_ticket']=="CLOSED") {
					$table_status = '<span class="badge_style b_done">Job Done</span><a href="'.base_url().'dashboard/detail/'.$d['id_ticket'].'"><span class="badge_style b_done">Detail</span></a>';
				} else if($d['status_ticket']=="PENDING") {
					$table_status = '<span class="badge_style b_away">Job PENDING</span>';
				} else if($d['status_ticket']=="SLM") {
					$table_status = '<span class="badge_style b_suspend">Refer to SLM</span>';
				}
			}
			
			$table_ctnt .= '
				<tr>
					<td>'.$no.'</td>
					<td>
						<table>
							<tr>
								<td>Ticket</td>
								<td> : </td>
								<td>'.$d['id_ticket'].'</td>
							</tr>
							<tr>
								<td>Team</td>
								<td> : </td>
								<td>'.$d['team'].'</td>
							</tr>
							<tr>
								<td>Bank</td>
								<td> : </td>
								<td>('.$d['wsid'].') '.$d['bank'].'</td>
							</tr>
							<tr>
								<td>Problem</td>
								<td> : </td>
								<td>'.implode(",<br>", explode(", ", $d['problem'])).'</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>Entry Date</td>
								<td> : </td>
								<td>'.date("d-m-Y", strtotime($d['entry_date'])).'</td>
							</tr>
							<tr>
								<td>Entry Time</td>
								<td> : </td>
								<td>'.date("H:i:s", strtotime($d['entry_date'])).'</td>
							</tr>
							<tr>
								<td>Response</td>
								<td> : </td>
								<td><span id="demo1'.$d['id'].'">'.$d['selisih1'].'</span></td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>Accepted Date</td>
								<td> : </td>
								<td>'.($d['accept_time']=="" ? "-" : date("d-m-Y", strtotime($d['accept_time']))).'</td>
							</tr>
							<tr>
								<td>Accepted Time</td>
								<td> : </td>
								<td>'.($d['accept_time']=="" ? "-" : date("H:i:s", strtotime($d['accept_time']))).'</td>
							</tr>
							<tr>
								<td>Resolution</td>
								<td> : </td>
								<td><span id="demo2'.$d['id'].'">'.($d['accept_time']=="" ? "-" : $d['selisih2']).'</span></td>
							</tr>
						</table>
					</td>
					<td>'.$table_status.'</td>
				</tr>
			';
			
			$no++; 
		}
		
		$table_arry = '';
		foreach ($list as $d) { 
			$table_arry .= '
				{
					id: "'.$d['id'].'",
					entry_date: "'.$d['entry_date'].'",
					distance1: new Date("'.date('M j, Y H:i:s', strtotime($d['entry_date'])).'").getTime(),
					accept_time: "'.$d['accept_time'].'",
					distance2: new Date("'.date('M j, Y H:i:s', strtotime($d['accept_time'])).'").getTime(),
					end_apply: "'.$d['end_apply'].'"
				},
			';
		} 
		
		$table .= '
			<div>
				<div class="view" style="margin-bottom: 20px">
					<div class="wrapper" style="margin-top: 0px">
						<table class="table" style="width: 100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Description</th>
									<th>Response Time FLM</th>
									<th>Resolution Time FLM</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								'.$table_ctnt.'
							</tbody>
						</table>
					</div>
				</div>
				
				<script>
					var countdowns = [
						'.$table_arry.'
					];
					
					var timer = setInterval(function() {
						// Get todays date and time
						var now = Date.now();

						var index = countdowns.length - 1;

						// we have to loop backwards since we will be removing
						// countdowns when they are finished
						while(index >= 0) {
							var countdown = countdowns[index];
							
							// console.log(countdown.accept_time);

							// Find the distance between now and the count down date
							
							if(countdown.accept_time=="") {
								var distance1 = now - countdown.distance1;
								// Time calculations for days, hours, minutes and seconds
								var days1 = Math.floor(distance1 / (1000 * 60 * 60 * 24));
								var hours1 = Math.floor((distance1 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								var minutes1 = Math.floor((distance1 % (1000 * 60 * 60)) / (1000 * 60));
								var seconds1 = Math.floor((distance1 % (1000 * 60)) / 1000);

								var timerElement1 = document.getElementById("demo1" + countdown.id);
								// If the count down is over, write some text 
								if (distance1 < 0) {
									timerElement1.innerHTML = "EXPIRED";
									// this timer is done, remove it
									countdowns.splice(index, 1);
								} else {
									timerElement1.innerHTML =  days1 + "d " + hours1 + "h " + minutes1 + "m " + seconds1 + "s "; 
								}
							}
							
							if(countdown.end_apply=="" && countdown.accept_time!="") {
								var distance2 = now - countdown.distance2;
								// Time calculations for days, hours, minutes and seconds
								var days2 = Math.floor(distance2 / (1000 * 60 * 60 * 24));
								var hours2 = Math.floor((distance2 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								var minutes2 = Math.floor((distance2 % (1000 * 60 * 60)) / (1000 * 60));
								var seconds2 = Math.floor((distance2 % (1000 * 60)) / 1000);
								
								var timerElement2 = document.getElementById("demo2" + countdown.id);
								// If the count down is over, write some text 
								if (distance2 < 0) {
									timerElement2.innerHTML = "EXPIRED";
									// this timer is done, remove it
									countdowns.splice(index, 1);
								} else {
									timerElement2.innerHTML =  days2 + "d " + hours2 + "h " + minutes2 + "m " + seconds2 + "s "; 
								}
							}

							index -= 1;
						}

						// if all countdowns have finished, stop timer
						if (countdowns.length < 1) {
							clearInterval(timer);
						}
					}, 1000);
				</script>
			</div>
		';
		
		echo $table;
	}
}