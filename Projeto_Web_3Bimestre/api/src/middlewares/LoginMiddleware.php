<?php
require_once "api/src/http/Response.php";

class LoginMiddleware
{

    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdLogin = json_decode(json: $requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(
                success: false,
                message: 'Cargo inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Json inválido',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdLogin->funcionario)) {  
            (new Response(
                success: false,
                message: 'Login inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi enviado o objeto funcionario',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdLogin->funcionario->email)) {
            (new Response(
                success: false,
                message: 'Email inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi eniado o atributo email',
                ],
                httpCode: 400
            ))->send();
            exit();
        } else if (!isset($stdLogin->funcionario->senha)) {
            (new Response(
                success: false,
                message: 'Senha inválido',
                error: [
                    'code' => 'validation_error',
                    'mesagem' => 'Não foi eniado o atributo senha',
                ],
                httpCode: 400
            ))->send();
            exit();
        }



        return $stdLogin;
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
    public function isvalidSenha(string $senha): self
    {
        // Verifica se a senha está vazia
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

        // Verifica o comprimento mínimo
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

        // Verifica se contém pelo menos uma letra maiúscula
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

        // Verifica se contém pelo menos uma letra minúscula
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

        // Verifica se contém pelo menos um número
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

        // Verifica se contém pelo menos um caractere especial
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

}

