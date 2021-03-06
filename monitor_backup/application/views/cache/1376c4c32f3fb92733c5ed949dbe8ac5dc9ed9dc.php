<?php $__env->startSection('content'); ?>
	<!-- Content -->
	<article class="container_12">
		
		<section class="grid_12">
			<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
						</div>
						<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="GA & LOGISTIC") { ?>
							<div class="float-right">
								<button type="button" onClick="window.location.href='<?=base_url()?>vehicle/add'">Add Data</button>
							</div>
						<?php } ?>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
							<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
						</p>
					</div>
				</div>	
			</div>
		</div>
		
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Vehicle Type
								</th>
								<th>
									Brands/Varian
								</th>
								<th>
									Police Number
								</th>
								<th>
									KM Status
								</th>
								<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="GA & LOGISTIC") { ?>
									<th>
										Action
									</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($datavehicle))
								foreach($datavehicle as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo strtoupper($row->jenis);?></td>
								<td><?php echo $row->type;?></td>
								<td><?php echo $row->police_number;?></td>
								<td><?php echo $row->km_status;?></td>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<td>
										<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>vehicle/edit/<?php echo $row->id;?>'" href="#" title="Edit">Edit</a></span>
										<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>vehicle/delete')" href="#" title="delete">Delete</a></span>
									</td>
								<?php } ?>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
						
					</table>
				</div>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>