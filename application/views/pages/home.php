<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Choropleth Mapping</title>

		<!-- jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		
		<!-- Sweet Alert -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		
		<!--Leaflet-->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
				
		<!-- Locate Position -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.78.0/dist/L.Control.Locate.min.css">
		<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.78.0/src/L.Control.Locate.min.js" charset="utf-8"></script>
		
		<!-- Routing System-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>

		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>

		<!-- Geocoder-->
		<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
		<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

		<!-- EasyButton -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
		<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
		
		<!-- DBSCAN Clustering -->
		<script src="<?= base_url() ?>asset/js/jDBScan.js"></script>

		<!-- core-js for array group -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/3.26.1/minified.js"></script>

		<!-- math js -->
		<script src="https://cdn.jsdelivr.net/npm/mathjs@11.4.0/lib/browser/math.min.js"></script>

		<!-- chart js -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>

		<!-- timezone js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.7/jstz.min.js" integrity="sha512-pZ0i46J1zsMwPd2NQZ4IaL427jXE2RVHMk3uv/wPTNlBVp9AbB1L65/4YdrXRPLEmyZCkY9qYOOsQp44V4orHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/asset/css/page/styles.css"/>
	</head>
	<body>
		<!-- Sidebar -->
		<div id="mySidebar" class="sidebar">
			<a href="javascript:void(0)" class="closebtn menu-btn" onclick="closeNav()"><span class="menu"><i class="fa-solid fa-xmark" style="font-size: 28px;"></i></span></a>
			<a class="menu-sidebar" onclick="getLocation('route'); closeNav();" class="menu-btn"><span class="menu"><i class="fa-solid fa-route"></i> Route</span></a>
			<a class="menu-sidebar" data-toggle="modal" data-target="#report" onclick="getLocation('report'); closeNav();" class="menu-btn"><span class="menu"><i class="fa-solid fa-flag"></i> Report</span></a>
			<!-- Default dropright button -->
			<div>
				<!-- <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Statistic
				</button> -->
				<a id="statistic-button" class="menu-sidebar menu-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="menu"><i class="fa-solid fa-chart-simple"></i> Statistic</span></a>
				<div class="dropdown-menu">
					<!-- Dropdown menu links -->
					<a id="statistic-button" class="drop-menu-btn" data-toggle="modal" data-target="#statistic" onclick="load_data(); closeNav();">- per Subdistrict</a>
					<a id="statistic-button" class="drop-menu-btn" data-toggle="modal" data-target="#statistic" onclick="load_data_crime_name(); closeNav();">- per Crime Name</a>
				</div>
			</div>
		</div>
		
		<!-- modal Statistic per Subdistrict -->
		<div class="modal fade" id="statistic" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="close-modal-statistic" class="close" data-dismiss="modal" aria-hidden="true">
							<span><i class="fa-solid fa-xmark"></i></span>
						</button>
					</div>
					<div class="modal-body" id ='show_data'>
						
					</div>
				</div>
			</div>
		</div>

		<!-- modal statistic in one subdistrict -->
		<div class="modal fade" id="statistic_detail" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="close-modal-statistic-detail" class="close" data-dismiss="modal" aria-hidden="true">
							<span><i class="fa-solid fa-xmark"></i></span>
						</button>
					</div>
					<div class="modal-body" id="show_data_detail">
					</div>
				</div>
			</div>
		</div>

		<!-- modal Report Crime -->
		<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="close-modal-report" class="close" data-dismiss="modal" aria-hidden="true">
							<span><i class="fa-solid fa-xmark"></i></span>
						</button>
					</div>
					<div class="modal-body">
						<form id="submit_form" action="" enctype="multipart/form-data">
							<h2>Report</h1>
							<div class="form-group">
								<label for="exampleInputEmail1">Type of Crime</label>
								<div id="crime-type"></div>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Location</label>
								<input class="input-data" id="location" type="text">
								<label for="exampleInputEmail1">Accuracy</label> <input id="accuracy" type="text" disabled> 
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Description</label>
								<textarea rows="4" name="description" class="form-control" id="description"></textarea>
							</div>
							<div class="control-group form-group">
								<div class="controls">
									<label>Photo</label>
									<input class="input-data" name="file1" type="file" id="file1" required="">
									<p class="help-block"></p>
								</div>
							</div>
							<div class="modal-footer ">
								<button type="button" class="add" onclick="submitReport()">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- modal for detailed crime in one point -->
		<div class="modal fade" id="detailed_point" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="bold_text">Detailed Crime</h5>
						<a class="close" style="box-shadow: none; font-size: 1.2em" data-dismiss="modal" aria-hidden="true"><i class="fa-solid fa-circle-xmark"></i></a>
					</div>
					<div class="modal-body detailed data" id="show_point_detail">
						<h5 class="bold_text" id="subdistrict">-</h5>
						<p class="bold_text" id="crime_name">-</p>
						<p><i id="descr">-</i></p>
						<p class="bold_text" id="latlong">-</p>
						<img id="image_crime" style="border-radius: 1%; box-shadow: 0 0 15px rgb(0 0 0 / 20%)" width="100%" height="auto">
					</div>
				</div>
			</div>
		</div>

		<!-- modal filter
		<div class="modal fade" id="filterbydate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="bold_text">Filter the Data</h6>
						<a class="close" style="box-shadow: none; font-size: 1.2em" data-dismiss="modal" aria-hidden="true"><i class="fa-solid fa-circle-xmark"></i></a>
					</div>
					<div class="modal-body">
						
					</div>
				</div>
			</div>
		</div> -->

		<!-- leaflet basemap -->
		<div id="map" class="page-content"></div>

		<script>
			function openNav() {
				let width = screen.width;
				if (width > 900){
					document.getElementById("mySidebar").style.width = "15%";
					document.getElementById("map").style.marginLeft = "15%";
					document.getElementById("map").style.width = "85%";
				}
				else if (width < 450){
					document.getElementById("mySidebar").style.width = "40%";
					document.getElementById("map").style.marginLeft = "40%";
					document.getElementById("map").style.width = "60%";
				}
				$('#filter').popover('hide');
			}
			
			function closeNav() {
				document.getElementById("mySidebar").style.width = "0";
				document.getElementById("map").style.marginLeft= "0";
				document.getElementById("map").style.width= "100%";
			}
		</script>

		<!-- local javascript -->
		<script src="<?= base_url() ?>asset/js/page/index.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/getStatistic.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/clustering.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/alerting.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/responsive.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/report.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/timeFilter.js" type="text/javascript"></script>
		<script src="<?= base_url() ?>asset/js/page/routing.js" type="text/javascript"></script>
		
		<!-- popper js -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<!-- boostrap js-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>
</html>