
export default class ApiService {
    #token;  

    
    constructor(token = null) {
        this.#token = token;  
    }

    
    async simpleGet(uri) {
        try {
            const response = await fetch(uri);            
            const jsonObj = await response.json();       
            console.log("GET:", uri, jsonObj);           
            return jsonObj;                 

        } catch (error) {
            console.error("Erro ao buscar dados:", error.message);  // Exibe erro no console
            return [];                                     // Retorna array vazio em caso de erro
        }
    }

    async get(uri) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            const response = await fetch(uri, {
                method: "GET",
                headers: headers
            });

            const jsonObj = await response.json();   // Converte resposta para JSON
            console.log("GET:", uri, jsonObj);       // Log para depuração
            return jsonObj;                           // Retorna JSON da resposta

        } catch (error) {
            console.error("Erro ao buscar dados:", error.message);
            return [];                               // Retorna array vazio em caso de erro
        }
    }

    
    async getById(uri, id) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Concatena URI com ID para buscar recurso específico
            const fullUri = `${uri}/${id}`;

            const response = await fetch(fullUri, {
                method: "GET",
                headers: headers
            });

            // Verifica se a resposta HTTP foi bem sucedida (status 2xx)
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const jsonObj = await response.json();
            console.log("GET BY ID:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao buscar por ID:", error.message);
            return null;  // Retorna null se houve erro na requisição
        }
    }

   
    async post(uri, jsonObject) {
    console.log("jsonO" + JSON.stringify(jsonObject));
    try {
        const headers = {
            "Content-Type": "application/json"
        };

        if (this.#token) {
            headers["Authorization"] = `Bearer ${this.#token}`;
            console.log("jsonO" + headers["Authorization"]);
        }

        const response = await fetch(uri, {
            method: "POST",
            headers: headers,
            body: JSON.stringify(jsonObject)
        });

        // Verifica se a resposta foi OK
        if (!response.ok) {
            const text = await response.text();
            throw new Error(`Erro HTTP ${response.status}: ${text}`);
        }

        // Confere se o content-type é JSON antes de tentar parsear
        const contentType = response.headers.get("content-type") || "";
        if (contentType.includes("application/json")) {
            const jsonObj = await response.json();
            console.log("POST:", uri, jsonObj);
            return jsonObj;
        } else {
            const text = await response.text();
            console.warn("Resposta não é JSON:", text);
            return { raw: text }; // retorna texto cru para debug
        }

    } catch (error) {
        console.error("Erro ao buscar dados:", error.message);
        return [];
    }
}

    async put(uri, id, jsonObject) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Monta URL final com ID
            const fullUri = `${uri}/${id}`;

            const response = await fetch(fullUri, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify(jsonObject)
            });

            const jsonObj = await response.json();
            console.log("PUT:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao enviar dados:", error.message);
            return null;  // Retorna null em caso de erro
        }
    }

 
    async delete(uri, id) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Monta URL final com ID
            const fullUri = `${uri}/${id}`;

            // Executa requisição DELETE
            console.log("DELETE: " + fullUri);
            const response = await fetch(fullUri, {
                method: "DELETE",
                headers: headers
            });

            // Verifica sucesso da resposta
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            // Tenta converter resposta para JSON, mas se falhar retorna null
            const jsonObj = await response.json().catch(() => null);
            console.log("DELETE:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao deletar dados:", error.message);
            return null;  // Retorna null em caso de erro
        }
    }

    /**
     * Getter para o token privado.
     * @returns {string|null} Retorna o token atual.
     */
    get token() {
        return this.#token;
    }

    /**
     * Setter para atualizar o token privado.
     * @param {string} value - Novo token a ser setado.
     */
    set token(value) {
        this.#token = value;
    }
}
