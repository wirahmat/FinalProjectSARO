<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Choropleth Mapping</title>

	<!-- Bootstrap CSS -->
	<!-- <link rel="stylesheet" href="<?= base_url('asset/css/bootstrap.min.css') ?>" /> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
	<!-- jQuery -->
	<!-- <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

	<!--Leaflet css-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
    integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
    crossorigin=""/>
    <!-- Leaflet js: Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>
    <!-- Geopackage plugin via unpkg -->
    <!-- <script src="https://unpkg.com/@ngageoint/geopackage@4.1.0/dist/geopackage.min.js"></script> -->
	<script src="https://unpkg.com/@ngageoint/leaflet-geopackage@2.0.5/dist/leaflet-geopackage.min.js"></script>
	
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
	
	<!-- DBSCAN Clustering -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/density-clustering@1.3.0/lib/index.js" integrity="sha256-liBiwj9IofeZdqXyeeA+NiIJZ77nlMLcPPtXhultaKI=" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/jdbscan@1.0.0/dist/jDBScan.js" integrity="sha256-b5UyTffWS71UdrwIMc/x6wHUX0F0OhtLVJMTkFu4m1U=" crossorigin="anonymous"></script> -->
	<script src="<?= base_url() ?>asset/js/jDBScan.js"></script>

	<!-- core-js for array group -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/3.26.1/minified.js"></script>

	<!-- math js -->
	<script src="https://cdn.jsdelivr.net/npm/mathjs@11.4.0/lib/browser/math.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/asset/css/page/styles.css"/>
