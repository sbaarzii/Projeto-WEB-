<?php
require_once "api/src/http/Response.php";

class GeneroMiddleware
{  
public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdGenero = json_decode(json: $requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(
                success: false,
                message: 'Genero inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Json inválido',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdGenero->genero)) {  
            (new Response(
                success: false,
                message: 'Genero inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi enviado o objeto Genero',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdGenero->genero->nomeGenero)) {
            (new Response(
                success: false,
                message: 'Genero inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi eniado o atributo nomeGenero do genero',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $stdGenero;
    }
    public function isValidNomeGenero($nomeGenero): self
    {
        if (!isset($nomeGenero)) {
            (new Response(
                success: false,
                message: 'Genero inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O genero não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $nomeGenero) < 3) {
            (new Response(
                success: false,
                message: 'Genero inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O genero precisa de pelo menos 3 caracteres'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $nomeGenero) > 3) {
            (new Response(
                success: false,
                message: 'Nome do genero inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O nome do genero não pode estar vazio ou ter menos que 3 letras',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this;
    }
    public function hasGeneroByName($nomeGenero): self
    {
        $generoDAO = new GeneroDAO();

        $genero = $generoDAO->readByName(nomeGenero: $nomeGenero);

        if (isset($e)) {
            return $this;
        }

        (new Response(
            success: false,
            message: "já existe um genero cadastrado com o nome ($nomeGenero)",
            error: [
                'code' => 'validation_error',  // Código de erro padrão
                'message' => 'Genero não cadastrado anteriormente',  // Mensagem descritiva
            ],
            httpCode: 400  
        ))->send();

        exit();
    }
    public function hasNotGeneroByName($nomeGenero): self
    {
        $generoDAO = new GeneroDAO();

        $genero = $generoDAO->readByName(nomeGenero: $nomeGenero);

        if (!isset($genero)) {
            return $this;
        }
        (new Response(
            success: false,
            message: "já existe um genero cadastrado com o nome ($nomeGenero)",
            error: [
                'code' => 'validation_error',  // Código de erro padrão
                'message' => 'Genero cadastrado anteriormente',  // Mensagem descritiva
            ],
            httpCode: 400  
        ))->send();

        exit();
    }
    public function hasGeneroById($idGenero): self
    {
        $generoDAO = new GeneroDAO();
        $genero = $generoDAO->readById(idGenero: $idGenero);
        if (!isset($genero)) {
            (new Response(
                success: false,
                message: "Não existe um genero com o id Fornecido",
                error: [
                    'code' => 'validation_error',  // Código de erro padrão
                    'message' => 'genero informado não existente',  // Mensagem descritiva
                ],
                httpCode: 400  // Código HTTP 400 - Bad Request
            ))->send();
        }
        return $this;
    }
    public function hasNotGeneroById($idGenero): self
    {
        $generoDAO = new GeneroDAO();
        $genero = $generoDAO->readById(idGenero: $idGenero);

        // Se o autor EXISTIR (ou seja, não é null)
        if ($genero !== null) {
            (new Response(
                success: false,
                message: "Já existe um genero com o ID fornecido ($idGenero)",
                error: [
                    'code' => 'validation_error',
                    'message' => 'ID de genero já está em uso',
                ],
                httpCode: 400
            ))->send();
            exit(); // Importante para interromper a execução
        }

        return $this;
    }
public function isValidId($idGenero): self
    {
        if (!isset($idGenero)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o genero',
                error: [
                    'code' => 'genero_validation_error',
                    'message' => 'O id Fornecido não é válido'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!is_numeric(value: $idGenero)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o genero',
                error: [
                    'code' => 'genero_validation_error',
                    'message' => 'O id Fornecedio não é um número'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if ($idGenero <= 0) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o genero',
                error: [
                    'code' => 'genero_validation_error',
                    'message' => 'O id Fornecedio não é positivo'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else {
            return $this;
        }
    }


    // >> Roteador >> [Middleware] >> Controle >> DAO
    public function isValidPage($page): self
    {
        if (!is_numeric(value: $page)) {
            (new Response(
                success: false,
                message: 'A página fornecida não é um número',
                error: [
                    'cod' => 'validation_error',
                    'message' => 'ID inválido',
                ],
                httpCode: 400
            ))->send();
            exit(); // Interrompe a execução caso haja erro
        } else if ($page <= 0) {
            (new Response(
                success: false,
                message: 'Identificação da página inválida',
                error: [
                    'cod' => 'validation_error',
                    'message' => 'Página inválida',
                ],
                httpCode: 400
            ))->send();

            exit(); // Interrompe a execução caso haja erro
        }

        // Se a validação passar, retorna o próprio objeto para permitir encadeamento
        return $this;
    }

    public function isValidLimit($limit): self
    {

        if (!is_numeric(value: $limit)) {
            (new Response(
                success: false,
                message: 'O limite fornecido não é um número',
                error: [
                    'cod' => 'validation_error',
                    'message' => 'Limite inválido',
                ],
                httpCode: 400
            ))->send();
            exit(); // Interrompe a execução caso haja erro
        } else if ($limit <= 0) {
            (new Response(
                success: false,
                message: 'O limete não pode ser menor ou igual a zero',
                error: [
                    'cod' => 'validation_error',
                    'message' => 'Limite inválido',
                ],
                httpCode: 400
            ))->send();
            exit(); // Interrompe a execução caso haja erro
        }
        // Se a validação passar, retorna o próprio objeto para permitir encadeamento
        return $this;
    }
}