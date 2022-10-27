<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

	<link rel="stylesheet" href="{{ asset('./css/chat.css')}}">
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
						<div class="card-body">

							<div id="messageBox">

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

							<div class="form-outline">
								<textarea class="form-control" id="textAreaExample" rows="4"></textarea>
								<label class="form-label" for="textAreaExample">Digite sua mensagem</label>
							</div>

						</div>
					</div>

				</div>
			</div>

		</div>
	</section>
	<script>
		conn = new WebSocket("{{ env('WEBSOCKET_URL')}}")
		conn.addEventListener('open', (event) => {});
		username = "{{ auth()->user()->name}}"
		uuid = "{{ auth()->user()->uuid}}"
		user_id = "{{ auth()->user()->id}}"

		$('#textAreaExample').keyup(function(e) {

			message = $(this).val()

			if (e.keyCode == 13) {

				if (message.trim().length < 1) {
					$(this).addClass('is-invalid')
					return;
				}

				conn.send(JSON.stringify({
					message: message,
					username: username,
					uuid: uuid,
					user_id: user_id
				}))
				$(this).removeClass('is-invalid')
				$(this).val('')
			}
		});

		conn.onmessage = function(e) {
			receiveMessage(e);
		};

		function receiveMessage(e) {
			var data = JSON.parse(e.data);

			if (data.type == 'message') {
				isMessage(data)
			} else if (data.type == 'history') {
				isHistory(data);
			}


		}

		function isMessage(value) {
			message = value.message
			created = value.created
			from = value.username
			fromUsername = value.username
				fromId = value.user_id
				if (user_id == fromId) {
					thisUserMessage(message, created, fromUsername)
				} else {

					adicionaMsgUser(message, created, fromUsername)
				}
		}

		function isHistory(data) {
			$.each(data.history, (index, value) => {

				isMessage(value)
			})
		}

		function adicionaMsgUser(message, created, from) {
			html = ''
			html += '<div class="d-flex flex-row justify-content-start mb-4">'

			html += '<div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">'
			html += '<p class="small mb-0">' + from + ': ' + message + '</p>'
			html += '<span class="text-muted">' + created + '</span>'
			html += '</div>'
			html += '</div>'
			$('#messageBox').append(html)
		}

		function thisUserMessage(message, created, from) {
			html = '<div class="d-flex flex-row justify-content-end mb-4">'
			html += '<div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">'
			html += '<p class="small mb-0">' + from + ': ' + message + '</p>'
			html += '<span class="text-muted">' + created + '</span>'
			html += '</div>'
			html += '</div>'
			$('#messageBox').append(html)
		}
	</script>
</body>

</html>