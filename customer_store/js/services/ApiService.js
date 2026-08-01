class ApiService {
    constructor() {
        this.baseUrl = 'http://127.0.0.1:8000/api/v1';
    }

    async get(endpoint) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`);
            if (!response.ok) throw new Error(`Error en la petición: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error("Error GET:", error);
            return null;
        }
    }

    async post(endpoint, data) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            if (!response.ok) throw new Error(`Error en la petición: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error("Error POST:", error);
            return null;
        }
    }
}

// Instancia global para ser reutilizada
const api = new ApiService();