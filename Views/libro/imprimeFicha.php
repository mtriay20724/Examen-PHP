<article class="ficha">
    <?php if($libros->getImagen() !== ''): ?>
        <img src="<?= BASE_URL ?>img/<?= htmlspecialchars($libros->getImagen()) ?>">
    <?php else: ?>
    Sin foto
    <?php endif; ?>
    <h3><strong>Titulo:</strong><?= htmlspecialchars($libros->getTitulo()) ?></h3>
    <p class="autor">Por <?= htmlspecialchars($libros->getAutor()) ?></p>
    <span class="genero"><strong>Genero:</strong> <?= htmlspecialchars($libros->getGenero()) ?></span>
</article>