<html>
<head>
	<style>
		body {
			width: 100%;
			background-color: #ffffff;
			margin: 0;
			padding: 0;
			-webkit-font-smoothing: antialiased;
			font-family: Georgia, Times, serif
		}

		table {
			border-collapse: collapse;
		}

		td#logo {
			margin: 0 auto;
			padding: 14px 0;
		}

		img {
			border: none;
			display: block;
		}

		a.blue-btn {
			display: inline-block;
			margin-bottom: 34px;
			border: 3px solid #3baaff;
			padding: 11px 38px;
			font-size: 12px;
			font-family: arial;
			font-weight: bold;
			color: #3baaff;
			text-decoration: none;
			text-align: center;
		}

		a.blue-btn:hover {
			background-color: #3baaff;
			color: #fff;
		}

		a.white-btn {
			display: inline-block;
			margin-bottom: 30px;
			border: 3px solid #fff;
			background: transparent;
			padding: 11px 38px;
			font-size: 12px;
			font-family: arial;
			font-weight: bold;
			color: #fff;
			text-decoration: none;
			text-align: center;
		}

		a.white-btn:hover {
			background-color: #fff;
			color: #3baaff;
		}

		.border-complete {
			border-top: 1px solid #dadada;
			border-left: 1px solid #dadada;
			border-right: 1px solid #dadada;
		}

		.border-lr {
			border-left: 1px solid #dadada;
			border-right: 1px solid #dadada;
		}

		#banner-txt {
			color: #fff;
			padding: 15px 32px 0px 32px;
			font-family: arial;
			font-size: 13px;
			text-align: center;
		}

		h2#our-products {
			font-family: \'Pacifico\';
			margin: 23px auto 5px auto;
			font-size: 27px;
			color: #3baaff;
		}

		h3.our-products {
			font-family: arial;
			font-size: 15px;
			color: #7c7b7b;
		}

		p.our-products {
			text-align: center;
			font-family: arial;
			color: #7c7b7b;
			font-size: 12px;
			padding: 10px 10px 24px 10px;
		}

		h2.special {
			margin: 0;
			color: #fff;
			color: #fff;
			font-family: \'Pacifico\';
			padding: 15px 32px 0px 32px;
		}

		p.special {
			color: #fff;
			font-size: 12px;
			color: #fff;
			text-align: center;
			font-family: arial;
			padding: 0px 32px 10px 32px;
		}

		h2#coupons {
			color: #3baaff;
			text-align: center;
			font-family: \'Pacifico\';
			margin-top: 30px;
		}

		p#coupons {
			color: #7c7b7b;
			text-align: center;
			font-size: 12px;
			text-align: center;
			font-family: arial;
			padding: 0 32px;
		}

		#socials {
			padding-top: 12px;
		}

		p#footer-txt {
			text-align: center;
			color: #303032;
			font-family: arial;
			font-size: 12px;
			padding: 0 32px;
		}

		#social-icons {
			width: 28%;
		}

		@media only screen and (max-width: 640px) {
			body[yahoo] .deviceWidth {
				width: 440px!important;
				padding: 0;
			}
			body[yahoo] .center {
				text-align: center!important;
			}
			#social-icons {
				width: 40%;
			}
		}

		@media only screen and (max-width: 479px) {
			body[yahoo] .deviceWidth {
				width: 280px!important;
				padding: 0;
			}
			body[yahoo] .center {
				text-align: center!important;
			}
			#social-icons {
				width: 60%;
			}
		}
	</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Georgia, Times, serif">

	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">


		<!-- Start Header-->
		<table width="600" style="background-color: #1b8af3;" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#e9e9e9">
			<tr>
				<td width="100%">
					<!-- Logo -->
					<table border="0"  cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
						<tr>
							<td id="logo" style="text-align: center;" >
								<h2 style="color: #fff; padding-top: 15px;">Job Apply Mail | Intern Nexus</h2>
							</td>
						</tr>
					</table>
					<!-- End Logo -->
				</td>
			</tr>
		</table>
		<!-- End Header -->


		<!-- Banner Text -->
		<table width="600" height="108" border="0" cellpadding="0" cellspacing="0" align="center" class="border-lr deviceWidth" bgcolor="#ecf0fb">
			<tr>
				<td style="padding-left: 40px;padding-right: 40px; padding-top: 40px; color: #095583;">
					<blockquote type="cite">
						Dear <b> {{ $jobApplyMessage['jobseeker_name'] }}</b>,&nbsp;
						<div>
							<p>Your application for job "<a href="{{ $jobApplyMessage['job_url'] }}">{{ $jobApplyMessage['job_title'] }}</a>" has been sent successfully to <b>{{ $jobApplyMessage['employer_name'] }}</b> &nbsp;</p>

							<p>You will be contacted soon.</p>
						</div>&nbsp;

						<div>
							Regards,<br>
							<strong>Intern Nexus</strong>
						</div>&nbsp;
					</blockquote>
				</td>
			</tr>
		</table>
		<!-- End of Banner Text -->

		<!-- Footer -->
		<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#b6b6b6">
			<tr>
				<td style="text-align: center;">
					<p id="footer-txt" style="padding-top: 20px; padding-bottom: 20px; color:#eeeeed ">
						<b>© Copyright  {{ date("Y") }} - Intern Nexus - All Rights Reserved</b>
					</p>
				</td>
			</tr>
		</table>
		<!-- End of Footer-->

		<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
			<tr>
				<td style="padding-left: 10px; color: #b6babd; line-height: 0.6;font-size: 14px;">
					<small>
						<p>PLEASE DO NOT REPLY TO THIS EMAIL</p>
						<p>This is an auto-generated email, replies to this email are not responded.</p>
						<p>Please contact at <a href="mailto:{{ $jobApplyMessage['intern_nexus_email'] }}">{{ $jobApplyMessage['intern_nexus_email'] }}</a> for queries.</p>
					</small>
				</td>
			</tr>
		</table>
	</table>
	<!-- End Wrapper -->
</body>     
</html>