<?php
require_once "api/src/DAO/LoginDAO.php";
require_once "api/src/http/Response.php";
require_once "api/src/utils/Logger.php";
require_once "api/src/utils/MeuTokenJWT.php";
require_once "api/src/models/Funcionario.php";

use Firebase\JWT\MeuTokenJWT;

class LoginControl
{

    public function autenticar(stdClass $stdLogin): never
    {
        $loginDAO = new LoginDAO();

        $funcionario = new Funcionario();


        $funcionario->setEmail($stdLogin->funcionario->email);
        $funcionario->setSenha($stdLogin->funcionario->senha);

        $funcionarioLogado = $loginDAO->verificarLogin($funcionario);

        if (empty($funcionarioLogado)) {
            (new Response(
                success: false,
                message: 'Usuário e senha inválidos',

                httpCode: 401
            ))->send();
        } else {
           

            $claims = new stdClass();

            $claims->name = $funcionarioLogado[0]->getNomeFuncionario();
            $claims->email = $funcionarioLogado[0]->getEmail();
            $claims->idFuncionario = $funcionarioLogado[0]->getIdFuncionario();


            $meuToken = new MeuTokenJWT();

            $token = $meuToken->gerarToken($claims);

            (new Response(
                success: true,
                message: 'Usuário e senha validados',
                data: [
                    'token' => $token,
                    'funcionario' => $funcionarioLogado
                ],

                httpCode: 200
            ))->send();
        }

        exit();
    }





}
