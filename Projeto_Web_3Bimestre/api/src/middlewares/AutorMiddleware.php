<?php
require_once "api/src/http/Response.php";

class AutorMiddleware
{
    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdAutor = json_decode(json: $requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(
                success: false,
                message: 'Autor inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Json inválido',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdAutor->autor)) {  
            (new Response(
                success: false,
                message: 'Autor inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi enviado o objeto Autor',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdAutor->autor->nomeAutor)) {
            (new Response(
                success: false,
                message: 'Autor inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi eniado o atributo nomeAutor do autor',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $stdAutor;
    }
    public function isValidNomeAutor($nomeAutor): self
    {
        if (!isset($nomeAutor)) {
            (new Response(
                success: false,
                message: 'Autor inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O autor não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $nomeAutor) < 3) {
            (new Response(
                success: false,
                message: 'Autor inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O autor precisa de pelo menos 3 caracteres'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $nomeAutor) > 3) {
            (new Response(
                success: false,
                message: 'Nome do autor inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O nome do autor não pode estar vazio ou ter menos que 3 letras',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this;
    }
    public function isValidNacionalidade($nacionalidade): self
    {
         if (!isset($nacionalidade)) {
            (new Response(
                success: false,
                message: 'Nacionalidade inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A nacionalidade não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $nacionalidade) < 3) {
            (new Response(
                success: false,
                message: 'Nacionalidade inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A nacionalidade precisa de pelo menos 3 caracteres'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $nacionalidade) > 3) {
            (new Response(
                success: false,
                message: 'Nome da nacionalidade inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O nome da nacionalidade não pode estar vazia ou ter menos que 3 letras',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this;


    }
    public function hasAutorByName($nomeAutor): self
    {
        $autorDAO = new AutorDAO();

        $autor = $autorDAO->readByName(nomeAutor: $nomeAutor);

        if (isset($autor)) {
            return $this;
        }

        (new Response(
            success: false,
            message: "já existe um autor cadastrado com o nome ($nomeAutor)",
            error: [
                'code' => 'validation_error',  // Código de erro padrão
                'message' => 'Autor não cadastrado anteriormente',  // Mensagem descritiva
            ],
            httpCode: 400  
        ))->send();

        exit();
    }
    public function hasNotAutorByName($nomeAutor): self
    {
        $autorDAO = new AutorDAO();

        $autor = $autorDAO->readByName(nomeAutor: $nomeAutor);

        if (!isset($autor)) {
            return $this;
        }
        (new Response(
            success: false,
            message: "já existe um autor cadastrado com o nome ($nomeAutor)",
            error: [
                'code' => 'validation_error',  // Código de erro padrão
                'message' => 'Autor cadastrado anteriormente',  // Mensagem descritiva
            ],
            httpCode: 400  
        ))->send();

        exit();
    }
    public function hasAutorById($idAutor): self
    {
        $autorDAO = new AutorDAO();
        $autor = $autorDAO->readById(idAutor: $idAutor);
        if (!isset($autor)) {
            (new Response(
                success: false,
                message: "Não existe um autor com o id Fornecido",
                error: [
                    'code' => 'validation_error',  // Código de erro padrão
                    'message' => 'autor informado não existente',  // Mensagem descritiva
                ],
                httpCode: 400  // Código HTTP 400 - Bad Request
            ))->send();
        }
        return $this;
    }
   

    // >> Roteador >> [Middleware] >> Controle >> DAO
    public function hasNotAutorById($idAutor): self
    {
        $autorDAO = new AutorDAO();
        $autor = $autorDAO->readById(idAutor: $idAutor);

        // Se o autor EXISTIR (ou seja, não é null)
        if ($autor !== null) {
            (new Response(
                success: false,
                message: "Já existe um autor com o ID fornecido ($idAutor)",
                error: [
                    'code' => 'validation_error',
                    'message' => 'ID de autor já está em uso',
                ],
                httpCode: 400
            ))->send();
            exit(); // Importante para interromper a execução
        }

        return $this;
    }
public function isValidId($idAutor): self
    {
        if (!isset($idAutor)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o autor',
                error: [
                    'code' => 'autor_validation_error',
                    'message' => 'O id Fornecido não é válido'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!is_numeric(value: $idAutor)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o autor',
                error: [
                    'code' => 'autor_validation_error',
                    'message' => 'O id Fornecedio não é um número'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if ($idAutor <= 0) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o autor',
                error: [
                    'code' => 'autor_validation_error',
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