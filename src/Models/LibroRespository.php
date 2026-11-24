<?php
declare(strict_types=1);

namespace Models;

class LibroRespository
{
    private const FICHERO = BASE_PATH . '/data/biblioteca.txt';

    public function findAll(): array
    {

        if (!is_file(self::FICHERO) || !is_readable(self::FICHERO)) {
            throw new \Exception('No se puede leer el fichero de recetas');
        }
        $biblioteca = [];
        $datos = json_decode(file_get_contents(self::FICHERO), true);
        foreach ($datos as $libro) {
            $biblioteca[] = new Libro($libro['titulo'], $libro['autor'], $libro['genero'], $libro['anio'], $libro['paginas'], $libro['portada'] ?? '');
        }
        return $biblioteca;
    }

    public function existe(string $titulo, string $autor): bool
    {
        $libros = $this->findAll();
        foreach ($libros as $libro) {
            if($libro->getTitulo() == $titulo && $libro->getAutor() == $autor){
                return true;
            }
        }
        return false;
    }

    public function guardar(string $titulo, string $autor, string $genero, string $anio, int $pagina, string $portada): bool
    {
        if (!file_exists(self::FICHERO) || !is_readable(self::FICHERO)) {
            throw new \Exception('No se puede leer el fichero de recetas');
        }
        $libros = $this->existe($titulo, $autor);
        if($libros > 0){
            return false;
        }

        $json = file_get_contents(self::FICHERO);
        $datos = json_decode(file_get_contents(self::FICHERO), true);

        $datos[] = ['titulo'=> $titulo, 'autor' => $autor, 'genero' => $genero, 'anio' => $anio, 'pagina' => $pagina ];
        $json = json_encode($datos, JSON_PRETTY_PRINT);
        if(file_put_contents(self::FICHERO, $json)){
            return true;
        }
        return false;
    }
}