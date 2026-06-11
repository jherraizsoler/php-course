<?php
/** @var array $modelos  @var array $sectores  @var array $sectoresFull  @var array $formatos  @var string $uploadsDir */
declare(strict_types=1);

/** Emoji del sector (de la ficha profesional), con respaldo. */
$icono = fn(string $s): string => $sectoresFull[$s]['icono'] ?? '📦';
?>
<header class="hero" style="text-align:left; padding:8px 0 0">
    <span class="kicker"><span class="dot"></span> Parte 4 · Visor 3D / volumétrico</span>
    <h1 style="font-size:clamp(1.7rem,4.5vw,2.4rem)">🧊 Catálogo de modelos</h1>
    <p style="margin-top:6px; max-width:60ch">
        Sube y visualiza modelos 3D en el navegador. <strong>Three.js</strong> para mallas de superficie
        (<code>.stl</code>, <code>.glb</code>, <code>.obj</code>) y <strong>vtk.js</strong> para datos
        científicos/volumétricos (<code>.vtp</code>, <code>.vti</code>, <code>.vtk</code>). El backend
        en <strong>PHP</strong> gestiona la subida, el catálogo (MySQL) y el servido de cada modelo.
    </p>
</header>

<div class="toolbar">
    <a class="btn run" href="index.php?action=subir" style="text-decoration:none">⬆️ Subir modelo</a>
    <a class="btn open" href="index.php?action=usos" style="text-decoration:none">🎯 Usos por sector</a>
    <span class="meta"><?= count($modelos) ?> modelo(s) en el catálogo</span>
</div>

<?php if (! $modelos): ?>
    <section class="panel">
        <p>Todavía no hay modelos. Empieza subiendo uno con el botón de arriba
           (o ejecuta <code>tools/db-setup.php</code> para cargar el de ejemplo).</p>
    </section>
<?php else: ?>
    <div class="grid">
        <?php foreach ($modelos as $m):
            [$tipo] = $formatos[$m['formato']] ?? ['malla'];
            $esVol   = $tipo === 'volumetrico';
            $fileOk  = is_file($uploadsDir . '/' . basename($m['archivo']));
            $haThumb = ! empty($m['thumbnail']) && is_file($uploadsDir . '/' . basename((string) $m['thumbnail']));
            $thumbUrl = $haThumb ? 'index.php?action=thumb&id=' . (int) $m['id'] : null;
        ?>
            <article class="card">
                <a class="card-link" href="index.php?action=ver&id=<?= (int) $m['id'] ?>" aria-label="Ver <?= e($m['nombre']) ?>"></a>
                <?php if ($thumbUrl): ?>
                    <div class="thumb" style="padding:0; overflow:hidden;">
                        <img src="<?= e($thumbUrl) ?>" alt="Miniatura de <?= e($m['nombre']) ?>"
                             style="width:100%; height:100%; object-fit:cover; border-radius:12px; display:block;">
                    </div>
                <?php else: ?>
                    <div class="thumb"><?= $icono($m['sector']) ?></div>
                <?php endif; ?>
                <div class="row">
                    <span class="fmt-chip <?= $esVol ? 'vol' : '' ?>"><?= e($m['formato']) ?></span>
                    <span class="meta"><?= e(bytes_legibles((int) $m['bytes'])) ?></span>
                    <?php if (! $fileOk): ?>
                        <span class="fmt-chip"
                              style="background:rgba(234,179,8,.14);color:#ca8a04;border-color:rgba(234,179,8,.3);"
                              title="El archivo físico no está en el servidor">⚠️ sin archivo</span>
                    <?php endif; ?>
                </div>
                <h3><?= e($m['nombre']) ?></h3>
                <p><?= e($m['descripcion'] ?? '') ?: '<span style="opacity:.6">Sin descripción</span>' ?></p>
                <div class="card-foot">
                    <span class="meta"><?= e($sectores[$m['sector']] ?? $m['sector']) ?></span>
                    <?php if (! $fileOk && ! empty($m['fuente'])): ?>
                        <a class="go" href="<?= e($m['fuente']) ?>" target="_blank" rel="noopener noreferrer"
                           title="Descargar desde la fuente original" style="text-decoration:none">⬇️ Descargar</a>
                    <?php else: ?>
                        <span class="go">Ver →</span>
                    <?php endif; ?>
                </div>
                <form method="post" action="index.php?action=eliminar&id=<?= (int) $m['id'] ?>"
                      onsubmit="return confirm('¿Eliminar «<?= e($m['nombre']) ?>»? No se puede deshacer.')"
                      style="margin-top:10px">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="btn open" style="font-size:.78rem; padding:6px 12px; pointer-events:auto">🗑️ Eliminar</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>