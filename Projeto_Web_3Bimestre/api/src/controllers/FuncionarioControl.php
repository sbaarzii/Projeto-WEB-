<?php
require_once "api/src/models/Funcionario.php";
require_once "api/src/DAO/FuncionarioDAO.php";

require_once "api/src/http/Response.php";
require_once "api/src/utils/Logger.php";


class FuncionarioControl
{
    
    public function index(): never
    {
        $funcionarioDAO = new FuncionarioDAO();

        $funcionarios = $funcionarioDAO->readAll();

        (new Response(
            success: true,
            message: 'Dados selecionados com sucesso',
            data: ['funcionarios' => $funcionarios],
            httpCode: 200
        ))->send();
        exit();
    }


    public function controleFuncionarioReadById(int $idFuncionario): never
    {

        $FuncionarioDAO = new FuncionarioDAO();
        $funcionario = $FuncionarioDAO->readById(idFuncionario: $idFuncionario);
        (new Response(
            success: true,
            message: 'Dados selecionados com sucesso',
            data: [
                'funcionarios' => $funcionario
            ],
            httpCode: 200
        ))->send();
    }



    public function store(stdClass $stdFuncionario): never
    {
        $funcionario = new Funcionario();

        $funcionario->setNomeFuncionario(nomeFuncionario: $stdFuncionario->funcionario->nomeFuncionario);
        $funcionario->setEmail(email: $stdFuncionario->funcionario->email);
        $funcionario->setSenha(senha: $stdFuncionario->funcionario->senha);

        $funcionarioDAO = new FuncionarioDAO();

        $novoFuncionario = $funcionarioDAO->create(funcionario: $funcionario);
        (new Response(
            success: true,
            message: 'Funcionario Cadastrado com sucesso',
            data: [
                'funcionarios' => $novoFuncionario
            ],
            httpCode: 200
        ))->send();

        exit();

    }

    public function destroy($idFuncionario): never
    {


        $funcionarioDAO = new FuncionarioDAO();

        if ($funcionarioDAO->delete(idFuncionario: $idFuncionario)) {
            (new Response(
                success: true,
                message: 'Funcionario excluído com sucesso',
                httpCode: 204
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'Não foi possível excluir o Funcionario',
                error: [
                    'cod' => 'delete_error',
                    'message' => 'O Funcionario não pode ser excluído'
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }
    public function edit(stdClass $stdFuncionario): never
    {

        $funcionario = new Funcionario();
        $funcionario->setIdFuncionario(idFuncionario: $stdFuncionario->funcionario->idFuncionario);
        $funcionario->setNomeFuncionario(nomeFuncionario: $stdFuncionario->funcionario->nomeFuncionario);
        $funcionario->setEmail(email: $stdFuncionario->funcionario->email);
        $funcionario->setSenha(senha: $stdFuncionario->funcionario->senha);

        $funcionarioDAO = new FuncionarioDAO();

        if ($funcionarioDAO->update(funcionario: $funcionario)) {
            (new Response(
                success: true,
                message: 'Funcionario Atualizado com sucesso',
                data: [
                    'funcionarios' => $funcionario
                ],
                httpCode: 200
            ))->send();
        } else {
            (new Response(
                success: true,
                message: 'Funcionario não Atualizado',
                error: [
                    'code' =>"funcionario_update",
                    'message' => "Não foi possível atualizar o funcionário"
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }

}
