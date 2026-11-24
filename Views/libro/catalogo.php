<h1>Catálogo</h1>

<?php  // TODO (apartado 3): recibe la variable $libros (array de objetos Libro).
// Recórrela y pinta cada ficha reutilizando imprimeFicha.php.
// Si no hay libros, muestra «Todavía no hay libros en la biblioteca».
?>

<?php if (empty($libros)): ?>
    <p>Todavia no hay libros</p>
<?php endif; ?>
<table border="1" cellpadding="6">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Genero</th>
        <th>Año</th>
        <th>Paginas</th>
        <th>Portada</th>
    </tr>

    <?php foreach ($libros as $libro): ?>
        <tr>
            <td><a href="<?= BASE_URL ?>libro/<?= $libro->getTitulo() ?>"><?= htmlspecialchars($libro->getTitulo()) ?></a></td>
            <td><?= htmlspecialchars($libro->getAutor()) ?></td>
            <td><?= $libro->getPaginas() ?></td>
            <td><?= $libro->getAnio() ?></td>
            <td><?= $libro->getPaginas() ?></td>
            <td>
                <?php if ($libro->getPortada() !== ''): ?>
                    <img src="<?= BASE_URL ?>img/<?= htmlspecialchars($libro->getPortada()) ?>" width="80" alt="">
                <?php else: ?>
                    Sin foto
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>


