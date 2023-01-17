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

        <!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/asset/css/page/styles.css"/>
    </head>
    <body>
    <a class= "btn btn-danger" href="<?php echo base_url('login/logout'); ?>">Logout</a>
        <!-- <h1>ADMIN Choropleth</h1> -->
        <!--Table-->
        <br>
        <table class="table table-striped adminTable" id="showAllData">

            <!--Table head-->
            <!-- <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Country</th>
                <th>City</th>
                <th>Position</th>
                <th>Age</th>
                <th>Action</th>
            </tr>
            </thead> -->
            <!--Table head-->

            <!--Table body-->
            <!-- <tbody>
            <tr class="table-info">
                <th scope="row">1</th>
                <td>Kate</td>
                <td>Moss</td>
                <td>USA</td>
                <td>New York City</td>
                <td>Web Designer</td>
                <td>23</td>
                <td><button type="button" class="btn btn-info btn-sm">Info</button><td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Anna</td>
                <td>Wintour</td>
                <td>United Kingdom</td>
                <td>London</td>
                <td>Frontend Developer</td>
                <td>36</td>
                <td><button type="button" class="btn btn-info btn-sm">Info</button><td>
            </tr>
            <tr class="table-info">
                <th scope="row">3</th>
                <td>Tom</td>
                <td>Bond</td>
                <td>Spain</td>
                <td>Madrid</td>
                <td>Photographer</td>
                <td>25</td>
                <td><button type="button" class="btn btn-info btn-sm">Info</button><td>
            </tr>
            <tr>
                <th scope="row">4</th>
                <td>Jerry</td>
                <td>Horwitz</td>
                <td>Italy</td>
                <td>Bari</td>
                <td>Editor-in-chief</td>
                <td>41</td>
                <td><button type="button" class="btn btn-info btn-sm">Info</button><td>
            </tr>
            </tbody> -->
            <!--Table body-->


        </table>
        <!--Table-->
	</body>
    <script src="<?= base_url() ?>asset/js/page/admin.js" type="text/javascript"></script>
</html>