import RegisterUserDTO from "../DTO/registerUserDTO";

class RegisterService {
    public async registerUser(
        registerUserDTO: RegisterUserDTO
    ): Promise<boolean> {
        try {
            const response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "", // Asegúrate de manejar el caso null
                },
                body: JSON.stringify({
                    name: registerUserDTO.name,
                    email: registerUserDTO.email,
                    password: registerUserDTO.password,
                    rol: registerUserDTO.rol,
                }),
            });

            const data = await response.json();
            if (!("successfully" in data.message)) {
                throw Error;
            }

            alert("Se ha registrado correctamente al usuario");
            return true;
        } catch (error) {
            alert("No se ha registrado correctamente al usuario");
            return false;
        }
    }
}

export default RegisterService;
