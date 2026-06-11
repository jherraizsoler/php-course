<?php
/** @var array $sectoresFull  @var array $formatos */
declare(strict_types=1);

/** ¿Este sector incluye datos volumétricos? (para el color del chip de tipo) */
$tieneVol = function (array $s) use ($formatos): bool {
    foreach ($s['formatos'] as $ext) {
        if (($formatos[$ext][0] ?? 'malla') === 'volumetrico') return true;
    }
    return false;
};
?>
<header class="hero" style="text-align:left; padding:8px 0 0">
    <span class="kicker"><span class="dot"></span> Visor 3D · Aplicaciones profesionales</span>
    <h1 style="font-size:clamp(1.8rem,5vw,2.6rem)">Usos por sector</h1>
    <p style="margin-top:8px; max-width:70ch">
        El mismo visor sirve a campos muy distintos. La diferencia está en el <strong>tipo de dato</strong>
        y en las <strong>métricas</strong> que cada sector necesita extraer. Aquí tienes, por sector, los
        casos de uso reales y el análisis de datos que aporta valor.
    </p>
</header>

<section class="panel" style="margin-top:14px">
    <div class="col2">
        <div>
            <h3 style="margin:0 0 6px">🧊 Mallas de superficie <span class="tipo-chip">Three.js</span></h3>
            <p class="meta" style="display:block">
                La "piel" del objeto (triángulos): escáneres, piezas CAD, modelos para impresión.
                Formatos <code>.stl</code>, <code>.glb</code>, <code>.obj</code>. Métricas geométricas:
                dimensiones, superficie, volumen, integridad de malla.
            </p>
        </div>
        <div>
            <h3 style="margin:0 0 6px">🩻 Datos volumétricos <span class="tipo-chip vol">vtk.js</span></h3>
            <p class="meta" style="display:block">
                El interior del objeto, voxel a voxel: TAC/RM, sísmica, resultados de simulación.
                Formatos <code>.vti</code>, <code>.vtp</code>, <code>.vtk</code>. Métricas de campo:
                densidades, intensidades, umbrales, segmentación.
            </p>
        </div>
    </div>
</section>

<div class="sector-grid">
    <?php foreach ($sectoresFull as $clave => $s):
        if ($clave === 'otro') continue;          // "Otro" no aporta como caso de uso destacado
        $esVol = $tieneVol($s); ?>
        <article class="sector-card">
            <div class="sector-head">
                <span class="sector-ico"><?= $s['icono'] ?></span>
                <div>
                    <h3><?= e($s['nombre']) ?></h3>
                    <span class="tipo-chip <?= $esVol ? 'vol' : '' ?>"><?= e($s['tipo']) ?></span>
                </div>
            </div>
            <p class="sector-resumen"><?= e($s['resumen']) ?></p>

            <div class="col2">
                <div>
                    <h4 class="col-title">🎯 Casos de uso</h4>
                    <ul class="ulist">
                        <?php foreach ($s['casos'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="col-title">📊 Métricas y análisis</h4>
                    <ul class="ulist metric">
                        <?php foreach ($s['metricas'] as $m): ?><li><?= e($m) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="sector-foot">
                <span class="meta">Formatos:</span>
                <?php foreach ($s['formatos'] as $ext):
                    $v = ($formatos[$ext][0] ?? 'malla') === 'volumetrico'; ?>
                    <span class="fmt-chip <?= $v ? 'vol' : '' ?>"><?= e($ext) ?></span>
                <?php endforeach; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<section class="panel" style="margin-top:24px; text-align:center">
    <h3 style="margin:0 0 8px">¿Tienes un modelo de tu sector?</h3>
    <p class="meta" style="display:block; margin-bottom:14px">Súbelo y el visor calculará sus métricas geométricas al instante.</p>
    <a class="btn run" href="index.php?action=subir" style="text-decoration:none">⬆️ Subir un modelo</a>
    <a class="btn open" href="index.php" style="text-decoration:none">📚 Ver el catálogo</a>
</section>
