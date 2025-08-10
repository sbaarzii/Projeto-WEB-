<?php

require_once "api/src/models/Livro.php";
require_once "api/src/db/Database.php";
require_once "api/src/utils/Logger.php";
class LivroDAO
{

    public function create(Livro $livro): Livro
    {
        $idLivro = $livro->getIdLivro();
        if (!isset($idLivro)) {
            return $this->createWithoutId(livro: $livro);
        } else {
            return $this->createWithId(livro: $livro);
        }
    }
    private function createWithoutId(Livro $livro): Livro
    {        
        $query = 'INSERT INTO livro (                            
                            nomeLivro,
                            anoPublicacao,
                            editora,
                            idAutor,
                            idGenero
                        ) VALUES (                            
                            :nomeLivro,
                            :anoPublicacao,
                            :editora,
                            :idAutor,
                            :idGenero )';

        $statement = Database::getConnection()->prepare($query);        
        $statement->bindValue(
            param: ':nomeLivro',
            value: $livro->getNomeLivro(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':anoPublicacao',
            value: $livro->getAnoPublicacao(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':editora',
            value: $livro->getEditora(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':idAutor',
            value: $livro->getAutor()->getIdAutor(),
            type: PDO::PARAM_INT
        );
        $statement->bindValue(
            param: ':idGenero',
            value: $livro->getGenero()->getIdGenero(),
            type: PDO::PARAM_INT
        );

        $statement->execute();
        $livro->setidLivro((int) Database::getConnection()->lastInsertId());
        return $livro;
    }
    private function createWithId(Livro $livro): Livro
    {
        $query = 'INSERT INTO livro (
                    idLivro,
                    nomeLivro,
                    anoPublicacao,
                    editora,
                    idAutor,
                    idGenero
                    ) VALUES (
                        :idLivro,
                        :nomeLivro,
                        :anoPublicacao,
                        :editora,
                        :idAutor,
                        :idGenero
                    )';

        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(
            param: ':idLivro',
            value: $livro->getidLivro(),
            type: PDO::PARAM_INT
        );
        $statement->bindValue(
            param: ':nomeLivro',
            value: $livro->getNomeLivro(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':anoPublicacao',
            value: $livro->getAnoPublicacao(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':editora',
            value: $livro->getEditora(),
            type: PDO::PARAM_STR
        );
        $statement->bindValue(
            param: ':idAutor',
            value: $livro->getAutor()->getIdAutor(),
            type: PDO::PARAM_INT
        );
        $statement->bindValue(
            param: ':idGenero',
            value: $livro->getGenero()->getIdGenero(),
            type: PDO::PARAM_INT
        );
        try {
            var_dump($livro->getAutor()->getIdAutor(), $livro->getGenero()->getIdGenero());
    $statement->execute();
} catch (PDOException $e) {
     echo "Erro: " . $e->getMessage() . "<br>";
    print_r($statement->errorInfo());
    exit;
}

       

        return $livro;
    }
    public function readAll(): array
    {
        $query = 'SELECT
            idLivro,
            nomeLivro,
            anoPublicacao,
            editora,
            livro.idAutor,
            livro.idGenero
        FROM livro
        JOIN autor ON autor.idAutor = livro.idAutor
        JOIN genero ON genero.idGenero = livro.idGenero
        ORDER BY nomeLivro ASC

    ';

        $statement = Database::getConnection()->query(query: $query);

        $resultados = [];

        while ($stdLinha = $statement->fetch(mode: PDO::FETCH_OBJ)) {

            $livro = (new Livro())
                ->setidLivro(idLivro: $stdLinha->idLivro )                 
                ->setNomeLivro(nomeLivro: $stdLinha->nomeLivro)        
                ->setAnoPublicacao(anoPublicacao: $stdLinha->anoPublicacao)                                         // Email
                ->setEditora(editora: $stdLinha->editora);

            if ($stdLinha->idAutor !== null) {
            $livro->getAutor()->setIdAutor(idAutor: $stdLinha->idAutor); 
            } else {
            $livro->getAutor()->setIdAutor(idAutor: 0); 
        }

        if ($stdLinha->idGenero !== null) {
            $livro->getGenero()->setIdGenero(idGenero: $stdLinha->idGenero);
        } else {
            $livro->getGenero()->setIdGenero(idGenero: 0); 
        }
        $resultados[] = $livro;
        }

        return $resultados;
    }
    public function delete(int $idLivro): bool
    {
        $query = 'DELETE FROM livro WHERE idLivro = :idLivro';
        $statement = Database::getConnection()->prepare(query: $query);
        $statement->bindValue(':idLivro', $idLivro, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
    
    public function readById(int $idLivro): array
    {
        $query = 'SELECT
            idLivro,
            nomeLivro,
            anoPublicacao,
            editora,
            livro.idAutor,
            livro.idGenero
        FROM livro 
        JOIN autor ON autor.idAutor = livro.idAutor
        JOIN genero ON genero.idGenero = livro.idGenero
        WHERE idLivro = :idLivro
        ORDER BY nomeLivro ASC
    ';

        $statement = Database::getConnection()->prepare(query: $query);

        $statement->bindValue(
            param: ':idLivro',
            value: $idLivro,
            type: PDO::PARAM_INT
        );
        $statement->execute();
        $livro = new Livro();
        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);

        if (!$linha) {
            return []; 
        }
        $livro
            ->setIdLivro(idLivro: $linha->idLivro)                 
            ->setNomeLivro(nomeLivro: $linha->nomeLivro)         
            ->setAnoPublicacao(anoPublicacao: $linha->anoPublicacao)                                      
            ->setEditora(editora:$linha->editora); 

        $livro
            ->getAutor()
            ->setIdAutor(idAutor: $linha->idAutor);  
        $livro
            ->getGenero()
            ->setIdGenero(idGenero: $linha->idGenero);     

        return [$livro];
    }

    public function readByName(string $nomeLivro): array
    {
        $linha = [];

        $query = 'SELECT
            idLivro,
            nomeLivro,
            anoPublicacao,
            editora,
            livro.idAutor,
            livro.idGenero
            FROM livro
            JOIN autor ON autor.idAutor = livro.idAutor
            JOIN genero ON genero.idGenero = livro.idGenero
            WHERE nomeLivro = :nomeLivro
            LIMIT 1';
        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(
            param: ':nomeLivro',
            value: $nomeLivro,
            type: PDO::PARAM_STR
        );

        $statement->execute();

        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);

        if (!$linha) {
            return []; 
        }

        $livro = new Livro();
        $livro
         ->setidLivro(idLivro: $linha->idLivro )                 
                ->setNomeLivro(nomeLivro: $linha->nomeLivro)        
                ->setAnoPublicacao(anoPublicacao: $linha->anoPublicacao)                                         // Email
                ->setEditora(editora: $linha->editora);

            $livro->getAutor()
                ->setIdAutor(idAutor: $linha->idAutor);       
            $livro->getGenero()
                ->setIdGenero(idGenero: $linha->idGenero) ;
            return [$livro];

    }
     public function update(Livro $livro): bool
    {
        $query = 'UPDATE livro
                  SET 
                    nomeLivro = :nomeLivro,     
                    anoPublicacao = :anopublicacao,
                    editora = :editora,
                    livro.idAutor = :idAutor,
                    livro.idAutor = :idGenero
                  WHERE 
                    livro.idAutor = :idAutor';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(
            param: ':nomeLivro',
            value: $livro->getNomeLivro(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':ano_publicacao',
            value: $livro->getAnoPublicacao(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':editora',
            value: $livro->getEditora(),
            type: PDO::PARAM_INT
        );

        $statement->bindValue(
            param: ':idAutor',
            value: $livro->getAutor()->getIdAutor(),
            type: PDO::PARAM_INT
        );
        $statement->bindValue(
            param: ':idGenero',
            value: $livro->getGenero()->getIdGenero(),
            type: PDO::PARAM_INT
        );

        $statement->bindValue(
            param: ':idLivro',
            value: $livro->getidLivro(),
            type: PDO::PARAM_INT
        );

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }
    public function readByPage(int $page, int $limit): array
    {


        $resultados = [];

        return $resultados;
    }
    

}
?>