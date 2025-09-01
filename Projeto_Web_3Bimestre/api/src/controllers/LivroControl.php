<?php
require_once "api/src/models/Livro.php";
require_once "api/src/DAO/LivroDAO.php";
require_once "api/src/http/Response.php";
require_once "api/src/utils/Logger.php";

require_once "api/src/models/Autor.php";
require_once "api/src/DAO/AutorDAO.php";

require_once "api/src/models/Genero.php";
require_once "api/src/DAO/GeneroDAO.php";

class LivroControl
{
    public function index(): never
    {
        $livroDAO = new LivroDAO();
        $livros = $livroDAO->readAll();

        (new Response(
            success: true,
            message: 'Dados selecionados com sucesso',
            data: ['livros' => $livros],
            httpCode: 200
        ))->send();
        exit();
    }
    public function show(int $idLivro): never
    {
        $livroDAO = new LivroDAO();
        $livro = $livroDAO->readById(idLivro: $idLivro);
        if (isset($livro)) {
            (new Response(
                success: true,
                message: 'livro encontrado com sucesso',
                data: ['LIVROS' => $livro], 
                httpCode: 200  
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'livro não encontrado',
                httpCode: 404
            ))->send();
        }

        exit();
    }
    

    public function controleLivroReadById(int $idLivro): never
    {

        $livroDAO = new LivroDAO();
        $livro = $livroDAO->readById(idLivro: $idLivro);
        (new Response(
            success: true,
            message: 'Dados selecionados com sucesso',
            data: [
                'livros' => $livro
            ],
            httpCode: 200
        ))->send();
    }
    public function listPaginated(int $page = 1, int $limit = 10)
    {
        if ($page < 1) {
            $page = 1;
        }
        if ($limit < 1) {
            $limit = 10;
        }
    }  
    public function store(stdClass $stdLivro): never
    {
        $livro = new Livro();
        $livro->setNomeLivro(nomeLivro: $stdLivro->livro->nomeLivro);
        $livro->setAnoPublicacao(anoPublicacao: $stdLivro->livro->anoPublicacao);
        $livro->setEditora(editora: $stdLivro->livro->editora);
        $livro->getAutor()->setIdAutor(idAutor: $stdLivro->livro->autor->idAutor);
        $livro->getGenero()->setIdGenero(idGenero: $stdLivro->livro->genero->idGenero);

        $livroDAO = new LivroDAO();

        $novoLivro = $livroDAO->create(livro: $livro);
        (new Response(
            success: true,
            message: 'livro Cadastrado com sucesso',
            data: [
                'livros' => $novoLivro
            ],
            httpCode: 200
        ))->send();

        exit();

    }
    public function destroy($idLivro): never
    {


        $livroDAO = new LivroDAO();

        if ($livroDAO->delete(idLivro: $idLivro)) {
            (new Response(
                success: true,
                message: 'Livro excluído com sucesso',
                httpCode: 204
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'Não foi possível excluir o livro',
                error: [
                    'cod' => 'delete_error',
                    'message' => 'O livro não pode ser excluído'
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }
    public function edit(stdClass $stdLivro): never
    {

        $livro = new Livro();
        $livro->setIdLivro(idLivro: $stdLivro->livro->idLivro);
        $livro->setNomeLivro(nomeLivro: $stdLivro->livro->nomeLivro);
        $livro->setEditora(editora: $stdLivro->livro->editora);
        $livro->setAnoPublicacao(anoPublicacao: $stdLivro->livro->anoPublicacao);
        $livro->getAutor()->setIdAutor(idAutor: $stdLivro->livro->autor->idAutor);
        $livro->getGenero()->setIdGenero(idGenero: $stdLivro->livro->genero->idGenero);

        $livroDAO = new LivroDAO();

        if ($livroDAO->update(livro: $livro)) {
            (new Response(
                success: true,
                message: 'livro Atualizado com sucesso',
                data: [
                    'livros' => $livro
                ],
                httpCode: 200
            ))->send();
        } else {
            (new Response(
                success: true,
                message: 'livro não Atualizado',
                error: [
                    'code' =>"livro_update",
                    'message' => "Não foi possível atualizar o livro"
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }
}
?>