import Swal from "sweetalert2";

(function () {
	const btnEliminarProveedor = document.querySelectorAll(".boton-eliminar.proveedor");
	const btnEliminarCategoria = document.querySelectorAll(".boton-eliminar.categoria");
	const btnEliminarProducto = document.querySelectorAll(".boton-eliminar.producto");
	const btnEliminarUsuario = document.querySelectorAll(".boton-eliminar.usuario");
	const btnEliminarCompra = document.querySelectorAll(".boton-eliminar.compras");

	function agregarEventoEliminar(btns, tipo, url) {
		if (btns && btns.length > 0) {
			btns.forEach((btn) => {
				btn.addEventListener("click", (e) => {
					e.preventDefault();

					// Usamos currentTarget para asegurar que obtenemos el botón y no un icono interno
					const id = e.currentTarget.dataset.id;

					Swal.fire({
						title: `¿Estás seguro de eliminar este ${tipo}?`,
						text: "No podrás recuperar esta información una vez eliminada",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "Sí, eliminar!",
						customClass: {
							popup: "swal2-popup",
							title: "swal2-title",
							text: "swal2-text",
						},
					}).then((result) => {
						if (result.isConfirmed) {
							eliminar(url, id);
						}
					});
				});
			});
		}
	}

	async function eliminar(url, id) {
		const datos = new FormData();
		datos.append("id", id);

		try {
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});

			const resultado = await respuesta.json();
			console.log(resultado);

			if (resultado.resultado) {
				Swal.fire({
					position: "top-end",
					icon: "success",
					title: resultado.mensaje,
					showConfirmButton: false,
					timer: 1500,
					customClass: {
						popup: "swal2-popup",
						title: "swal2-title",
						text: "swal2-text",
					},
				}).then(() => {
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: resultado.mensaje || "Hubo un error al eliminar!",
					customClass: {
						popup: "swal2-popup",
						title: "swal2-title",
						text: "swal2-text",
					},
				});
			}
		} catch (error) {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "Algo salió mal con la conexión!",
				customClass: {
					popup: "swal2-popup",
					title: "swal2-title",
					text: "swal2-text",
				},
			});
		}
	}

	// Inicialización de eventos
	agregarEventoEliminar(btnEliminarCategoria, "categoría", "/categories/delete");
	agregarEventoEliminar(btnEliminarProveedor, "proveedor", "/suppliers/delete");
	agregarEventoEliminar(btnEliminarUsuario, "usuario", "/users/delete");
	agregarEventoEliminar(btnEliminarProducto, "producto", "/products/delete");
	agregarEventoEliminar(btnEliminarCompra, "Registro de Compra", "/purchases/delete");
})();