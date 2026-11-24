<?php
declare(strict_types=1);
namespace Requests;

use Models\Libro;

final class LibroRequest{
    private array $datos = [];
    private array $errores = [];

    private array $portadaTmpInfo = [];

    private const MAX_BYTES = 0.5 * 1024 * 1024;

    private const MIMES_PERMITIDOS = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    public function __construct(array $entrada, array $archivo)
    {
        $this->sanear($entrada);
        $this->validar();
    }

    public function esValido(): bool
    {
        return $this->errores === [];
    }
    public function errores(): array
    {
        return $this->errores;
    }

    public function datos(): array
    {
        return $this->datos;
    }

    public function hayPortadaNueva(): bool
    {
        return $this->portadaTmpInfo !== [];
    }

    private function sanear(array &$entrada): void
    {
        $texto = static fn(mixed $v): string => is_string($v) ? trim(strip_tags($v)) : '';
        $this->datos = [
            'titulo'           => $texto($entrada['titulo'] ?? ''),
            'autor'            => $texto($entrada['autor'] ?? ''),
            'genero'           => $texto($entrada['genero'] ?? ''),
            'anio'             => $texto($entrada['anio'] ?? ''),
            'paginas'          => $texto($entrada['paginas'] ?? ''),
            'portada'          => $texto($entrada['portada'] ?? ''),
        ];
    }

    private function validar(): void
    {
        $d = $this->datos;
        if ($d['titulo'] === '') {
            $this->errores['titulo'] = 'El título es obligatorio.';
        }

        if ($d['autor'] === '') {
            $this->errores['autor'] = 'El autor es obligatorio.';
        } elseif (!preg_match('/^[\pL\s.\-\']{3,80}$/u', $d['autor'])) {
            $this->errores['autor'] = 'El autor solo puede tener letras';
        }

        if (!array_key_exists($d['genero'], Libro::GENEROS)) {
            $this->errores['genero'] = 'Elige un género válido.';
        }

        $anio = filter_var($d['anio'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' > 0],
        ]);
        if ($anio === false) {
            $this->errores['anio'] = 'Las páginas deben ser un número mayores que 0.';
        }

        $paginas = filter_var($d['paginas'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 2000],
        ]);
        if ($paginas === false) {
            $this->errores['paginas'] = 'Las páginas deben ser un número entero entre 1 y 2000.';
        }

    }

    private function validarPortada(array $archivo, bool $obligatoria): void
    {
        $error = $archivo['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_NO_FILE) {                 // no se ha elegido fichero
            if ($obligatoria) {
                $this->errores['portada'] = 'Debes subir una portada.';
            }
            return;                                          // al editar, no subir otra es válido
        }

        if ($error !== UPLOAD_ERR_OK) {
            $this->errores['portada'] = 'Error al subir el archivo (código ' . $error . ').';
        } elseif ($archivo['size'] > self::MAX_BYTES) {
            $this->errores['portada'] = 'La portada no puede superar 1 MB.';
        } elseif (!is_uploaded_file($archivo['tmp_name'])) {
            $this->errores['portada'] = 'Petición no válida.';
        } else {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($archivo['tmp_name']);

            if (!array_key_exists($mime, self::MIMES_PERMITIDOS)) {
                $this->errores['portada'] = 'Solo se permiten imágenes JPG, PNG o WEBP.';
            } else {
                $this->portadaTmpInfo = [
                    'tmp_name' => $archivo['tmp_name'],
                    'ext'      => self::MIMES_PERMITIDOS[$mime],
                ];
            }
        }
    }

}