<?php
require_once "api/src/models/Genero.php";
require_once "api/src/DAO/GeneroDAO.php";
require_once "api/src/http/Response.php";
require_once "api/src/utils/Logger.php";

    class GeneroControl{
       public function index(): never
        {
            $generoDAO = new GeneroDAO();
            $generos = $generoDAO->readAll();
            (new Response(
                success: true,
                message: 'Dados selecionados com sucesso',
                data: ['generos' => $generos],
                httpCode: 200
            ))->send();
            exit();
        } 
        public function show(int $idGenero): never
    {
        $generoDAO = new GeneroDAO();
        $generos = $generoDAO->readById(idGenero: $idGenero);
        if (isset($generos)) {
            (new Response(
                success: true,
                message: 'genero encontrado com sucesso',
                data: ['generos' => $generos], 
                httpCode: 200  
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'genero não encontrado',
                httpCode: 404
            ))->send();
        }

        exit();
    }
    public function listPaginated(int $page = 1, int $limit = 10): never
    {
        if ($page < 1) {
            $page = 1;
        }
        if ($limit < 1) {
            $limit = 10;
        }
        $generoDAO = new GeneroDAO();
        $generos = $generoDAO->readByPage(page: $page, limit: $limit);
        (new Response(
            success: true,
            message: 'generos recuperados com sucesso',
            data: [
                'page' => $page,       // Página atual
                'limit' => $limit,     // Registros por página
                'generos' => $generos    // Lista de generos retornados
            ],
            httpCode: 200
        ))->send();
        exit();
    }    
public function store(stdClass $stdGenero): never
    {
        $genero = new Genero();
        $genero->setNomeGenero(nomeGenero: $stdGenero->genero->nomeGenero);
        //$genero->setIdGenero(idGenero:$stdGenero->generos->idGenero);
        $generoDAO = new GeneroDAO();
        $novogenero = $generoDAO->create($genero);
        (new Response(
            success: true,
            message: 'genero cadastrado com sucesso',
            data: ['generos' => $novogenero],
            httpCode: 200
        ))->send();

        exit();
    }
public function edit(stdClass $stdGenero): never
{
    $generoDAO = new GeneroDAO();
    $genero = (new Genero())
        ->setIdGenero(idGenero: $stdGenero->genero->idGenero)
        ->setNomeGenero(nomeGenero: $stdGenero->genero->nomeGenero);

    if ($generoDAO->update(genero: $genero) == true) {
        (new Response(
            success: true,
            message: "Atualizado com sucesso",
            data: ['genero' => $genero],
            httpCode: 200
        ))->send();
        exit();
    } else {
        (new Response(
            success: false,
            message: "Não foi possível atualizar o genero.",
            error: [
                'codigoError' => 'validation_error',
                'message' => 'Não é possível atualizar para um genero que já existe',
            ],
            httpCode: 400
        ))->send();
        exit();
    }
}

     public function destroy(int $idGenero): never
    {
        $generoDAO = new GeneroDAO();
        if ($generoDAO->delete(idGenero: $idGenero) == true) {
            (new Response(httpCode: 204))->send();
        } else {
            (new Response(
                success: false,
                message: 'Não foi possível excluir o genero',
                error: [
                    'cod' => 'delete_error',
                    'message' => 'O genero não pode ser excluído'
                ],
                httpCode: 400
            ))->send();
            exit();
        }
    }
    }
?>