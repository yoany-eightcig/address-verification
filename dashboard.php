<!DOCTYPE html>
<html>
<head>
	<title>Eightcig - Address Verificaction</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>	

	<!-- Bootstrap Form Helpers -->
	<link href="/css/bootstrap-formhelpers.min.css" rel="stylesheet" media="screen">
	<script src="/js/bootstrap-formhelpers.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/styles.css">


</head>
<body class="bg-dark">

	<div class="modal fade"  id="responseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title">Address Validation</h5>
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          				<span aria-hidden="true">&times;</span>
	        			</button>
	      		</div>
	      		<div class="modal-body" id="modal-body">
	        		<div id="messages" class="container_messages"></div>
	      		</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="emptyModal()">Close</button>
	      		</div>
	    	</div>
	  	</div>
	</div>

	<div class="container">
		<div class="text-white clearfix mb-3 mt-3">
			<div class="float-left mr-3">
				<img height="120px" src="/images/eightcig-logo-red.png">
			</div>
			<div class="float-left logo_info">
				<h4 class="text-white">EightCig</h4>
				<p>3010 E Alexander Rd</p>
				<p>Ste #1002</p>
				<p>North Las Vegas, NV 89030</p>
				<p><a href="tel:702-415-5263">(702) 415-5263</a></p>
				<p>info@eightcig.com</p>
			</div>
		</div>
		<div class="row row-styled">
			<div class="col-md-6">
				<h3>Address</h3>
				<label for="fullname"><i class="fa fa-user"></i> Full Name</label>
				<input type="text" id="fullname" name="firstname" placeholder="John M. Doe">
				<label for="email"><i class="fa fa-envelope"></i> Email</label>
				<input type="text" id="email" name="email" placeholder="john@example.com">
				<label for="address_1"><i class="fa fa-address-card-o"></i> Address 1</label>
				<input type="text" id="address_1" name="address_1" placeholder="Street address / P.O. box / c/o">
				<label for="address_2"><i class="fa fa-address-card-o"></i> Address 2 (Optional)</label>
				<input type="text" id="address_2" name="address_2" placeholder="Apartment / Suite / Unit / Building / Floor">
				<label for="city_locality"><i class="fa fa-institution"></i> City</label>
				<input type="text" id="city_locality" name="city_locality" placeholder="New York">

				<form>
					<div class="row">
				  		<div class="col-md-12">
							<label for="state_province">State</label>
							<select name="state_province" id="state_province" class="bfh-states" data-country="country_code"></select>
				  		</div>
				  		<div class="col-md-12">
				    		<label for="country_code">Country</label>
				    		<select name="country_code" id="country_code"  class="bfh-countries" data-country="US"></select>
				  		</div>
					  	<div class="col-md-12">
					    	<label for="postal_code">Zip</label>
					    	<input name="postal_code" type="text" id="postal_code"  placeholder="10001">
					  	</div>
					</div>
				</form>
				<button type="button" class="btn btn-success" onclick="validateAddress()">Check</button>
			</div>
			<div class="col-md-6">
				<h3>Bypass</h3>
				<div>
					<label>List of Address</label>
					<select class="form-control" size="10">
						<?php 
							$csv = array_map('str_getcsv', file('bypass.csv'));
							foreach ($csv as $value) {
								echo "<option>".$value[0].": ".$value[1]." </option>";
							}
						?>
					</select>
				</div>
				<button type="button" class="btn btn-primary" onclick="addToBypass()">Add</button>
			</div>
		</div>
	</div>
<script>
function emptyModal() {
	$( "#messages" ).html('');
}

function addToBypass() {
	let data = {
		email: $("#email").val(),
		address_line1: $("#address_1").val(),
	};

	$.ajax({
		url: '/addbypass.php',
		data: data,
		success: function(response){
			let data = JSON.parse(response);
			if (data.status == true) {
				alert("Bypass was successfully created.");
				window.location.reload(true);
			}
		},
		error: function(e) {
			console.error(e);
		}
	})
}

function validateAddress() {
	$( "#messages" ).html('');
	let url = "/";
	let data = {
	    	name: $("#fullname").val(),
	    	email: $("#email").val(),
	    	address_line1: $("#address_1").val(),
	    	address_line2: $("#address_2").val(),
	    	city_locality: $("#city_locality").val(),
	    	state_province: $("#state_province").val(),
	    	country_code: $("#country_code").val(),
	    	postal_code: $("#postal_code").val(),
	    };

	$.ajax ({
	    url: url,
	    data: data,
	    success: function (response) {
    	 	$("#responseModal").modal("show");

	    	console.log(response);
          	let data = JSON.parse(response);
          	console.log(data);

	        if (data.status == "verified") {
	            $( "#messages" ).append( "<h2>Info</h2>" );
	            
	            if (data.messages.length > 0 ) {
	              	for (i = 0; i < data.messages.length; i++) {
	                	console.log(data.messages[i]);
                		$( "#messages" ).append( "<p class='message_error'>["+data.messages[i].type+"] "+data.messages[i].message+"</p>" );
	              	}
	            } else {
	              	$( "#messages" ).addClass("message_success");
	              	$( "#messages" ).append( "<p>Your address was verified successfully.</p>" );
	              	$(".step__footer__continue-btn").removeClass("hidden_button");
	              	$(".address-verify").addClass("og_btn_success");
	            }
	         }
          
	        if (data.status == "error") {
	            $( "#messages" ).addClass("message_error");
	            $( "#messages" ).append( "<h2>Error</h2>" );
	            for (i = 0; i < data.messages.length; i++) {
	              	console.log(data.messages[i]);
	              	$( "#messages" ).append( "<p>"+data.messages[i].message+"</p>" );
	            }
	        }

	        if (data.status == "unverified") {
	            $( "#messages" ).addClass("message_error");
	            $( "#messages" ).append( "<h2>Error</h2>" );
	            for (i = 0; i < data.messages.length; i++) {
	              	console.log(data.messages[i]);
	              	$( "#messages" ).append( "<p>"+data.messages[i].message+"</p>" );
	            }
	        }

	    },
	    error: function(e) {
	        console.error(e);
	    }
	 });	
}

$(document).ready(function(){
	
})

</script>
</body>
</html>