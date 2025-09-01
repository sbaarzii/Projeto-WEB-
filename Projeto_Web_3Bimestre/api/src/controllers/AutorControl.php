<?php
require_once "api/src/models/Autor.php";
require_once "api/src/DAO/AutorDAO.php";
require_once "api/src/http/Response.php";
require_once "api/src/utils/Logger.php";

class AutorControl
{
public function index(): never
        {
            $autorDAO = new AutorDAO();
            $autores = $autorDAO->readAll();
            (new Response(
                success: true,
                message: 'Dados selecionados com sucesso',
                data: ['autores' => $autores],
                httpCode: 200
            ))->send();
            exit();
        }
public function show(int $idAutor): never
    {
        $autorDAO = new AutorDAO();
        $autor = $autorDAO->readById(idAutor: $idAutor);
        if (isset($autor)) {
            (new Response(
                success: true,
                message: 'autor encontrado com sucesso',
                data: ['autores' => $autor], 
                httpCode: 200  
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'autor não encontrado',
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
        $autorDAO = new AutorDAO();
        $autores = $autorDAO->readByPage(page: $page, limit: $limit);
        (new Response(
            success: true,
            message: 'Autores recuperados com sucesso',
            data: [
                'page' => $page,       // Página atual
                'limit' => $limit,     // Registros por página
                'autores' => $autores    // Lista de cargos retornados
            ],
            httpCode: 200
        ))->send();
        exit();
    }    
public function store(stdClass $stdAutor): never
    {
        $autor = new Autor();
        $autor->setNomeAutor(nomeAutor: $stdAutor->autor->nomeAutor);
        $autor->setNacionalidade(nacionalidade:$stdAutor->autor->nacionalidade);
        $autorDAO = new AutorDAO();
        $novoautor = $autorDAO->create($autor);
        (new Response(
            success: true,
            message: 'Autor cadastrado com sucesso',
            data: ['autores' => $novoautor],
            httpCode: 200
        ))->send();

        exit();
    }
public function edit(stdClass $stdAutor): never
    {
        $autorDAO = new AutorDAO();
        $autor = (new Autor())
            ->setIdAutor(idAutor: $stdAutor->autor->idAutor)
            ->setNomeAutor(nomeAutor: $stdAutor->autor->nomeAutor)
            ->setNacionalidade(nacionalidade: $stdAutor->autor->nacionalidade);
        if ($autorDAO->update(autor: $autor) == true) {
            (new Response(
                success: true,
                message: "Atualizado com sucesso",
                data: ['autores' => $autor],
                httpCode: 200
            ))->send();
            exit();
        } else {
            (new Response(
                success: false,
                message: "Não foi possível atualizar o autor.",
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'Não é possível atualizar para um autor que já existe',
                ],
                httpCode: 400
            ))->send();
            exit();
        }
    }
     public function destroy(int $idAutor): never
    {
        $autorDAO = new AutorDAO();
        if ($autorDAO->delete(idAutor: $idAutor) == true) {
            (new Response(httpCode: 204))->send();
        } else {
            (new Response(
                success: false,
                message: 'Não foi possível excluir o autor',
                error: [
                    'cod' => 'delete_error',
                    'message' => 'O autor não pode ser excluído'
                ],
                httpCode: 400
            ))->send();
            exit();
        }
    }

}





?>