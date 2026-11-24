<?php
declare(strict_types=1);

namespace Controllers;

use Core\Pages;
use Models\Libro;
use Models\LibroRespository;
use Requests\LibroRequest;

/** Controlador del modelo Libro. */
final class LibroController
{
    /** Muestra el formulario de alta. (YA HECHO) */
    public function create(): void
    {
        new Pages()->render('libro/formulario');
    }

    /** Procesa el alta de un libro. */
    public function store(): void
    {
        // TODO (apartado 4): crear new LibroReq($_POST, $_FILES).
        //      Si no es válido -> render('libro/formulario') con 'errores' y 'datos', y return.
        //
        // TODO (apartado 5):
        //      1. Generar el nombre único de la imagen (todavía no se mueve).
        //      2. Crear el objeto Libro con ese nombre de portada.
        //      3. Si existe() -> render('libro/formulario') con $errores['general'], y return.
        //      4. Mover la imagen a RUTA_IMG.
        //
        // TODO (apartado 6):
        //      guardar() -> render('libro/formulario') con 'mensaje' y 'libro'.
        //      Si falla -> borrar la imagen que ya se había movido y mostrar un error.

        $request = new LibroRequest($_POST['libro'] ?? [], $_FILES['portada'] ?? [], true);

        if (!$request->esValido()) {
            $this->render('libros/formulario', [
                'datos'   => $request->datos(),
                'errores' => $request->errores(),
            ]);
            return;
        }

        $nombrePortada = $this->moverPortada($request->portadaTmpInfo());
        $libro = $this->repository->create($request->datos(), $nombrePortada);

        $this->mostrarListadoConMensaje('Libro "' . $libro->getTitulo() . '" añadido correctamente.');
    }
    private function render(string $vista, array $variables = []): void
    {
        extract($variables);
        require BASE_PATH . '/Views/layout/header.php';
        require BASE_PATH . '/Views/' . $vista . '.php';
        require BASE_PATH . '/Views/layout/footer.php';
    }
    private function mostrarListadoConMensaje(string $mensaje): void
    {
        $libros = $this->repository->findAll();
        $titulo = 'Todos los libros';
        $q = '';
        $this->render('libros/listado', compact('libros', 'titulo', 'q', 'mensaje'));
    }

    private function moverPortada(array $info): string
    {
        if (!is_dir(self::DIR_IMG) && !mkdir(self::DIR_IMG, 0755, true)) {
            throw new \RuntimeException('No se pudo crear la carpeta de imágenes');
        }

        //$nombre = uniqid('portada_') . '.' . $info['ext'];

        if (!move_uploaded_file($info['tmp_name'], self:: DIR_IMG . $nombre)) {
            throw new \RuntimeException('No se pudo guardar la portada');
        }
        return $nombre;
    }


    /** Muestra el catálogo. */
    public function index(): void
    {
        $libros = [];

        // TODO (apartado 3): rellenar $libros leyendo RUTA_BIBLIOTECA línea por línea
        //      (un array de objetos Libro).

        $repository = new LibroRespository();
        $libros = $repository->findAll();
        new Pages()->render('libro/catalogo', ['libros' => $libros]);
    }


}
