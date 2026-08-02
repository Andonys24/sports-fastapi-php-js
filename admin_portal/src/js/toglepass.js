(function () {
	function togglePasswordVisibility() {
		const eyeIcon = document.getElementById("eye-icon");
		const passwordInput = document.getElementById("password");
		const passwordInput2 = document.getElementById("password2");

		if (eyeIcon && passwordInput) {
			eyeIcon.addEventListener("click", function () {
				if (passwordInput.type === "password") {
					passwordInput.type = "text";
					if (passwordInput2) {
						passwordInput2.type = "text";
					}
					eyeIcon.classList.remove("fa-eye");
					eyeIcon.classList.add("fa-eye-slash");
				} else {
					passwordInput.type = "password";
					if (passwordInput2) {
						passwordInput2.type = "password";
					}
					eyeIcon.classList.remove("fa-eye-slash");
					eyeIcon.classList.add("fa-eye");
				}
			});
		}
	}

	togglePasswordVisibility();
})();
