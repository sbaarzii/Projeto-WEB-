<?php


declare(strict_types=1);


/**
 * Classe [Response]
 * Responsável por padronizar a resposta JSON de uma API.
 * Implementa JsonSerializable para permitir conversão personalizada em JSON.
 *
 */

class Response implements JsonSerializable
{
    /**
     * Construtor da classe.
     * Inicializa os atributos que compõem a resposta da API.
     *
     * @param bool $success   Indica se a operação foi bem-sucedida.
     * @param string $message Mensagem explicativa sobre a resposta.
     * @param ?array $data    Dados retornados pela requisição (opcional).
     * @param ?array rror   Informações de erro, caso existam (opcional).
     * @param int $httpCode   Código HTTP da resposta (padrão: 200).
     */
    public function __construct(
        private bool $success = true,
        private ?string $message = null,
        private ?array $data = null,
        private ?array $error = null,
        private int $httpCode = 200
    ) {

    }

    /**
     * Método obrigatório da interface JsonSerializable.
     * Define como o objeto deve ser convertido em JSON.
     *
     * @return array Estrutura de resposta a ser convertida em JSON.
     */
    public function jsonSerialize(): array
    {
        $response = [];
        $response['success'] = $this->success;

        if (!empty($this->message)) {
            $response['message'] = $this->message;
        }
        // Se o atributo $data não estiver vazio, inclui no array de resposta
        if (!empty($this->data)) {
            $response['data'] = $this->data;
        }

        if (!empty($this->error)) {
            $response['error'] = $this->error;
        }

        // Retorna o array que será convertido em JSON
        return $response;
    }

    /**
     * Envia a resposta para o cliente.
     * Define o código HTTP, converte o objeto em JSON e encerra a execução.
     *
     * @return never (indica que a função não retorna — chama exit).
     */
    public function send(): never
    {
        header(header: "Content-Type: application/json");

        // Define o código de status HTTP da resposta
        http_response_code(response_code: $this->httpCode);

        // Converte o objeto para JSON e envia para a saída padrão
        echo json_encode(value: $this);

        // Encerra o script imediatamente após enviar a resposta
        exit(); // Encerra a execução após enviar a resposta
    }
}


