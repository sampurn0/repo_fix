@extends('layouts.master')

@section('content')
	<!-- Always visible control bar -->
	<!-- <div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back to list</button>
			<button type="button" onClick="openModal()">Open modal</button>
		</div>
		
		<div class="float-right">
			<button type="button" disabled="disabled">Disabled</button>
			<button type="button" class="red">Cancel</button>
			<button type="button" class="grey">Reset</button>
			<button type="button"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16"> Save</button>
		</div>
			
	</div></div> -->
	<!-- End control bar -->
	
	<!-- Content -->
	<article class="container_12">
		<section class="grid_4">
			<!--<div class="block-border"><div class="block-content">-->
				<h1>Favourites</h1>
				
				<ul class="favorites no-margin with-tip" title="Context menu available!">
					
					<li>
						<img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Info.png" width="48" height="48">
						<a href="javascript:void(0)">Settings<br>
						<small>System &gt; Settings</small></a>
						<ul class="mini-menu">
							<li><a href="javascript:void(0)" title="Move down"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Delete"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Line-Chart.png" width="48" height="48">
						<a href="javascript:void(0)">Bandwidth usage<br>
						<small>Stats &gt; Server &gt; Bandwidth usage</small></a>
						<ul class="mini-menu">
							<li><a href="javascript:void(0)" title="Move up"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Move down"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Delete"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Modify.png" width="48" height="48">
						<a href="javascript:void(0)">New post<br>
						<small>Write &gt; New post</small></a>
						<ul class="mini-menu">
							<li><a href="javascript:void(0)" title="Move up"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Move down"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Delete"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Pie-Chart.png" width="48" height="48">
						<a href="javascript:void(0)">Browsers stats<br>
						<small>Stats &gt; Sites &gt; Browsers stats</small></a>
						<ul class="mini-menu">
							<li><a href="javascript:void(0)" title="Move up"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Move down"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Delete"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Comment.png" width="48" height="48">
						<a href="javascript:void(0)">Manage comments<br>
						<small>Comments &gt; Manage comments</small></a>
						<ul class="mini-menu">
							<li><a href="javascript:void(0)" title="Move up"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="javascript:void(0)" title="Delete"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
				</ul>
				
				<form class="form" name="stats_options" id="stats_options" method="post" action="#">
					<fieldset class="grey-bg no-margin">
						<legend>Add favourite</legend>
						<p class="input-with-button">
							<label for="simple-action">Select page</label>
							<select name="simple-action" id="simple-action">
								<option value=""></option>
								<option value="1">Page 1</option>
								<option value="2">Page 2</option>
							</select>
							<button type="button">Add</button>
						</p>
					</fieldset>
				</form>
				
			<!--</div></div>-->
		</section>
		
		<section class="grid_8">
			<div class="block-border"><div class="block-content">
				<!-- We could put the menu inside a H1, but to get valid syntax we'll use a wrapper -->
				<div class="h1 with-menu">
					<h1>Web stats</h1>
					<div class="menu">
						<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16">
						<ul>
							<li class="icon_address"><a href="javascript:void(0)">Browse by</a>
								<ul>
									<li class="icon_blog"><a href="javascript:void(0)">Blog</a>
										<ul>
											<li class="icon_alarm"><a href="javascript:void(0)">Recents</a>
												<ul>
													<li class="icon_building"><a href="javascript:void(0)">Corporate blog</a></li>
													<li class="icon_newspaper"><a href="javascript:void(0)">Press blog</a></li>
												</ul>
											</li>
											<li class="icon_building"><a href="javascript:void(0)">Corporate blog</a></li>
											<li class="icon_computer"><a href="javascript:void(0)">Support blog</a></li>
											<li class="icon_search"><a href="javascript:void(0)">Search...</a></li>
										</ul>
									</li>
									<li class="icon_server"><a href="javascript:void(0)">Website</a></li>
									<li class="icon_network"><a href="javascript:void(0)">Domain</a></li>
								</ul>
							</li>
							<li class="icon_export"><a href="javascript:void(0)">Export</a>
								<ul>
									<li class="icon_doc_excel"><a href="javascript:void(0)">Excel</a></li>
									<li class="icon_doc_csv"><a href="javascript:void(0)">CSV</a></li>
									<li class="icon_doc_pdf"><a href="javascript:void(0)">PDF</a></li>
									<li class="icon_doc_image"><a href="javascript:void(0)">Image</a></li>
									<li class="icon_doc_web"><a href="javascript:void(0)">Html</a></li>
								</ul>
							</li>
							<li class="sep"></li>
							<li class="icon_refresh"><a href="javascript:void(0)">Reload</a></li>
							<li class="icon_reset">Reset</li>
							<li class="icon_search"><a href="javascript:void(0)">Search</a></li>
							<li class="sep"></li>
							<li class="icon_terminal"><a href="javascript:void(0)">Custom request</a></li>
							<li class="icon_battery"><a href="javascript:void(0)">Stats server load</a></li>
						</ul>
					</div>
				</div>
			
				<div class="block-controls">
					
					<ul class="controls-tabs js-tabs same-height with-children-tip">
						<li><a href="#tab-stats" title="Charts"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Bar-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-comments" title="Comments"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Comment.png" width="24" height="24"></a></li>
						<li><a href="#tab-medias" title="Medias"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Picture.png" width="24" height="24"></a></li>
						<li><a href="#tab-users" title="Users"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Profile.png" width="24" height="24"></a></li>
						<li><a href="#tab-infos" title="Informations"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Info.png" width="24" height="24"></a></li>
					</ul>
					
				</div>
				
				<form class="form" id="tab-stats" method="post" action="#">
					
					<fieldset class="grey-bg">
						<legend><a href="javascript:void(0)">Options</a></legend>
						
						<div class="float-left gutter-right">
							<label for="stats-period">Period</label>
							<span class="input-type-text"><input type="text" name="stats-period" id="stats-period" value=""><img src="<?=base_url()?>constellation/assets/images/icons/fugue/calendar-month.png" width="16" height="16"></span>
						</div>
						<div class="float-left gutter-right">
							<span class="label">Display</span>
							<p class="input-height grey-bg">
								<input type="checkbox" name="stats-display[]" id="stats-display-0" value="0">&nbsp;<label for="stats-display-0">Views</label>
								<input type="checkbox" name="stats-display[]" id="stats-display-1" value="1">&nbsp;<label for="stats-display-1">Unique visitors</label>
							</p>
						</div>
						<div class="float-left gutter-right">
							<span class="label">Sites</span>
							<p class="input-height grey-bg">
								<input type="radio" name="stats-sites" id="stats-sites-0" value="0">&nbsp;<label for="stats-sites-0">Group</label>
								<input type="radio" name="stats-sites" id="stats-sites-1" value="1">&nbsp;<label for="stats-sites-1">Separate</label>
							</p>
						</div>
						<div class="float-left">
							<span class="label">Mode</span>
							<select name="stats-sites" id="stats-sites-0">
								<option value="0">Bars</option>
								<option value="0">Lines</option>
							</select>
						</div>
					</fieldset>
					<div id="chart_div" style="height:330px;"></div>
					
				</form>
				
				<div id="tab-comments" class="with-margin">
					<!-- Content is loaded dynamically at bottom in the javascript section -->
				</div>
				
				<div id="tab-medias" class="with-margin">
					<p>Medias</p>
				</div>
				
				<div id="tab-users" class="with-margin">
					<p>Users</p>
				</div>
				
				<div id="tab-infos" class="with-margin">
					<p>Infos</p>
				</div>
				
				<ul class="message no-margin">
					<li>This is an <strong>information message</strong>, inside a box</li>
				</ul>
				
			</div></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection