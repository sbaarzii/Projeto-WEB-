<?php

declare(strict_types=1);

/**
 * Classe [Cargo]
 * 
 * Implementa JsonSerializable para permitir serialização em JSON
 * Essa classe representa a tabela Funcionario no banco de dados
 *
 * Esta classe faz parte de uma API REST didática desenvolvida com o objetivo de
 * ensinar, de forma simples e prática, os principais conceitos da arquitetura REST
 * e do padrão de projeto MVC (Model-View-Controller).
 *
 * A API realiza o CRUD completo (Create, Read, Update, Delete) das tabelas `cargo` e `funcionario`,
 * sendo ideal para estudantes e desenvolvedores que estão começando com PHP moderno e boas práticas de organização.
 *
 * A construção passo a passo desta API está disponível gratuitamente na playlist do YouTube:
 * https://www.youtube.com/playlist?list=PLpdOJd7P4_HsiLH8b5uyFAaaox4r547qe
 *
 * @author      Hélio Esperidião
 * @copyright   Copyright (c) 2025 Hélio Esperidião
 * @license     GPL (GNU General Public License)
 * @website http://helioesperidiao.com
 * @github https://github.com/helioesperidiao
 * @linkedin https://www.linkedin.com/in/helioesperidiao/
 * @youtube https://www.youtube.com/c/HélioEsperidião
 */
class Funcionario implements JsonSerializable
{

    // Construtor da classe
    public function __construct(
        private ?int $idFuncionario = null,
        private string $nomeFuncionario = "",
        private string $email = "",
        private string $senha = "",
    
    ) {
    }

    /**
     * Serializa o objeto para formato JSON
     * @return array Array associativo com os dados do funcionário
     */
    public function jsonSerialize(): array
    {
        // Retorna um array com os dados do funcionário para conversão em JSON
        return [
            'idFuncionario' => $this->getIdFuncionario(),                  // Inclui o ID do funcionário
            'nomeFuncionario' => $this->getNomeFuncionario(),              // Inclui o nome do funcionário
            'email' => $this->getEmail(),                                  // Inclui o e-mail do funcionário
            
        ];
    }


    // Método getter para idFuncionario
    public function getIdFuncionario(): int | null
    {
        return $this->idFuncionario;
    }

    // Método setter para idFuncionario
    public function setIdFuncionario($idFuncionario): self
    {
        $this->idFuncionario = $idFuncionario;
        return $this;
    }

    // Método getter para nomeFuncionario
    public function getNomeFuncionario(): string
    {
        return $this->nomeFuncionario;
    }

    // Método setter para nomeFuncionario
    public function setNomeFuncionario($nomeFuncionario): self
    {
        $this->nomeFuncionario = $nomeFuncionario;
        return $this;
    }

    // Método getter para email
    public function getEmail(): string
    {
        return $this->email;
    }

    // Método setter para email
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    // Método getter para senha
    public function getSenha(): string
    {
        return $this->senha;
    }

    // Método setter para senha
    public function setSenha($senha): self
    {
        $this->senha = $senha;
        return $this;
    }

    // Método getter para recebeValeTransporte
   

    
}
