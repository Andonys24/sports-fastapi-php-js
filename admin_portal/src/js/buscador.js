(function () {
	document.addEventListener("DOMContentLoaded", function () {
		iniciarApp();
	});

	function iniciarApp() {
		buscarPorFecha();
	}

	function buscarPorFecha() {
		const fechaInput = document.querySelector("#fecha-admin");
		if (fechaInput) {
			fechaInput.addEventListener("input", function (e) {
				const fechaSeleccionada = e.target.value;

				window.location = `?fecha=${fechaSeleccionada}`;
			});
		}
		return;
	}
})();
