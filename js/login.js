//cuando se da click en el boton de iniciar sesion
$("#iniciarSesion").click(function() {
	//"use strict";
	//envia formulario

	$("#login").validate({	
		rules: {
			usuario: "required",
			contrasena: "required"
		},
		messages: {
			usuario: "Debe introducir su usuario.",
			contrasena: "Debe introducir su contraseña."
		},
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
					//$("#login").serialize()
				})
				.done(function(response) {
					//redirecciiona a la pagina del tipo de usuario
					if (response.success === true) {
						window.location.href = "v2/index.php";
						//$(location).attr('href', "empleados/index.php");
					}
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
});
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
/*
$("#registrarse").click(function() {
	"use strict";
	$("#formRegistro").validate({
		rules: {
			apellido_paterno: "required",
			apellido_materno: "required",
			nombres: "required",
			user_registro: "required",
			correo: {
				required: true,
				email: true
			},
			password_registro: "required",
			password2_registro: {
				equalTo: "#password_registro"
			}
		},
		messages: {
			apellido_paterno: "Debe introducir su apellido paterno.",
			apellido_materno: "Debe introducir su apellido materno.",
			nombres: "Debe introducir su nombre.",
			user_registro: "Debe introducir usuario.",
			password_registro: "Debe introducir contraseña .",
			correo: "Debe introducir correo valido.",
			password2_registro: "Ambas contraseñas deben coincidir"
		},
		submitHandler: function() {
			$("#registrarse").attr("disabled", true);
			//conexión asincrona
			$.ajax({
					url: 'php/registros.php',
					type: 'post',
					//dataType: 'html',
					//variables
					data: $("#form_registro").serialize()
				})
				.done(function(data) {
					$("#registrarse").attr("disabled", false);
					//$("#alerta_registro").attr("hidden", false);
					//$("#alerta_registro_servidor").html(data);
					/*
					setTimeout(function (){
						$("#alerta_registro").attr("hidden", true);
						$("#registro").click();
					},3000);
					*/
				    //$("#alerta_registro").attr("hidden", true);
/*
					console.log(data);
				})
				.fail(function() {
					//si falla el registro
					/*
					$("#registrarse").attr("disabled", false);
					$("#alerta_registro").attr("hidden", false);
					$("#alerta_registro_servidor").html('Favor de intentar más tarde');
					//$("#alerta_registro").attr("hidden", true);
					*/
/*
				    console.log("error");
				});
		}
	});
});
*/