import RegisterService from './registerService.ts';

class CreateUser {
    constructor() {
        this.registerService = new RegisterService();
        this.name = document.getElementById("name");
        this.email = document.getElementById("email");
        this.password = document.getElementById("password");
        this.role = document.getElementById("rol");
        this.botonValidar = document.querySelector(".btn.successBtn");

        this.initialize();
    }

    obtenerValores() {
        const name = this.name.value;
        const email = this.email.value;
        const role = this.role.value;
        const password = this.password.value;
        console.log("Password:", password);
        console.log("Rol:", role);
        console.log("name:", name);
        console.log("email:", email);

        return { name, email, role, password };
    }

    async handleRegister(event) {
        event.preventDefault(); // Evita que el formulario se envíe
        const { name, email, role, password } = this.obtenerValores();

        const registerUserDTO = {
            name,
            email,
            password,
            rol: parseInt(role, 10) // Asegúrate de convertir el rol a número
        };

        const success = await this.registerService.registerUser(registerUserDTO);
        if (success) {
            console.log("Usuario registrado correctamente");
        } else {
            console.log("Error al registrar el usuario");
        }
    }

    initialize() {
        this.botonValidar.addEventListener("click", this.handleRegister.bind(this));
    }
}

document.addEventListener("DOMContentLoaded", function() {
    new CreateUser();
});


