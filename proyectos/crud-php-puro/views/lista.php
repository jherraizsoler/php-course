<h1>📋 Mis tareas</h1>

<p><a class="btn" href="index.php?action=crear">+ Nueva tarea</a></p>

<?php if (empty($tareas)): ?>
    <p>No hay tareas todavía. ¡Crea la primera!</p>
<?php else: ?>
    <ul>
        <?php foreach ($tareas as $tarea): ?>
            <li>
                <a href="index.php?action=completar&id=<?= (int) $tarea['id'] ?>"
                   title="Marcar como hecha/pendiente">
                    <?= $tarea['completada'] ? '✅' : '⬜' ?>
                </a>
                <span class="<?= $tarea['completada'] ? 'hecha' : '' ?>">
                    <?= e($tarea['titulo']) ?>
                </span>
                <span class="acciones">
                    <a href="index.php?action=editar&id=<?= (int) $tarea['id'] ?>">Editar</a>
                    <a href="index.php?action=eliminar&id=<?= (int) $tarea['id'] ?>"
                       onclick="return confirm('¿Eliminar esta tarea?')">Eliminar</a>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
