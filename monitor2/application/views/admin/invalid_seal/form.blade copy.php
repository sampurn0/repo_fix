@extends('layouts.master2')

@section('content')
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>constellation/assets/equipment/icon.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/demo.css">
	<style type="text/css">
		form{
			margin:0;
			padding:0;
		}
		.dv-table td{
			border:0;
		}
		.dv-table input{
			border:1px solid #ccc;
		}
		.focus {
			border-color:red;
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/datagrid-detailview.js"></script>
	
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	<script type="text/javascript">
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		// $ = jq341;
	
		$(function(){
			$('#dg').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				}, 
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					
					console.log(row);
					ddv.panel({
						border:false,
						cache:true,
						// href:'show_form.php?index='+index,
						href:'<?=base_url()?>cashprocessing/show_form?index='+index+'&state='+row.state+'&row='+JSON.stringify(row)+'&id='+row.id+'&id_ct='+<?=$id?>,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
							$('#dg').datagrid('selectRow',index);
							$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
		});
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			// console.log(row);
			var url = row.isNewRecord ? '<?=base_url()?>cashprocessing/save_data' : '<?=base_url()?>cashprocessing/update_data?id='+row.id;
			console.log(url);
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					console.log(data);
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
				}, error: function(e) {
					console.log(e);
				}
			});
		}
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		function destroyItem(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this user?',function(r){
					if (r){
						var index = $('#dg').datagrid('getRowIndex',row);
						console.log(row);
						$.post('<?=base_url()?>cashprocessing/delete_data',{id:row.id},function(){
							$('#dg').datagrid('deleteRow',index);
						});
					}
				});
			}
		}
		function newItem(){
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
		}
		
		function doSearch(){
			// $('#dg').datagrid('load',{
				// sektor: $('#sektor').val()
			// });
			
			$('#dg').datagrid('load', {
				sektor: $('#sektor').val()
			});
		}
		
		function formatdecimal(num){
			if(num==undefined || num==0) {
				return '<span style="font-size: 10px">N/A</span>';
			} else {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");																	
			}
		}
	</script>
	
	<article class="container_12">
	
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Data Input Run Sheet</h1>
				<br>
				<table id="dg" class="easyui-datagrid" title="Cash Transit <?=$branch?>" style="width:100%;height:550px"
					data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>cashprocessing/get_data/<?=$id?>',method:'get'" toolbar="#toolbar" pagination="true">
					<thead>
						<tr>
							<th data-options="field:'wsid',width:50">ID</th>
							<th data-options="field:'branch',width:100">BRANCH</th>
							<th data-options="field:'runsheet',width:60">GA</th>
							<th data-options="field:'bank',width:100">BANK</th>
							<th data-options="field:'act',width:80">ACT</th>
							<th data-options="field:'brand',width:140">BRAND</th>
							<th data-options="field:'model',width:140">MODEL</th>
							<th data-options="field:'pcs_100000',width:120,align:'center',formatter:formatdecimal">100,000</th>
							<th data-options="field:'pcs_50000',width:120,align:'center',formatter:formatdecimal">50,000</th>
							<th data-options="field:'pcs_20000',width:120,align:'center',formatter:formatdecimal">20,000</th>
							<th data-options="field:'pcs_10000',width:120,align:'center',formatter:formatdecimal">10,000</th>
							<th data-options="field:'pcs_5000',width:120,align:'center',formatter:formatdecimal">5,000</th>
							<th data-options="field:'pcs_2000',width:120,align:'center',formatter:formatdecimal">2,000</th>
							<th data-options="field:'pcs_1000',width:120,align:'center',formatter:formatdecimal">1,000</th>
							<th data-options="field:'pcs_coin',width:120,align:'center',formatter:formatdecimal">COIN</th>
							<th data-options="field:'ctr',width:120,align:'center',formatter:formatdecimal">CART</th>
							<th data-options="field:'total',width:120,align:'center',formatter:formatdecimal">TOTAL</th>
						</tr>
					</thead>
				</table>
				
				<div id="toolbar">
					<a href="<?=base_url()?>cashprocessing#&tab-hcp" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
				</div>
				
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection