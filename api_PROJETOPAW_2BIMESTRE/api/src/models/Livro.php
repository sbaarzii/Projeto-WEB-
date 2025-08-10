<?php

declare(strict_types=1);

class Livro implements JsonSerializable
{

    public function __construct(
        private ?int $idLivro = null,
        private string $nomeLivro = "",
        private string $editora = "",
        private string $anoPublicacao = "",
        private Autor $autor = new Autor(),
        private Genero $genero = new Genero()
            ) {
    }
    public function jsonSerialize(): array
    {
        return [
            'idLivro' => $this->getidLivro(),    
            'nomeLivro' => $this->getNomeLivro(),
            'editora'=> $this->getEditora(),
            'anoPublicacao'=> $this->getAnoPublicacao(),
            'autor' => [
                'idAutor' => $this->autor->getIdAutor(),
            ],
            'genero' => [
                'idGenero'=> $this->genero->getIdGenero()
            ]
            ];
    }
     public function getidLivro(): int|null
    {
        return $this->idLivro;
    }
    public function setidLivro(int $idLivro): self
    {
        $this->idLivro = $idLivro;
        return $this;
    }
    public function getNomeLivro(): string
    {
        return $this->nomeLivro;
    }
    public function setNomeLivro(string $nomeLivro): self
    {
        $this->nomeLivro = $nomeLivro;
        return $this;
    }
    public function getEditora(): string
    {
        return $this->editora;
    }
    public function setEditora(string $editora): self
    {
        $this->editora = $editora;
        return $this;
    }
    public function getAnoPublicacao(): string{
        return $this->anoPublicacao;
    }
    public function setAnoPublicacao(string $anoPublicacao): self{
            $this->anoPublicacao = $anoPublicacao;
            return $this;
    }
    public function getAutor(): Autor
    {
        return $this->autor;
    }

    public function setAutor(Autor $autor): self
    {
        $this->autor = $autor;
        return $this;
    }
    public function getGenero(): Genero
    {
        return $this->genero;
    }

    public function setGenero(Genero $genero): self
    {
        $this->genero = $genero;
        return $this;
    }

}

?>