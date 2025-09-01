<?php
require_once "api/src/http/Response.php";

class FuncionarioMiddleware
{
    
    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdFuncionario = json_decode($requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(
                success: false,
                message: 'Cargo inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'mesagem' => 'Json inválido',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdFuncionario->funcionario)) { 
            (new Response(
                success: false,
                message: 'funcionario inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'mesagem' => 'Não foi enviado o objeto funcionario',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdFuncionario->funcionario->nomeFuncionario)) { 
            (new Response(
                success: false,
                message: 'Nome do Funcionário inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'mesagem' => 'Não foi enviado o nome do Funcionario',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdFuncionario->funcionario->email)) { 
            (new Response(
                success: false,
                message: 'email do Funcionário inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'mesagem' => 'Não foi enviado o email do Funcionario',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdFuncionario->funcionario->senha)) {  
            (new Response(
                success: false,
                message: 'senha do Funcionário inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'mesagem' => 'Não foi enviada a senha do Funcionario',
                ],
                httpCode: 400
            ))->send();
            exit();
        } 
        return $stdFuncionario;
    }

    
    public function isValidNomeFuncionario(string $nomeFuncionario = null): self
    {
        if (!isset($nomeFuncionario)) {
            (new Response(
                success: false,
                message: 'Nome do Funcionario inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'Nome não fornecido',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        $nomeFuncionario = trim(string: $nomeFuncionario);
        if (strlen(string: $nomeFuncionario) < 3) {
            (new Response(
                success: false,
                message: 'Nome do Funcionario inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'O nome do Funcionario não pode estar vazio ou ter menos que 4 letras',
                ],
                httpCode: 400
            ))->send();

            exit();
        }

        return $this;
    }
    public function isValidId($idFuncionario): self
    {

        if (!isset($idFuncionario)) {
            (new Response(
                success: false,
                message: 'Não Foi possível buscar o cargo',
                error: [
                    'code' => 'cargo_validation_error',
                    'message' => 'O id do funcionário não é válido'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!is_numeric(value: $idFuncionario)) {
            (new Response(
                success: false,
                message: 'Não Foi possível buscar o cargo',
                error: [
                    'code' => 'cargo_validation_error',
                    'message' => 'O id Fornecedio não é um número'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if ($idFuncionario <= 0) {
            (new Response(
                success: false,
                message: 'Não Foi possível buscar o cargo',
                error: [
                    'code' => 'cargo_validation_error',
                    'message' => 'O id Fornecedio não é positivo'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else {
            return $this;
        }
    }

    
    public function isvalideEmail(string $email): self
    {
        if (empty($email)) {
            (new Response(
                success: false,
                message: 'E-mail inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'O e-mail não pode estar vazio',
                ],
                httpCode: 400
            ))->send();

            exit(); 
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            (new Response(
                success: false,
                message: 'E-mail inválido',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'O formato do e-mail é inválido',
                ],
                httpCode: 400
            ))->send();

            exit();
        }

        return $this;
    }

    
    public function isvalideSenha(string $senha): self
    {
        if (!isset($senha)) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha não pode estar vazia',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        if (strlen($senha) < 8) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha deve ter no mínimo 8 caracteres',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        if (!preg_match(pattern: '/[A-Z]/', subject: $senha)) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha deve conter pelo menos uma letra maiúscula',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        if (!preg_match('/[a-z]/', $senha)) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha deve conter pelo menos uma letra minúscula',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        if (!preg_match('/[0-9]/', $senha)) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha deve conter pelo menos um número',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        if (!preg_match('/[\W_]/', $senha)) {
            (new Response(
                success: false,
                message: 'Senha inválida',
                error: [
                    'codigoError' => 'validation_error',
                    'message' => 'A senha deve conter pelo menos um caractere especial',
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $this;
    }

    

    public function hasNotFuncionarioByEmail($email): self
    {
        $funcionarioDAO = new FuncionarioDAO();
        $funcionarioDAO = $funcionarioDAO->readByEmail(email: $email);

        if (!empty($funcionarioDAO)) {
            (new Response(
                success: false,
                message: 'Cargo inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => "Já existe um Funcionario cadastrado com esse email [$email]"
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $this;
    }
    public function isValidEmail($email): self
    {
        if (!isset($email)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O email do Funcionario não foi enviado'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $email) < 5) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O email do Funcionário precisa de pelo menos 5 caracteres'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'O email do Funcionário não possui um formato válido'
                ],
                httpCode: 400
            ))->send();
            exit();
        }
        return $this;
    }

    public function isValidSenha($senha): self
    {
        if (!isset($senha)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A email do Funcionário não foi enviada'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (strlen(string: $senha) < 8) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A senha do Funcionário precisa de pelo menos 8 caracteres'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!preg_match(pattern: '/[A-Z]/', subject: $senha)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A senha do Funcionário precisa de pelo menos um maiúsculo'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!preg_match(pattern: '/[0-9]/', subject: $senha)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A senha do Funcionário precisa de pelo menos um número'
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!preg_match(pattern: '/[\W_]/', subject: $senha)) {
            (new Response(
                success: false,
                message: 'Funcionário inválido',
                error: [
                    'code' => 'validation_error',
                    'message' => 'A senha do Funcionário precisa de pelo menos um caractere especial'
                ],
                httpCode: 400
            ))->send();
            exit();
        }

        return $this;
    }

    


    public function isValidePage(int $page): self
    {
        return $this;
    }
    public function isValideLimit(int $limit): self
    {
        return $this;
    }


}

