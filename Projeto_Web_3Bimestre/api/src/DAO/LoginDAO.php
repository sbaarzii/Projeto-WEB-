<?php
require_once "api/src/db/Database.php";
require_once "api/src/utils/Logger.php";


class LoginDAO{

    public function verificarLogin(Funcionario $funcionario): array
    {
        $query = '  SELECT
                        idFuncionario,
                        nomeFuncionario,
                        email
                    FROM funcionario 
                    WHERE 
                        email = :email AND
                        senha = :senha
                    ORDER BY nomeFuncionario ASC
                ';

        $statement = Database::getConnection()->prepare(query: $query);

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


}