</head>
<body>
	<script src="<?= base_url() ?>asset/js/page/report.js" type="text/javascript"></script>
	<!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><span class="menu"><i class="fa-solid fa-xmark" style="font-size: 28px;"></i></span></a>
		<a href="#" id="choropleth"><span class="menu">Choropleth</span></a>
		<!-- <a href="#" id="hotspot" onclick="getHotspotCrime()"><span class="menu">Hotspot Crime Area</span></a> -->
		<a href="#" id="hotspot"><span class="menu">Hotspot Crime Area</span></a>
        <a onclick="getLocation('route')"><span class="menu">Route</span></a>
        <a data-toggle="modal" data-target="#report" onclick="getLocation('report')"><span class="menu">Report</span></a>
		<a data-toggle="modal" data-target="#statistic"><span class="menu">Statistic</span></a>
      </div>
	<!-- <div id="sidebars" class="sidebars">
        <div class="menus">
        	<div class="bar-content"><i class="fa-solid fa-bars" id="bars"></i></div>
        </div>
    	<ul>
			<li><a href='' class='waves-effect'><i class="fa-solid fa-route"></i><span class=''>Route</span></a></li>
			<li><a href='' class='waves-effect'><i class="fa-solid fa-flag-swallowtail"></i><span class=''>Report</span></a></li>
			<li><a href='' class='waves-effect'><i class="fa-solid fa-chart-simple"></i><span class=''>Statistic</span></a></li>
		</ul>
    </div> -->
    <button class="openbtn" onclick="openNav()">☰ Open Sidebar</button>  
    <div class="modal fade" id="statistic" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="close-modal-statistic" class="close" data-dismiss="modal" aria-hidden="true">
                        <span><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Statistic</h1>
					<table>
						<thead>
							<tr>
								<th>Kecamatan</th>
								<th>Total Cases</th>
							</tr>
						</thead>
						<tbody id ="show_data">
							<!-- <tr>
								<td>Karawang Barat</td>
								<td>50 Cases</td>
							</tr> -->
					</table>
                </div>
            </div>
        </div>
	</div>

	<div class="modal fade" id="statistic_detail" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="close-modal-statistic-detail" class="close" data-dismiss="modal" aria-hidden="true">
                        <span><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="show_data_detail">
                    <!-- <h2></h2>
					<table>
						<thead>
							<tr>
								<th>Kecamatan</th>
								<th>Total Cases</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td>Karawang Barat</td>
							<td>50 Cases</td>
						</tr>
					</table> -->
                </div>
            </div>
        </div>
	</div>

	<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="close-modal-report" onclick="load_data()" class="close" data-dismiss="modal" aria-hidden="true">
                        <span><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="submit_form" action="" enctype="multipart/form-data">
						<!-- Status message -->
						<div class="statusMsg"></div>

                        <h2>Report</h1>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Type of Crime</label>
							<div id="crime-type"></div>
                            <!-- <input class="input-data" id="type_of_crime" type="text"> -->
							<!-- <span class="symbol-input"><a><i class="fa-solid fa-location-dot"></i></a></span> -->
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
						<!-- <div class="form-group">
                            <label>Photo</label>
							<input class="input-data" name="file1" type="file" id="photo" required="">
                        </div> -->
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
	<!-- Modal -->
	<div class="modal fade" id="detailed_point" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="bold_text">Detailed Crime</h5>
					<a class="close" style="box-shadow: none; font-size: 1.2em" data-dismiss="modal" aria-hidden="true"><i class="fa-solid fa-circle-xmark"></i></a>
					<!-- <button type="button" id="close-modal-point" class="close" data-dismiss="modal" aria-hidden="true">
                        <span>X</span>
                    </button> -->
				</div>
				<div class="modal-body detailed data" id="show_point_detail">
					<h5 class="bold_text" id="subdistrict">-</h5>
					<p class="bold_text" id="crime_name">-</p>
					<p><i id="descr">-</i></p>
					<p class="bold_text" id="latlong">-</p>
					<img id="image_crime" style="border-radius: 1%; box-shadow: 0 0 15px rgb(0 0 0 / 20%)" width="100%" height="auto">
				</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div> -->
			</div>
		</div>
	</div>
    <div id="map" class="page-content">
    </div>
    <script>
        function openNav() {
          document.getElementById("mySidebar").style.width = "15%";
          document.getElementById("map").style.marginLeft = "15%";
          document.getElementById("map").style.width = "85%";
        }
        
        function closeNav() {
          document.getElementById("mySidebar").style.width = "0";
          document.getElementById("map").style.marginLeft= "0";
          document.getElementById("map").style.width= "100%";
        }
    </script>
  	<!-- <script src="https://unpkg.com/@ngageoint/leaflet-geopackage@2.0.4/dist/leaflet-geopackage.min.js"></script> -->

    <script src="<?= base_url() ?>asset/js/page/index.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>asset/js/page/getStatistic.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>asset/js/page/clustering.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>asset/js/page/alerting.js" type="text/javascript"></script>


	<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<div class="container">
			<a class="navbar-brand" href="<?= base_url() ?>">Home</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('about') ?>">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('faqs') ?>">Faqs</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<header class="jumbotron jumbotron-fluid">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1 class="h1">Portal Berita Codeigniter</h1>
				</div>
			</div>
		</div>
	</header>

	<div class="container">
		<div class="row">
			<div class="col-md-12 my-2 card">
				<div class="card-body">
					<h5 class="h5">Codeigniter 4 Sudah Rilis!</h5>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam perferendis commodi tenetur quos ducimus repellat nulla, nam magni. Commodi iusto ad harum voluptas exercitationem facere eos earum laboriosam excepturi quas?</p>
				</div>
			</div>
			<div class="col-md-12 my-2 card">
				<div class="card-body">
					<h5 class="h5">Pengembangan Codeiginter 4 Tertunda</h5>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam perferendis commodi tenetur quos ducimus repellat nulla, nam magni. Commodi iusto ad harum voluptas exercitationem facere eos earum laboriosam excepturi quas?</p>
				</div>
			</div>
			<div class="col-md-12 my-2 card">
				<div class="card-body">
					<h5 class="h5">Wow, Ini 5 Startup yang Menggunakan Codeigniter</h5>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam perferendis commodi tenetur quos ducimus repellat nulla, nam magni. Commodi iusto ad harum voluptas exercitationem facere eos earum laboriosam excepturi quas?</p>
				</div>
			</div>
			<div class="col-md-12 my-2 card">
				<div class="card-body">
					<h5 class="h5">Codeigniter Ternyata Framework Terpopuler di Inodnesia</h5>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam perferendis commodi tenetur quos ducimus repellat nulla, nam magni. Commodi iusto ad harum voluptas exercitationem facere eos earum laboriosam excepturi quas?</p>
				</div>
			</div>
		</div>
	</div>

	<footer class="jumbotron jumbotron-fluid mt-5 mb-0">
		<div class="container text-center">Copyright &copy <?= Date('Y') ?> CI News</div>
	</footer> -->

	<!-- Jquery dan Bootsrap JS -->
	<!-- <script src="<?= base_url('js/jquery.min.js') ?>"></script> -->
	<!-- <script src="<?= base_url('js/bootstrap.min.js') ?>"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->
	<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>