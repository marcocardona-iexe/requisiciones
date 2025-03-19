window.envs = {
    development: {
        API_URL: "http://127.0.0.1:8080/requisiciones/",
    },
    production: {
        API_URL: "",
    },
};

// Definir el entorno actual (Cámbialo a "production" cuando sea necesario)
window.currentEnv = "development";

// Variable global con la configuración actual
window.env = window.envs[window.currentEnv] || window.envs["development"];
