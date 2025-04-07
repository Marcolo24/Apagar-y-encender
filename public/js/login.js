document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        let isValid = true;

        // Obtener los campos
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();
        
        // Obtener los spans de error
        let emailError = document.getElementById("emailError");
        let passwordError = document.getElementById("passwordError");

        // Resetear mensajes de error
        emailError.textContent = "";
        passwordError.textContent = "";

        // Validar email
        if (email === "") {
            emailError.textContent = "El email es obligatorio.";
            isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) {
            emailError.textContent = "Ingresa un email válido.";
            isValid = false;
        }

        // Validar contraseña
        if (password === "") {
            passwordError.textContent = "La contraseña es obligatoria.";
            isValid = false;
        } else if (password.length < 6) {
            passwordError.textContent = "La contraseña debe tener al menos 6 caracteres.";
            isValid = false;
        }

        // Si hay errores, prevenir el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });
});
