<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tes extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->library('curl');
	}
	
	public function index() {
		$tgl = "2020-09-09";
		$sql = "
			SELECT C.wsid, B.jam_cash_in, B.id_cashtransit, A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			CASE 
				WHEN B.no_boc = '' AND A.keterangan != 'pengisian_awal' AND DATE(B.jam_cash_in) != '0000-00-00' THEN
					DATE(B.jam_cash_in) = '$tgl' 
				WHEN B.no_boc = '' AND A.keterangan != 'pengisian_awal' AND DATE(B.jam_cash_in) = '0000-00-00' THEN
					DATE(A.tanggal) = '$tgl'
				WHEN B.no_boc = '' AND A.keterangan = 'pengisian_awal' THEN
					DATE(B.jam_cash_in) = '0000-00-00' AND DATE(A.tanggal) = '$tgl'
				ELSE
					DATE(B.jam_cash_in) = '0000-00-00' AND DATE(A.tanggal) = '$tgl'
				END
			AND
			NOT EXISTS (
				SELECT * FROM jurnal_sync_new 
				WHERE ids = jurnal.id
			)
			#GROUP BY C.wsid
			ORDER BY A.id ASC
		";
		$sql = "
			SELECT count(*) as cnt FROM jurnal A WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.ids = A.id
			) AND
			DATE(A.tanggal) = '$tgl' ORDER BY A.id ASC
		";
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$halaman = 1;
		$page = isset($_REQUEST['halaman'])? (int)$_REQUEST["halaman"]: 1;
		$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
		$total = $count;
		$pages = ceil($total/$halaman); 
		
		for ($i=1; $i<=$pages ; $i++) {
			if($i==1) {
				echo '<a href="?halaman='.$i.'"><button style="padding: 20px">'.($pages-($i-1)).'</button></a>';
			}
		}
		
		$sql = "
			SELECT A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.id_detail = A.id_detail
			) AND
			DATE(A.tanggal) = '$tgl'
			ORDER BY A.id ASC LIMIT $mulai, $halaman
		";
		
		$sql = "
			SELECT C.wsid, A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.ids = A.id
			) AND
			DATE(A.tanggal) = '$tgl' ORDER BY A.id ASC LIMIT $mulai, $halaman
		";
		
		// echo $sql;
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
	
		// echo "<pre>";
		// print_r($sql);
		
		$debit_100 = 0;
		$debit_50 = 0;
		$debit_20 = 0;
		
		$kredit_100 = 0;
		$kredit_50 = 0;
		$kredit_20 = 0;
		
		$no = 0;
		
		if($_REQUEST["halaman"]) {
			foreach($result as $r) {
				// $debit_100 = $debit_100 + $r->debit_100;
				// $debit_50 = $debit_50 + $r->debit_50;
				// $debit_20 = $debit_20 + $r->debit_20;
				
				// $kredit_100 = $kredit_100 + $r->kredit_100;
				// $kredit_50 = $kredit_50 + $r->kredit_50;
				// $kredit_20 = $kredit_20 + $r->kredit_20;
				
				// $no++;
				$sql = "INSERT INTO `jurnal_new`
						(
						`ids`, 
						`id_detail`, 
						`tanggal`, 
						`catatan`, 
						`keterangan`, 
						`posisi`, 
						`debit_100`, 
						`debit_50`, 
						`debit_20`, 
						`kredit_100`, 
						`kredit_50`, 
						`kredit_20`
						) 
						VALUES (
						'$r->id',
						'$r->id_detail',
						'$r->tanggal',
						'$r->catatan',
						'$r->keterangan',
						'$r->posisi',
						'$r->debit_100',
						'$r->debit_50',
						'$r->debit_20',
						'$r->kredit_100',
						'$r->kredit_50',
						'$r->kredit_20'
						)";
				
				if($r->posisi!=="kredit") {
					$sqlZ = "
						SELECT C.wsid, A.* FROM jurnal A 
						LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
						LEFT JOIN client C ON C.id=B.id_bank
						WHERE 
						C.wsid='$r->wsid' 
						AND A.keterangan='replenishment' 
						AND A.id_detail='$r->id_detail' 
						ORDER BY A.id DESC
					";
					
					$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sqlZ), array(CURLOPT_BUFFERSIZE => 10)));
					
					echo "<pre>";
					echo $r->tanggal." ".$res->tanggal;
					print_r($sql);
					print_r($res);
					
					if($r->tanggal==$res->tanggal) {
						json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
						echo "<script>window.location.href='".base_url()."tes'</script>";
					} else {
						$sql = "INSERT INTO `jurnal_new`
						(
						`ids`, 
						`id_detail`, 
						`tanggal`, 
						`catatan`, 
						`keterangan`, 
						`posisi`, 
						`debit_100`, 
						`debit_50`, 
						`debit_20`, 
						`kredit_100`, 
						`kredit_50`, 
						`kredit_20`
						) 
						VALUES (
						'$r->id',
						'$r->id_detail',
						'$res->tanggal',
						'$r->catatan',
						'$r->keterangan',
						'$r->posisi',
						'$r->debit_100',
						'$r->debit_50',
						'$r->debit_20',
						'$r->kredit_100',
						'$r->kredit_50',
						'$r->kredit_20'
						)";
						
						json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
						echo "<script>window.location.href='".base_url()."tes'</script>";
					}
				} else {					
					json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
					echo "<script>window.location.href='".base_url()."tes'</script>";
				}
			}
			
		}
		
		echo "<style>table {   border-collapse: collapse; }  table, th, td {   border: 1px solid black; } th, td {   padding: 5px;   text-align: center; }</style>";
		echo "<table style='text-align: center; border: 1px solid black'>";
		echo "<tr>";
		echo "<th>NO</th>";
		echo "<th>ID</th>";
		echo "<th>WSID</th>";
		echo "<th>KETERANGAN</th>";
		echo "<th>POSISI</th>";
		echo "<th>DEBIT 100</th>";
		echo "<th>DEBIT 50</th>";
		echo "<th>DEBIT 20</th>";
		echo "<th>KREDIT 100</th>";
		echo "<th>KREDIT 50</th>";
		echo "<th>KREDIT 20</th>";
		echo "</tr>";
		foreach($result as $k => $r) {
			echo "<tr>";
			echo "<td>".($k+1)."</td>";
			echo "<td>".$r->id."</td>";
			echo "<td>".$r->wsid."</td>";
			echo "<td>".$r->keterangan."</td>";
			echo "<td>".$r->posisi."</td>";
			echo "<td>".$this->uang($r->debit_100/1000)."</td>";
			echo "<td>".$this->uang($r->debit_50/1000)."</td>";
			echo "<td>".$this->uang($r->debit_20/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_100/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_50/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_20/1000)."</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		echo "<br>debit_100 : ".$debit_100/1000;
		echo "<br>debit_50 : ".$debit_50/1000;
		echo "<br>debit_20 : ".$debit_20/1000;
		
		echo "<br>";
		echo "<br>kredit_100 : ".$kredit_100/1000;
		echo "<br>kredit_50 : ".$kredit_50/1000;
		echo "<br>kredit_20 : ".$kredit_20/1000;
		
		$total_keluar = intval($kredit_100)+intval($kredit_50)+intval($kredit_20);
		echo "<br>";
		echo "<br>".$this->uang($total_keluar/1000);
	}
	
	public function a() {
		$tgl = "2020-09-09";
		$query = "SELECT 
			count(*) as cnt
		FROM jurnal_new
		LEFT JOIN (SELECT id, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal_new.id_detail)
		LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
		WHERE 
			jurnal_new.tanggal LIKE '%".$tgl."%' AND 
			NOT EXISTS (
				SELECT * FROM jurnal_sync_new
				WHERE ids = jurnal_new.id
			)
		ORDER BY jurnal_new.tanggal, jurnal_new.id_detail, client.wsid ASC";
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$halaman = 1;
		$page = (int)$_REQUEST["halaman"];
		$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
		$total = $count;
		$pages = ceil($total/$halaman); 
		for ($i=1; $i<=$pages ; $i++) {
			if($i==1) {
				echo '<a href="?halaman='.$i.'"><button style="padding: 20px">'.($pages-($i-1)).'</button></a>';
			}
		}
		
		$sql = "
			SELECT 
			*, cashtransit_detail.id_cashtransit as id_ct, jurnal_new.id as ids, jurnal_new.keterangan as keterangan_jurnal
			FROM jurnal_new
			LEFT JOIN (SELECT id, id_cashtransit, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal_new.id_detail)
			LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
			WHERE 
				jurnal_new.tanggal LIKE '%".$tgl."%' AND 
				NOT EXISTS (
					SELECT * FROM jurnal_sync_new
					WHERE ids = jurnal_new.id
				)
			ORDER BY jurnal_new.tanggal, jurnal_new.id_detail, client.wsid ASC LIMIT $mulai, $halaman
		";
		
		// echo $sql;
		
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));	
		
		echo "<style>table {   border-collapse: collapse; }  table, th, td {   border: 1px solid black; } th, td {   padding: 5px;   text-align: center; }</style>";
		echo "<table style='text-align: center; border: 1px solid black'>";
		echo "<tr>";
		echo "<th>NO</th>";
		echo "<th>ID</th>";
		echo "<th>RUN</th>";
		echo "<th>WSID</th>";
		echo "<th>KETERANGAN</th>";
		echo "<th>POSISI</th>";
		echo "<th>DEBIT 100</th>";
		echo "<th>DEBIT 50</th>";
		echo "<th>DEBIT 20</th>";
		echo "<th>KREDIT 100</th>";
		echo "<th>KREDIT 50</th>";
		echo "<th>KREDIT 20</th>";
		echo "</tr>";
		foreach($data_record as $k => $r) {
			echo "<tr>";
			echo "<td>".($k+1)."</td>";
			echo "<td>".$r->id."</td>";
			echo "<td>".$r->id_ct."</td>";
			echo "<td>".$r->wsid."</td>";
			echo "<td>".$r->keterangan."</td>";
			echo "<td>".$r->posisi."</td>";
			echo "<td>".$this->uang($r->debit_100/1000)."</td>";
			echo "<td>".$this->uang($r->debit_50/1000)."</td>";
			echo "<td>".$this->uang($r->debit_20/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_100/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_50/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_20/1000)."</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		$no = 0;
		$prev_debit_100 = 0;
		$prev_kredit_100 = 0;
		$prev_debit_50 = 0;
		$prev_kredit_50 = 0;
		$prev_debit_20 = 0;
		$prev_kredit_20 = 0;
		$prev_saldo_100 = $this->prev_jurnal("saldo_100");
		$prev_saldo_50 = $this->prev_jurnal("saldo_50");
		$prev_saldo_20 = $this->prev_jurnal("saldo_20");
		$prev_saldo = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo_20 = 0;
		$saldo = 0;
		$prev_ket = "";
		
		if($_REQUEST["halaman"]) {
			foreach($data_record as $r) {
				if($r->kredit_100==0) {
					$saldo_100 = $prev_saldo_100 + $r->debit_100;
				} else {
					$saldo_100 = $prev_saldo_100 - $r->kredit_100;
				}
				
				if($r->kredit_50==0) {
					$saldo_50 = $prev_saldo_50  + $r->debit_50;
				} else {
					$saldo_50 = $prev_saldo_50  - $r->kredit_50;
				}
				
				if($r->kredit_20==0) {
					$saldo_20 = $prev_saldo_20 + $r->debit_20;
				} else {
					$saldo_20 = $prev_saldo_20 - $r->kredit_20;
				}
				
				$saldo = $saldo_100 + $saldo_50 + $saldo_20;
				
				
				$data_save = array();
				$data_save['ids']				= $r->ids;
				$data_save['id_detail']			= $r->id_detail;
				$data_save['wsid']				= ($r->wsid==null ? "" : $r->wsid);
				$data_save['tanggal']			= $r->tanggal;
				$data_save['catatan']			= $r->catatan;
				$data_save['keterangan']		= $r->keterangan;
				$data_save['posisi']			= $r->posisi;
				$data_save['debit_100']			= $r->debit_100;
				$data_save['debit_50']			= $r->debit_50;
				$data_save['debit_20']			= $r->debit_20;
				$data_save['kredit_100']		= $r->kredit_100;
				$data_save['kredit_50']			= $r->kredit_50;
				$data_save['kredit_20']			= $r->kredit_20;
				$data_save['saldo_100']			= ($saldo_100==0 ? 0 : $saldo_100);
				$data_save['saldo_50']			= ($saldo_50==0 ? 0 : $saldo_50);
				$data_save['saldo_20']			= ($saldo_20==0 ? 0 : $saldo_20);
				$data_save['sum_saldo']			= ($saldo==0 ? 0 : $saldo);
				
				$query = "SELECT count(*) as cnt FROM jurnal_sync_new WHERE ids='".$r->ids."'";
				$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
				
				if($count==0) {
					$this->curl->simple_get(rest_api().'/select/insert', array('table'=>'jurnal_sync_new', 'data'=>$data_save), array(CURLOPT_BUFFERSIZE => 10));
				}
				
				$prev_debit_100 = $r->debit_100;
				$prev_kredit_100 = $r->kredit_100;
				$prev_debit_50= $r->debit_50;
				$prev_kredit_50 = $r->kredit_50;
				$prev_debit_20= $r->debit_20;
				$prev_kredit_20 = $r->kredit_20;
				
				$prev_saldo_100 = $saldo_100;
				$prev_saldo_50 = $saldo_50;
				$prev_saldo_20 = $saldo_20;
				$prev_ket = $r->keterangan;
				
				$no++;
			}
			
			echo "<script>window.location.href='".base_url()."tes/a'</script>";
		}
	}
	
	public function prev_jurnal($field) {
		$query = "SELECT id, saldo_100, saldo_50, saldo_20 FROM jurnal_sync_new ORDER BY id DESC LIMIT 0,1 ";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));	
		
		$data_record = json_decode(json_encode($data_record), true);
		
		return (int) $data_record[$field];
	}
	
	function uang($u) {
		return number_format($u, 0, ',', '.');
	}
}