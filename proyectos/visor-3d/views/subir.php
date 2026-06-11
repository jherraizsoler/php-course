<?php
/** @var string $error  @var array $sectores  @var string $exts  @var int $maxMb */
declare(strict_types=1);
?>
<header class="hero" style="text-align:left; padding:8px 0 0">
    <span class="kicker"><span class="dot"></span> Catálogo · Nuevo modelo</span>
    <h1 style="font-size:clamp(1.6rem,4.5vw,2.2rem)">⬆️ Subir modelo 3D</h1>
</header>

<section class="panel">
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <form method="post" action="index.php?action=subir" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <div class="field">
            <label>Nombre</label>
            <input type="text" name="nombre" maxlength="150" required
                   value="<?= e($_POST['nombre'] ?? '') ?>" placeholder="Ej. Pieza de motor v2">
        </div>

        <div class="field">
            <label>Descripción <span style="font-weight:400;opacity:.7">(opcional)</span></label>
            <textarea name="descripcion" rows="3" maxlength="500"
                      placeholder="¿Qué es y de dónde viene?"><?= e($_POST['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label>Fuente / origen <span style="font-weight:400;opacity:.7">(opcional, URL)</span></label>
            <input type="url" name="fuente" maxlength="255"
                   value="<?= e($_POST['fuente'] ?? '') ?>" placeholder="https://… (de dónde procede el modelo)">
        </div>

        <div class="field">
            <label>Sector</label>
            <select name="sector">
                <?php foreach ($sectores as $val => $etq): ?>
                    <option value="<?= e($val) ?>" <?= (($_POST['sector'] ?? '') === $val) ? 'selected' : '' ?>><?= e($etq) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label>Archivo del modelo</label>
            <input type="file" name="modelo" accept="<?= e($exts) ?>" required>
            <p class="meta" style="margin-top:8px">
                Formatos: <strong><?= e($exts) ?></strong> · máx. <strong><?= e($maxMb) ?> MB</strong>.
                Mallas (<code>.stl/.glb/.gltf/.obj/.fbx</code>) → Three.js · volumétrico (<code>.vtp/.vti/.vtk</code>) → vtk.js.
                <br>El <code>.gltf</code> debe ser autocontenido (buffers/texturas embebidos).
            </p>
        </div>

        <div class="toolbar">
            <button type="submit" class="btn run" style="border:none;cursor:pointer">Subir y añadir al catálogo</button>
            <a class="btn open" href="index.php" style="text-decoration:none">Cancelar</a>
        </div>
    </form>
</section>
