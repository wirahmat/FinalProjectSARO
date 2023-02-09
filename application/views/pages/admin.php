<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Admin Choropleth Mapping</title>
        <!-- jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Sweet Alert -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"> -->
        <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <!-- chart js -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>

        <!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/asset/css/page/styles.css"/>

        <!-- boostrap js-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    </head>
    <body>
        <!-- modal analysis -->
		<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
                        <h2>Summary Analysis</h2>
						<button type="button" id="close-modal-notification" class="close" data-dismiss="modal" aria-hidden="true">
							<span><b>X</b></span>
						</button>
					</div>
					<div class="modal-body detailed data" id="show_point_detail">
                        <div>
                        <!-- <div style="border-style: solid;"> -->
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <b><p style="font-size: 130%">Total Crime Reported in One Month</p></b>
                                        <div id="total-crime"></div>
                                        <br>
                                        <hr>
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 90%;">
                                <tr>
                                    <td>
                                        <b><p style="font-size: 130%">District with Highest Crime</p></b>
                                        <div id="top-crime-sub"></div>
                                    </td>
                                    <td>
                                        <b><p style="font-size: 130%">Validation Status</p></b>
                                        <div id="valid-status"></div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- <b><span id="subdistrict_name" style="color:red"></span> <p id="total_crime" style="color:red"></p></b> -->
                            <hr>
                            <i class="fa-solid fa-filter"></i>
                            <select id="month" name="month"></select>
                            <select id="year" name="year"></select>
                            <a style="text-decoration: none; cursor: pointer;" onclick="resetFilter()">Reset Filter</a>
                            <br><br>
                            <b><p style="font-size: 130%">Subdistrict Chart</p></b>
                            <canvas id='myChart'></canvas><br><br>
                            <hr>
                            <b><p style="font-size: 130%">Crime Name Chart</p></b>
                            <canvas id='myChart2'></canvas>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <div>
            <a class= "btn btn-danger" href="<?php echo base_url('login/logout'); ?>">Logout</a>
            <a class= "btn btn-info" style="color:white" data-toggle="modal" data-target="#notification">Summary</a>
            <!-- <h1>ADMIN Choropleth</h1> -->
            <!--Table-->
            <br>
            <table class="table table-striped adminTable" id="showAllData">

            </table>
            <!--Table-->
        </div>
	</body>
    <script src="<?= base_url() ?>asset/js/page/admin.js" type="text/javascript"></script>
</html>