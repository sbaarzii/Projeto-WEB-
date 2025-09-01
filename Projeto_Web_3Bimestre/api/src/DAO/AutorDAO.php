<?php

require_once "api/src/models/Autor.php";
require_once "api/src/db/Database.php";
require_once "api/src/utils/Logger.php";

class AutorDAO
{

    public function create(Autor $autor): Autor
    {
        $idAutor = $autor->getIdAutor();
        if (isset($idAutor)) {
            return $this->createWithId(autor: $autor);
        } else {
            return $this->createWithoutId(autor: $autor);
        }
    }

    private function createWithId(Autor $autor): Autor
    {
        $query = 'INSERT INTO autor (idAutor, nomeAutor, nacionalidade) VALUES (:idAutor, :nomeAutor, :nacionalidade)';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idAutor', $autor->getIdAutor(), PDO::PARAM_INT);
        $statement->bindValue(':nomeAutor', $autor->getNomeAutor(), PDO::PARAM_STR);
        $statement->bindValue(':nacionalidade', $autor->getNacionalidade(), PDO::PARAM_STR);
        $statement->execute();
        return $autor;
    }
    private function createWithoutId(Autor $autor): Autor
    {
        $query = 'INSERT INTO autor (nomeAutor ,nacionalidade) VALUES (:nomeAutor,:nacionalidade)';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':nomeAutor', $autor->getNomeAutor(), PDO::PARAM_STR);
        $statement->bindValue(':nacionalidade', $autor->getNacionalidade(), PDO::PARAM_STR);
        $statement->execute();
        $autor->setIdAutor(idAutor: (int) Database::getConnection()->lastInsertId());
        return $autor;
    }
    public function delete(int $idAutor): bool
    {
        $query = 'DELETE FROM autor WHERE idAutor = :idAutor';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idAutor', $idAutor, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
    public function readAll(): array
    {
        $resultados = [];
        $query = 'SELECT idAutor, nomeAutor, nacionalidade FROM autor ORDER BY idAutor ASC';
        $statement = Database::getConnection()->query(query: $query);
        while ($linha = $statement->fetch(mode: PDO::FETCH_OBJ)) {
            $autor = (new Autor())
                ->setIdAutor(idAutor: $linha->idAutor)
                ->setNomeAutor(nomeAutor: $linha->nomeAutor)
                ->setNacionalidade(nacionalidade: $linha->nacionalidade);
            $resultados[] = $autor;
        }
        return $resultados;
    }
    public function readByName(string $nomeAutor): Autor|null
    {
        $query = 'SELECT idAutor, nomeAutor, nacionalidade FROM autor WHERE nomeAutor = :nomeAutor';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(param: ':nomeAutor', value: $nomeAutor, type: PDO::PARAM_STR);
        $statement->execute();
        $objStdAutor = $statement->fetch(mode: PDO::FETCH_OBJ);
        if (!$objStdAutor) {
            return null;
        }
        return (new Autor())
            ->setIdAutor(idAutor: $objStdAutor->idAutor)
            ->setNomeAutor(nomeAutor: $objStdAutor->nomeAutor)
            ->setNacionalidade(nacionalidade: $objStdAutor->nacionalidade);
    }
    public function readByPage(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $query = 'SELECT idAutor, nomeAutor, nacionalidade FROM autor ORDER BY nomeAutor ASC LIMIT :limit OFFSET :offset;';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(param: ':limit', value: $limit, type: PDO::PARAM_INT);
        $statement->bindValue(param: ':offset', value: $offset, type: PDO::PARAM_INT);
        $statement->execute();
        $resultados = [];
        while ($stdLinha = $statement->fetch(mode: PDO::FETCH_OBJ)) {
            $autor = (new Autor())
                ->setIdAutor(idAutor: $stdLinha->idAutor)
                ->setNomeAutor(nomeAutor: $stdLinha->nome)
                ->setNacionalidade(nacionalidade: $stdLinha->nacionalidade);
            $resultados[] = $autor;
        }
        return $resultados;
    }
    public function readById(int $idAutor): array
    {
        $resultados = [];
        $query = 'SELECT idAutor, nomeAutor, nacionalidade FROM autor WHERE idAutor = :idAutor;';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idAutor', $idAutor, PDO::PARAM_INT);
        $statement->execute();
        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);
        if (!$linha) {
            return [];
        } else {
            $autor = (new Autor())
                ->setIdAutor(idAutor: $linha->idAutor)
                ->setNomeAutor(nomeAutor: $linha->nomeAutor)
                ->setNacionalidade(nacionalidade: $linha->nacionalidade);
            return [$autor];
        }
    }
   public function update(Autor $autor): bool
{
    $query = 'UPDATE autor SET nomeAutor = :nomeAutor, nacionalidade = :nacionalidade WHERE idAutor = :idAutor';
    $statement = Database::getConnection()->prepare($query);

    $statement->bindValue(':nomeAutor', $autor->getNomeAutor(), PDO::PARAM_STR);
    $statement->bindValue(':nacionalidade', $autor->getNacionalidade(), PDO::PARAM_STR);
    $statement->bindValue(':idAutor', $autor->getIdAutor(), PDO::PARAM_INT);

    $statement->execute();

    return $statement->rowCount() > 0;
}
}
