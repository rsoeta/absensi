<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>403 Access Denied</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<link href="<?= base_url() ?>assets/css/vendor.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/css/transparent/app.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class='pace-top'>

	<div class="app-cover"></div>
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<div id="app" class="app">
		<div class="error">
			<div class="error-code">403</div>
			<div class="error-content">
				<div class="error-message">Access Denied</div>
				<div class="error-desc mb-4">
					Full authentication is required to access this resource.
				</div>
				<div>
					<a class="btn btn-success px-3" onclick="self.history.back()"><i class="fas fa-undo"></i> Go Back</a>
				</div>
			</div>
		</div>
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
	</div>
	<script src="<?= base_url() ?>assets/js/vendor.min.js" type="6b76989f612f42d3909ff0b2-text/javascript"></script>
	<script src="<?= base_url() ?>assets/js/app.min.js" type="6b76989f612f42d3909ff0b2-text/javascript"></script>
	<script src="<?= base_url() ?>assets/js/theme/transparent.min.js" type="6b76989f612f42d3909ff0b2-text/javascript"></script>
	<script src="https://ajax.cloudflare.com/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="6b76989f612f42d3909ff0b2-|49" defer=""></script>
	<script defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"rayId":"6724e62e798a1a36","version":"2021.7.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":10}'></script>
</body>

</html>