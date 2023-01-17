<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login Page</title>
		
		<!-- jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- font awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <!-- Sweet Alert -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"> -->
        <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <style>
            .fa-eye{
                /* position: absolute;
                top: 28%;
                right: 4%; */
                cursor: pointer;
                color: lightgray;
            }
        </style>
    </head>
	<body>
		<div style="width: 30%; margin: auto; margin-top: 7%">
			<h1 style="margin-bottom: 5%; text-align: center">Login Page</h1><br>
			<form action="<?php echo base_url('login/login_action'); ?>" method="post">
				<!-- Email input -->
				<div class="form-outline mb-4">
					<label class="form-label" for="username">Username</label>
					<input type="text" id="username" class="form-control" name="username" />
				</div>

				<!-- Password input -->
				<div class="form-outline mb-4">
					<label class="form-label" for="password">Password</label>
					<input type="password" id="password" class="form-control" name="password" />
                    <span>Show Password : </span><i class="fa-solid fa-eye" id="eye"></i>
				</div>

				<!-- Submit button -->
				<!-- <button type="button" class="btn btn-primary btn-block mb-4" onclick="loginProcess()">Sign in</button> -->
                <input class="btn btn-primary btn-block mb-4" type="submit" value="Login">
            </form>
		</div>
	</body>
    <script src="<?= base_url() ?>asset/js/page/login.js" type="text/javascript"></script>
</html>