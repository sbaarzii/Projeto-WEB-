<?php
require_once "api/src/models/Cargo.php";
require_once "api/src/db/Database.php";
require_once "api/src/utils/Logger.php";


class FuncionarioDAO
{

    public function create(Funcionario $funcionario): Funcionario|false
    {
        $idFuncionario = $funcionario->getIdFuncionario();

        if (isset($idFuncionario)) {
            return $this->createWithoutId(funcionario: $funcionario);
        }

        return $this->createWithId(funcionario: $funcionario);
    }


    private function createWithoutId(Funcionario $funcionario): Funcionario
    {
        $query = 'INSERT INTO Funcionario (
                            nomeFuncionario,
                            email,
                            senha     
                        ) VALUES ( 
                            :nomeFuncionario,
                            :email,
                            :senha
                        )';

        $statement = Database::getConnection()->prepare($query);

       
        $statement->bindValue(
            param: ':nomeFuncionario',
            value: $funcionario->getNomeFuncionario(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':email',
            value: $funcionario->getEmail(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':senha',
            value: $funcionario->getSenha(),
            type: PDO::PARAM_STR
        );

        $statement->execute();

        $funcionario->setIdFuncionario((int) Database::getConnection()->lastInsertId());

        return $funcionario;
    }


   
    private function createWithId(Funcionario $funcionario): Funcionario
    {
        $query = 'INSERT INTO Funcionario (
            idFuncionario,
            nomeFuncionario,
            email,
            senha
        ) VALUES (
            :idFuncionario,
            :nomeFuncionario,
            :email,
            :senha
            )';

        $statement = Database::getConnection()->prepare(query: $query);

        $statement->bindValue(
            param: ':idFuncionario',
            value: $funcionario->getIdFuncionario(),
            type: PDO::PARAM_INT
        );

        $statement->bindValue(
            param: ':nomeFuncionario',
            value: $funcionario->getNomeFuncionario(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':email',
            value: $funcionario->getEmail(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':senha',
            value: $funcionario->getSenha(),
            type: PDO::PARAM_STR
        );

    
        $statement->execute();

        return $funcionario;
    }





  
    public function readAll(): array
    {
        $query = '
        SELECT
            idFuncionario,
            nomeFuncionario,
            email   
        FROM funcionario
        ORDER BY nomeFuncionario ASC
    ';

        $statement = Database::getConnection()->query(query: $query);

        $resultados = [];

        while ($stdLinha = $statement->fetch(mode: PDO::FETCH_OBJ)) {

            $funcionario = (new Funcionario())
                ->setIdFuncionario(idFuncionario: $stdLinha->idFuncionario)                 // ID do funcionário
                ->setNomeFuncionario(nomeFuncionario: $stdLinha->nomeFuncionario)           // Nome
                ->setEmail(email: $stdLinha->email);                                         // Email

            

            $resultados[] = $funcionario;
        }

        return $resultados;
    }




    public function readByPage(int $page, int $limit): array
    {


        $resultados = [];

        return $resultados;
    }


    
    public function readById(int $idFuncionario): array
    {
        $query = '
        SELECT
            idFuncionario,
            nomeFuncionario,
            email
        FROM funcionario 
        WHERE idFuncionario = :idFuncionario
        ORDER BY nomeFuncionario ASC
    ';

        $statement = Database::getConnection()->prepare(query: $query);

        $statement->bindValue(
            param: ':idFuncionario',
            value: $idFuncionario,
            type: PDO::PARAM_INT
        );
        $statement->execute();
        $funcionario = new Funcionario();
        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);

        if (!$linha) {
            return []; 
        }
        $funcionario
            ->setIdFuncionario(idFuncionario: $linha->idFuncionario)              
            ->setNomeFuncionario(nomeFuncionario: $linha->nomeFuncionario)          
            ->setEmail(email: $linha->email);                                          
        return [$funcionario];
    }


    public function readByEmail(string $email): array
    {
        $linha = [];
        $query = 'SELECT
                idFuncionario,
                nomeFuncionario,
                email
              FROM funcionario
              WHERE email = :email
              LIMIT 1'; 

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(
            param: ':email',
            value: $email,
            type: PDO::PARAM_STR
        );
        $statement->execute();

        $linha = $statement->fetch(mode: PDO::FETCH_OBJ);

        if (!$linha) {
            return []; 
        }

        $funcionario = new Funcionario();

        $funcionario
            ->setIdFuncionario(idFuncionario: $linha->idFuncionario)                    
            ->setNomeFuncionario(nomeFuncionario: $linha->nomeFuncionario)     
            ->setEmail(email: $linha->email);

        

        return [$funcionario];
    }

    public function update(Funcionario $funcionario): bool
    {
        $query = 'UPDATE Funcionario
                  SET 
                    nomeFuncionario = :nomeFuncionario,     
                    email = :email,
                  WHERE 
                    idFuncionario = :idFuncionario';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(
            param: ':nomeFuncionario',
            value: $funcionario->getNomeFuncionario(),
            type: PDO::PARAM_STR
        );

        $statement->bindValue(
            param: ':email',
            value: $funcionario->getEmail(),
            type: PDO::PARAM_STR
        );


        $statement->bindValue(
            param: ':idFuncionario',
            value: $funcionario->getIdFuncionario(),
            type: PDO::PARAM_INT
        );

        $statement->execute();
        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function delete(int $idFuncionario): bool
    {
        $query = 'DELETE FROM 
              Funcionario 
              WHERE 
              idFuncionario = :idFuncionario';

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(
            param: ':idFuncionario',
            value: $idFuncionario,
            type: PDO::PARAM_INT
        );
        $statement->execute();
        return $statement->rowCount() > 0;
    }


}
