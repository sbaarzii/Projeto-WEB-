<?php
require_once "api/src/http/Response.php";

class LivroMiddleware{

    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdLivro = json_decode(json: $requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(
                success: false,
                message: 'Livro inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Json inválido',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdLivro->livro)) {  
            (new Response(
                success: false,
                message: 'livro inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi enviado o objeto Livro',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdLivro->livro->nomeLivro)) {
            (new Response(
                success: false,
                message: 'Livro inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi eniado o atributo nomeLivro do livro',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $stdLivro;
    }
    public function isValidAnoPublicacao($anoPublicacao): self{
        if (!isset($anoPublicacao)) 
        {
            (new Response(
                success: false,
                message: 'Ano de publicação inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O ano não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        }else if (strlen(string: $anoPublicacao) < 4) 
        {
            (new Response(
                success: false,
                message: 'ano de publicação inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O ano precisa de 4 numeros'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $anoPublicacao) > 4)
         {
            (new Response(
                success: false,
                message: 'Ano de publicação inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O ano de publicação não pode estar vazio ou ter menos de 4 numeros',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this; 
    }

    public function isValidEditora($editora): self {
    
        if (!isset($editora)) 
        {
            (new Response(
                success: false,
                message: 'Editora inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A editora não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $editora) < 3) 
        {
            (new Response(
                success: false,
                message: 'Editora inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A editora precisa de pelo menos 3 caracteres'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $editora) > 3)
         {
            (new Response(
                success: false,
                message: 'Nome da editora inválida',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O nome da editora não pode estar vazio ou ter menos que 3 letras',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this;
    }
    public function isValidNomeLivro($nomeLivro): self
    {
        if (!isset($nomeLivro)) {
            (new Response(
                success: false,
                message: 'Livro inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O livro não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $nomeLivro) < 3) {
            (new Response(
                success: false,
                message: 'Livro inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O livro precisa de pelo menos 3 caracteres'
                ],
                httpCode: 400
            ))->send();


            exit();
        }
        if (!strlen(string: $nomeLivro) > 3) {
            (new Response(
                success: false,
                message: 'Nome do livro inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O nome do livro não pode estar vazio ou ter menos que 3 letras',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        return $this;
    }
    public function hasLivroByName($nomeLivro): self
{
    $livroDAO = new LivroDAO();
    $livro = $livroDAO->readByName(nomeLivro: $nomeLivro);

    if (!empty($livro)) {
        return $this;
    }

    // Caso contrário, não existe livro com esse nome
    (new Response(
        success: false,
        message: "Não existe livro cadastrado com o nome ($nomeLivro)",
        error: [
            'code' => 'validation_error',
            'message' => 'Livro não cadastrado anteriormente',
        ],
        httpCode: 400
    ))->send();

    exit();
}
public function hasNotLivroByName($nomeLivro, $idLivro = null): self
{
    $livroDAO = new LivroDAO();
    $livro = $livroDAO->readByName(nomeLivro: $nomeLivro);

    // Se não existe livro com esse nome, está ok
    if (!isset($livro)) {
        return $this;
    }

    // Se está editando e o id é igual ao do livro encontrado, está ok
    if ($idLivro !== null && isset($livro->$idLivro) && $livro->$idLivro == $idLivro) {
        return $this;
    }

    // Caso contrário, já existe outro livro com esse nome
    (new Response(
        success: false,
        message: "já existe um livro cadastrado com o nome ($nomeLivro)",
        error: [
            'code' => 'validation_error',
            'message' => 'Livro cadastrado anteriormente',
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
   

    // >> Roteador >> [Middleware] >> Controle >> DAO
    public function hasNotLivroById($idLivro): self
    {
        $livroDAO = new LivroDAO();
        $livro = $livroDAO->readById(idLivro: $idLivro);

        // Se o autor EXISTIR (ou seja, não é null)
        if ($livro !== null) {
            (new Response(
                success: false,
                message: "Já existe um livro com o ID fornecido ($idLivro)",
                error: [
                    'code' => 'validation_error',
                    'message' => 'ID de livro já está em uso',
                ],
                httpCode: 400
            ))->send();
            exit(); // Importante para interromper a execução
        }

        return $this;
    }
    public function isValidId($idLivro): self
    {
        if (!isset($idLivro)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o livro',
                error: [
                    'code' => 'livro_validation_error',
                    'message' => 'O id Fornecido não é válido'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!is_numeric(value: $idLivro)) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o livro',
                error: [
                    'code' => 'livro_validation_error',
                    'message' => 'O id Fornecedio não é um número'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if ($idLivro <= 0) {
            (new Response(
                success: true,
                message: 'Não Foi possível buscar o livro',
                error: [
                    'code' => 'livro_validation_error',
                    'message' => 'O id Fornecedio não é positivo'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else {
            return $this;
        }
    }
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