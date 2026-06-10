<?php $editando = $tarea !== null; ?>

<h1><?= $editando ? '✏️ Editar tarea' : '➕ Nueva tarea' ?></h1>

<?php if (!empty($error)): ?>
    <p class="error">⚠️ <?= e($error) ?></p>
<?php endif; ?>

<form method="post"
      action="index.php?action=<?= $editando ? 'editar&id=' . (int) $tarea['id'] : 'crear' ?>">
    <label>
        Título de la tarea:
        <input type="text" name="titulo" autofocus
               value="<?= e($editando ? $tarea['titulo'] : ($_POST['titulo'] ?? '')) ?>">
    </label>
    <button class="btn" type="submit"><?= $editando ? 'Guardar' : 'Crear' ?></button>
    <a href="index.php">Cancelar</a>
</form>
