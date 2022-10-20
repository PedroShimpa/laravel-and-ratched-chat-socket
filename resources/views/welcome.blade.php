<!DOCTYPE html>
<html lang="en">
<style>
	TML CSS #chat1 .form-outline .form-control~.form-notch div {
		pointer-events: none;
		border: 1px solid;
		border-color: #eee;
		box-sizing: border-box;
		background: transparent;
	}

	#chat1 .form-outline .form-control~.form-notch .form-notch-leading {
		left: 0;
		top: 0;
		height: 100%;
		border-right: none;
		border-radius: .65rem 0 0 .65rem;
	}

	#chat1 .form-outline .form-control~.form-notch .form-notch-middle {
		flex: 0 0 auto;
		max-width: calc(100% - 1rem);
		height: 100%;
		border-right: none;
		border-left: none;
	}

	#chat1 .form-outline .form-control~.form-notch .form-notch-trailing {
		flex-grow: 1;
		height: 100%;
		border-left: none;
		border-radius: 0 .65rem .65rem 0;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading {
		border-top: 0.125rem solid #39c0ed;
		border-bottom: 0.125rem solid #39c0ed;
		border-left: 0.125rem solid #39c0ed;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading,
	#chat1 .form-outline .form-control.active~.form-notch .form-notch-leading {
		border-right: none;
		transition: all 0.2s linear;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle {
		border-bottom: 0.125rem solid;
		border-color: #39c0ed;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle,
	#chat1 .form-outline .form-control.active~.form-notch .form-notch-middle {
		border-top: none;
		border-right: none;
		border-left: none;
		transition: all 0.2s linear;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing {
		border-top: 0.125rem solid #39c0ed;
		border-bottom: 0.125rem solid #39c0ed;
		border-right: 0.125rem solid #39c0ed;
	}

	#chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing,
	#chat1 .form-outline .form-control.active~.form-notch .form-notch-trailing {
		border-left: none;
		transition: all 0.2s linear;
	}

	#chat1 .form-outline .form-control:focus~.form-label {
		color: #39c0ed;
	}

	#chat1 .form-outline .form-control~.form-label {
		color: #bfbfbf;
	}
</style>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
	<title>WebSocket</title>
</head>

<body>
	<section style="background-color: #eee;">
		<div class="container py-5">

			<div class="row d-flex justify-content-center">
				<div class="col-md-8 col-lg-6 col-xl-4">

					<div class="card" id="chat1" style="border-radius: 15px;">
						<div class="card-header d-flex justify-content-between align-items-center p-3 bg-info text-white border-bottom-0" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
							<i class="fas fa-angle-left"></i>
							<p class="mb-0 fw-bold">WebSocket</p>
							<i class="fas fa-times"></i>
						</div>
						<div class="card-body" id="messageBox">



							<div class="d-flex flex-row justify-content-end mb-4">
								<div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
									<p class="small mb-0">Thank you, I really like your product.</p>
								</div>

							</div>

							<div class="d-flex flex-row justify-content-start mb-4">

								<div class="ms-3" style="border-radius: 15px;">
									<div class="bg-image">

										<a href="#!">
											<div class="mask"></div>
										</a>
									</div>
								</div>
							</div>

							<div class="d-flex flex-row justify-content-start mb-4">

								<div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
									<p class="small mb-0">...</p>
								</div>
							</div>

							<div class="form-outline">
								<textarea class="form-control" id="textAreaExample" rows="4"></textarea>
								<label class="form-label" for="textAreaExample">Type your message</label>
							</div>

						</div>
					</div>

				</div>
			</div>

		</div>
	</section>
	<script>
		conn = new WebSocket('ws://localhost:8888')

		conn.addEventListener('open', (event) => {

			conn.send(JSON.stringify({
				message: 'oi'
			}))
		});


		conn.onmessage = function(e) {
			var data = JSON.parse(e.data);
			message = data.message
			console.log(message)
			adicionaMsgUser(message)
		};

		function adicionaMsgUser(message) {
			html = ''
			html += '<div class="d-flex flex-row justify-content-start mb-4">'

			html += '<div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">'
			html += '<p class="small mb-0">' + message + '</p>'
			html += '</div>'
			html += '</div>'
			$('#messageBox').html(html)
		}
	</script>
</body>

</html>