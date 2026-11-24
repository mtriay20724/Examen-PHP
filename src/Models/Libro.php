<?php

class Libro {
    public function __construct(
        private string $titulo,
        private string $autor,
        private string $genero,
        private int $anio,
        private int $paginas,
        private string $portada
    ) {}

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getGenero(): string
    {
        return $this->genero;
    }

    public function getAnio(): int
    {
        return $this->anio;
    }

    public function getPaginas(): int
    {
        return $this->paginas;
    }

    public function getPortada(): string
    {
        return $this->portada;
    }

    public function existe(): bool
    {
        $libros = new Libro();
        foreach($libros as $libro) {

        }
    }
}