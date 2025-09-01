<?php

declare(strict_types=1);
 
class Genero implements JsonSerializable
{

    public function __construct(
        private ?int $idGenero = null,
        private string $nomeGenero = "",        
    ) {
    }
    public function jsonSerialize(): array
    {
        return [
            'idGenero' => $this->idGenero,    
            'nomeGenero' => $this->nomeGenero            
        ];
    }
     public function getidGenero(): int|null
    {
        return $this->idGenero;
    }
    public function setidGenero(int $idGenero): self
    {
        $this->idGenero = $idGenero;
        return $this;
    }
    public function getnomeGenero(): string
    {
        return $this->nomeGenero;
    }
    public function setnomeGenero(string $nomeGenero): self
    {
        $this->nomeGenero = $nomeGenero;
        return $this;
    }    

}