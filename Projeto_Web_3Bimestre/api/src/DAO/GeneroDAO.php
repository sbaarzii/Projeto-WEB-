<?php

require_once "api/src/models/Genero.php";
require_once "api/src/db/Database.php";
require_once "api/src/utils/Logger.php";

class GeneroDAO
{
    public function create(Genero $genero): Genero
    {
        $idGenero = $genero->getIdGenero();
        if (isset($idGenero)) {
            return $this->createWithId(genero: $genero);
        } else {
            return $this->createWithoutId(genero: $genero);
        }
    }
    private function createWithId(Genero $genero): Genero
    {
        $query = 'INSERT INTO genero (idGenero, nomeGenero) VALUES (:idGenero, :nomeGenero)';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idGenero', $genero->getIdGenero(), PDO::PARAM_INT);
        $statement->bindValue(':nomeGenero', $genero->getNomeGenero(), PDO::PARAM_STR);
        $statement->execute();
        return $genero;
    }
    private function createWithoutId(Genero $genero): Genero
    {
        $query = 'INSERT INTO genero (nomeGenero) VALUES (:nomeGenero)';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':nomeGenero', $genero->getNomeGenero(), PDO::PARAM_STR);
        $statement->execute();
        $genero->setIdGenero(idGenero: (int) Database::getConnection()->lastInsertId());
        return $genero;
    }
    public function delete(int $idGenero): bool
    {
        $query = 'DELETE FROM genero WHERE idGenero = :idGenero';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idGenero', $idGenero, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
    public function readAll(): array
    {
        $resultados = [];
        $query = 'SELECT idGenero , nomeGenero FROM genero ORDER BY idGenero ASC';
        $statement = Database::getConnection()->query(query: $query);
        while ($linha = $statement->fetch(mode: PDO::FETCH_OBJ)) {
            $genero = (new Genero())
                ->setIdGenero(idGenero: $linha->idGenero)
                ->setNomeGenero(nomeGenero: $linha->nomeGenero);
            $resultados[] = $genero;
        }
        return $resultados;
    }
    public function readByName(string $nomeGenero): Genero|null
    {
        $query = 'SELECT idGenero,nomeGenero FROM genero WHERE nomeGenero = :nomeGenero';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(param: ':nomeGenero', value: $nomeGenero, type: PDO::PARAM_STR);
        $statement->execute();
        $objStdGenero = $statement->fetch(mode: PDO::FETCH_OBJ);
        if (!$objStdGenero) {
            return null;
        }
        return (new Genero())
            ->setIdGenero(idGenero: $objStdGenero->idGenero)
            ->setNomeGenero(nomeGenero: $objStdGenero->nomeGenero);
    }
    public function readByPage(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $query = 'SELECT idGenero, nomeGenero FROM genero ORDER BY idGenero ASC LIMIT :limit OFFSET :offset;';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(param: ':limit', value: $limit, type: PDO::PARAM_INT);
        $statement->bindValue(param: ':offset', value: $offset, type: PDO::PARAM_INT);
        $statement->execute();
        $resultados = [];
        while ($stdLinha = $statement->fetch(mode: PDO::FETCH_OBJ)) {
            $genero = (new Genero())
                ->setIdGenero(idGenero: $stdLinha->idGenero)
                ->setNomeGenero(nomeGenero: $stdLinha->nome);
                 $resultados[] = $genero;
        }
        return $resultados;
    }
    public function readById(int $idGenero): array
    {
        $resultados = [];
        $query = 'SELECT idGenero, nomeGenero FROM genero WHERE idGenero = :idGenero;';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idGenero', $idGenero, PDO::PARAM_INT);
        $statement->execute();
        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);
        if (!$linha) {
            return [];
        } else {
            $genero = (new Genero())
                ->setIdGenero(idGenero: $linha->idGenero)
                ->setNomeGenero(nomeGenero: $linha->nomeGenero);
                return [$genero];
        }
    }
    public function update(Genero $genero): bool
    {
        $query = 'UPDATE genero SET nomeGenero = :nomeGenero WHERE idGenero = :idGenero';
        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(':nomeGenero', $genero->getNomeGenero(), PDO::PARAM_STR);    
        $statement->bindValue(':idGenero', $genero->getIdGenero(), PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }
}
