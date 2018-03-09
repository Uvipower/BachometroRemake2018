//validación del formulario
$("#login").validate({	
	//reglas
	rules: {
		usuario: "required",
		contrasena: "required"
	},
	//mensajes que apareceran abajo del input
	messages: {
		usuario: "Debe introducir su usuario.",
		contrasena: "Debe introducir su contraseña."
	},
	//al enviar el formulario
	submitHandler: function() {
		$.ajax({
				//php encargada de hacer la consulta del usuario
				url: 'php/controladorUsuario.php',
				type: 'post',
				dataType: 'json',
				//variables
				//envio de los campos
				data: {
					"opcion": "login",
					"usuario": $("#usuario").val(),
					"contrasena": $("#contrasena").val(),
					"mantenerSesion": $("#mantenerSesion").val()
				}
			})
			.done(function(response) {
				//redirecciiona a la pagina del tipo de usuario
				if (response.success === true) {
					window.location.href = "v2/index.php";
				}
				//muestra alerta
				else {
					$("#respuestaServidor").html(
						$("<div>", {
							'class': 'alert alert-danger',
							'role': 'alert',
							'text': response.mensaje
						}).show().fadeIn('slow')
					);
				}
			})
			.fail(function() {
				//si falla la conexion
				$("#respuestaServidor").html(
					$("<div>", {
						'class': 'alert alert-danger',
						'role': 'alert',
						'text': "Problemas al conectar, intente más tarde"
					}).show().fadeIn('slow')
				);
			});
	}
});

//ver contraseña escrita codigo que vino con la plantilla sb-admin
$(function() {
	$("input[type='password'][data-eye]").each(function(i) {
		var $this = $(this);

		$this.wrap($("<div/>", {
			style: 'position:relative'
		}));
		$this.css({
			paddingRight: 60
		});
		$this.after($("<div/>", {
			html: 'Ver',
			class: 'btn btn-primary btn-sm',
			id: 'passeye-toggle-'+i,
			style: 'position:absolute;right:10px;top:50%;transform:translate(0,-50%);-webkit-transform:translate(0,-50%);-o-transform:translate(0,-50%);padding: 2px 7px;font-size:12px;cursor:pointer;'
		}));
		$this.after($("<input/>", {
			type: 'hidden',
			id: 'passeye-' + i
		}));
		$this.on("keyup paste", function() {
			$("#passeye-"+i).val($(this).val());
		});
		$("#passeye-toggle-"+i).on("click", function() {
			if($this.hasClass("show")) {
				$this.attr('type', 'password');
				$this.removeClass("show");
				$(this).removeClass("btn-outline-primary");
			}else{
				$this.attr('type', 'text');
				$this.val($("#passeye-"+i).val());				
				$this.addClass("show");
				$(this).addClass("btn-outline-primary");
			}
		});
	});
});

//falta validar los datos
$("#formRegistro").validate({
	//reglas
	rules: {
		apellidoPaterno: "required",
		apellidoMaterno: "required",
		nombres: "required",
		usuarioRegistro: "required",
		correoElectronico: {
			required: true,
			email: true
		},
		confirmarContrasena1: "required",
		confirmarContrasena: {
			equalTo: "#confirmarContrasena1"
		}
	},
	//mensajes que apareceran abajo del input
	messages: {
		apellidoPaterno: "Debe introducir su apellido paterno.",
		apellidoMaterno: "Debe introducir su apellido materno.",
		nombres: "Debe introducir su nombre.",
		usuarioRegistro: "Debe introducir usuario.",
		correoElectronico: "Debe introducir correo valido.",
		confirmarContrasena1: "Debe introducir contraseña .",
		confirmarContrasena: "Ambas contraseñas deben coincidir"
	},
	//al enviar el formulario
	submitHandler: function() {
		//conexión asincrona
		$.ajax({
				url: 'php/controladorUsuario.php',
				type: 'post',
				dataType: 'json',
				//variables
				data: {
					"opcion": "registro",
					"apellidoPaterno": $("#apellidoPaterno").val(),	
					"apellidoMaterno": $("#apellidoMaterno").val(),
					"nombres": $("#nombres").val(),
					"usuario": $("#usuarioRegistro").val(),
					"correoElectronico": $("#correoElectronico").val(),
					"confirmarContrasena1": $("#confirmarContrasena1").val(),
					"confirmarContrasena": $("#confirmarContrasena").val()
				}
			})
			.done(function(data) {
				alert(data);
				console.log(data);
			})
			.fail(function() {
			    console.log("error");
			});
	}
});
