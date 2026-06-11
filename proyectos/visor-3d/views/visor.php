<?php
/** @var array $m  @var string $tipo  @var array $sectores  @var bool $fileExists */
declare(strict_types=1);

$fileUrl = 'index.php?action=archivo&id=' . (int) $m['id'];
$esVol   = $tipo === 'volumetrico';
?>
<header class="hero" style="text-align:left; padding:8px 0 0">
    <span class="kicker"><span class="dot"></span> <?= $esVol ? 'vtk.js · volumétrico' : 'Three.js · malla de superficie' ?></span>
    <h1 style="font-size:clamp(1.5rem,4vw,2.1rem)"><?= e($m['nombre']) ?></h1>
</header>

<?php if (! $fileExists): /* ============ ARCHIVO NO DISPONIBLE ============ */ ?>
<section class="panel" style="text-align:center; padding:40px 24px;">
    <div style="font-size:3rem; margin-bottom:16px;">📂</div>
    <h3 style="margin:0 0 10px">Archivo no disponible en el servidor</h3>
    <p class="meta" style="display:block; margin-bottom:20px; max-width:48ch; margin-inline:auto;">
        El archivo <code><?= e($m['nombre_orig']) ?></code> no está almacenado localmente.
        <?php if (! empty($m['fuente'])): ?>
            Puedes descargarlo desde la fuente original, subirlo con
            <a href="index.php?action=subir">⬆️ Subir modelo</a> y volver a visitarlo.
        <?php else: ?>
            Sube el archivo con <a href="index.php?action=subir">⬆️ Subir modelo</a> para visualizarlo.
        <?php endif; ?>
    </p>
    <?php if (! empty($m['fuente'])): ?>
        <a class="btn run" href="<?= e($m['fuente']) ?>" target="_blank" rel="noopener noreferrer"
           style="text-decoration:none; display:inline-flex; gap:8px;">
            ⬇️ Descargar desde la fuente
        </a>
    <?php endif; ?>
</section>
<?php else: /* ========================== VISOR ========================== */ ?>

<div class="viewer" id="viewer">
    <div class="loading" id="loading">⏳ Cargando modelo…</div>
</div>

<?php endif; /* $fileExists */ ?>

<div class="toolbar">
    <a class="btn open" href="index.php" style="text-decoration:none">← Volver al catálogo</a>
    <?php if ($fileExists): ?>
    <a class="btn open" href="<?= e($fileUrl) ?>" download="<?= e($m['nombre_orig']) ?>" style="text-decoration:none">⬇️ Descargar archivo</a>
    <?php elseif (! empty($m['fuente'])): ?>
    <a class="btn open" href="<?= e($m['fuente']) ?>" target="_blank" rel="noopener noreferrer" style="text-decoration:none">⬇️ Descargar desde fuente</a>
    <?php endif; ?>
    <span class="bg-ctl">
        <label for="bg-color">🎨 Fondo:</label>
        <input type="color" id="bg-color" value="#0e1224">
        <button type="button" class="btn open" id="bg-reset" style="padding:7px 12px;font-size:.78rem">Según tema</button>
    </span>
</div>

<section class="panel">
    <h3 style="margin:0 0 10px">📊 Métricas del modelo <span class="meta" style="font-weight:400">· calculadas en tu navegador</span></h3>
    <div class="stats">
        <?php if (! $esVol): ?>
            <div class="stat pending"><div class="v" id="st-tris">—</div><div class="k">Triángulos</div></div>
            <div class="stat pending"><div class="v dim" id="st-dims">—</div><div class="k">Dimensiones (u)</div></div>
            <div class="stat pending"><div class="v" id="st-area">—</div><div class="k">Superficie (u²)</div></div>
            <div class="stat pending"><div class="v" id="st-vol">—</div><div class="k">Volumen (u³)</div></div>
        <?php else: ?>
            <div class="stat pending"><div class="v dim" id="st-vdims">—</div><div class="k">Dimensiones</div></div>
            <div class="stat pending"><div class="v" id="st-range">—</div><div class="k">Rango escalar</div></div>
            <div class="stat pending"><div class="v" id="st-count">—</div><div class="k">Vóxeles / puntos</div></div>
        <?php endif; ?>
    </div>
    <p class="meta" style="margin-top:12px">
        Unidades del propio modelo (<code>u</code>): el archivo no lleva escala física.
        🖱️ Arrastra para rotar · rueda para zoom · clic derecho para desplazar.
    </p>
</section>

<section class="panel">
    <table class="tbl">
        <tr><td>Formato</td><td><span class="fmt-chip <?= $esVol ? 'vol' : '' ?>"><?= e($m['formato']) ?></span> · <?= $esVol ? 'vtk.js' : 'Three.js' ?></td></tr>
        <tr><td>Sector</td><td><?= e($sectores[$m['sector']] ?? $m['sector']) ?></td></tr>
        <tr><td>Archivo</td><td><?= e($m['nombre_orig']) ?> (<?= e(bytes_legibles((int) $m['bytes'])) ?>)</td></tr>
        <tr><td>Subido</td><td><?= e($m['subido_en']) ?></td></tr>
        <?php if (! empty($m['descripcion'])): ?><tr><td>Descripción</td><td><?= e($m['descripcion']) ?></td></tr><?php endif; ?>
        <?php if (! empty($m['fuente'])): ?><tr><td>Fuente</td><td><a href="<?= e($m['fuente']) ?>" target="_blank" rel="noopener noreferrer">🔗 <?= e($m['fuente']) ?></a></td></tr><?php endif; ?>
    </table>
</section>

<section class="panel">
    <h3 style="margin:0 0 4px"><?= $ficha['icono'] ?> <?= e($ficha['nombre']) ?></h3>
    <p class="meta" style="display:block; margin-bottom:14px"><?= e($ficha['resumen']) ?></p>
    <div class="col2">
        <div>
            <h4 class="col-title">🎯 Casos de uso</h4>
            <ul class="ulist"><?php foreach ($ficha['casos'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?></ul>
        </div>
        <div>
            <h4 class="col-title">📊 Métricas típicas del sector</h4>
            <ul class="ulist metric"><?php foreach ($ficha['metricas'] as $mt): ?><li><?= e($mt) ?></li><?php endforeach; ?></ul>
        </div>
    </div>
    <p class="meta" style="margin-top:14px"><a href="index.php?action=usos">Ver todos los usos por sector →</a></p>
</section>

<script>
/* Control de fondo del visor (común a Three.js y vtk.js).
 * - Por defecto sigue el tema (oscuro/claro) y reacciona al cambiarlo.
 * - El usuario puede elegir cualquier color; su elección se guarda (localStorage). */
(function () {
    const input = document.getElementById('bg-color');
    const reset = document.getElementById('bg-reset');
    const KEY = 'v3d-bg';
    const themeBg = () => document.documentElement.dataset.theme === 'light' ? '#eef2f9' : '#181b2e';
    const current = () => localStorage.getItem(KEY) || themeBg();

    window.__applyViewerBg = () => {};   // lo redefine el motor cuando está listo
    function refresh() {
        const hex = current();
        input.value = hex;
        window.__applyViewerBg(hex);
    }
    window.__refreshViewerBg = refresh;   // el motor lo llama al inicializarse

    input.addEventListener('input', () => { localStorage.setItem(KEY, input.value); window.__applyViewerBg(input.value); });
    reset.addEventListener('click', () => { localStorage.removeItem(KEY); refresh(); });
    // Si el usuario no ha fijado color, seguir el tema cuando cambie.
    new MutationObserver(() => { if (!localStorage.getItem(KEY)) refresh(); })
        .observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

    // Convierte "#rrggbb" → [r,g,b] en 0..1 (lo usa vtk.js).
    window.__hexToRgb01 = (hex) => {
        const n = parseInt(hex.replace('#', ''), 16);
        return [(n >> 16 & 255) / 255, (n >> 8 & 255) / 255, (n & 255) / 255];
    };
    input.value = current();
})();
</script>


<script>
/* Captura miniatura del canvas tras el primer render y la envía al servidor. */
(function () {
    const _id   = <?= (int) $m['id'] ?>;
    const _csrf = <?= json_encode(csrf_token()) ?>;
    window.__captureThumb = function () {
        const cv = document.querySelector('#viewer canvas');
        if (!cv) return;
        try {
            const dataUrl = cv.toDataURL('image/jpeg', 0.85);
            const fd = new FormData();
            fd.append('csrf', _csrf);
            fd.append('data', dataUrl);
            fetch('index.php?action=thumbnail&id=' + _id, { method: 'POST', body: fd }).catch(() => {});
        } catch (e) { /* canvas bloqueado */ }
    };
})();
</script>

<?php if ($fileExists): /* ==================== SCRIPTS 3D ==================== */ ?>
<?php if (! $esVol): /* ============================== MALLAS: Three.js ===== */ ?>
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { STLLoader }     from 'three/addons/loaders/STLLoader.js';
import { OBJLoader }     from 'three/addons/loaders/OBJLoader.js';
import { GLTFLoader }    from 'three/addons/loaders/GLTFLoader.js';
import { FBXLoader }     from 'three/addons/loaders/FBXLoader.js';

const box = document.getElementById('viewer');
const loading = document.getElementById('loading');
const fmt = <?= json_encode($m['formato']) ?>;
const url = <?= json_encode($fileUrl) ?>;

const scene = new THREE.Scene();
const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, preserveDrawingBuffer: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setSize(box.clientWidth, box.clientHeight);
box.appendChild(renderer.domElement);

// Fondo controlable (por defecto sigue el tema; ver el control de fondo arriba).
window.__applyViewerBg = (hex) => renderer.setClearColor(new THREE.Color(hex), 1);
if (window.__refreshViewerBg) window.__refreshViewerBg();

const camera = new THREE.PerspectiveCamera(50, box.clientWidth / box.clientHeight, 0.01, 5000);
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping    = true;
controls.dampingFactor    = 0.07;
controls.screenSpacePanning = true;            // panning derecho más natural
controls.maxPolarAngle    = Math.PI * 0.92;    // impide dar la vuelta por el polo

scene.add(new THREE.HemisphereLight(0xffffff, 0x444466, 1.1));
const dir = new THREE.DirectionalLight(0xffffff, 1.4);
dir.position.set(1, 1.5, 1);
scene.add(dir);
const grid = new THREE.GridHelper(10, 20, 0x6366f1, 0x2a2a40);
grid.material.opacity = 0.35; grid.material.transparent = true;
scene.add(grid);

// Formato compacto de número y volcado a un recuadro de métrica.
function nf(n) {
    if (!isFinite(n)) return '—';
    const a = Math.abs(n);
    if (a !== 0 && (a >= 1e6 || a < 1e-2)) return n.toExponential(2);
    if (a >= 100) return n.toFixed(0);
    return n.toFixed(2);
}
function setStat(id, val) {
    const el = document.getElementById(id);
    if (el) { el.textContent = val; el.closest('.stat').classList.remove('pending'); }
}

// Métricas geométricas: nº de triángulos, área de superficie y volumen (signo de
// la fórmula del tetraedro respecto al origen — válido para mallas cerradas).
function computeStats(object3d, size) {
    object3d.updateWorldMatrix(true, true);
    let tris = 0, area = 0, vol = 0;
    const a = new THREE.Vector3(), b = new THREE.Vector3(), c = new THREE.Vector3();
    const ab = new THREE.Vector3(), ac = new THREE.Vector3(), cr = new THREE.Vector3(), bc = new THREE.Vector3();
    object3d.traverse(o => {
        if (!o.isMesh || !o.geometry || !o.geometry.attributes.position) return;
        const pos = o.geometry.attributes.position, idx = o.geometry.index, m = o.matrixWorld;
        const n = idx ? idx.count : pos.count;
        for (let i = 0; i < n; i += 3) {
            const i0 = idx ? idx.getX(i) : i, i1 = idx ? idx.getX(i + 1) : i + 1, i2 = idx ? idx.getX(i + 2) : i + 2;
            a.fromBufferAttribute(pos, i0).applyMatrix4(m);
            b.fromBufferAttribute(pos, i1).applyMatrix4(m);
            c.fromBufferAttribute(pos, i2).applyMatrix4(m);
            ab.subVectors(b, a); ac.subVectors(c, a); cr.crossVectors(ab, ac);
            area += 0.5 * cr.length();
            bc.crossVectors(b, c); vol += a.dot(bc) / 6;
            tris++;
        }
    });
    setStat('st-tris', tris.toLocaleString('es'));
    setStat('st-dims', `${nf(size.x)} × ${nf(size.y)} × ${nf(size.z)}`);
    setStat('st-area', nf(area));
    setStat('st-vol', nf(Math.abs(vol)));
}

// Encadra cualquier objeto: lo centra y ajusta la cámara a su tamaño.
function frame(object3d) {
    scene.add(object3d);                                // añadir primero para que la bbox sea correcta
    object3d.updateWorldMatrix(true, true);
    const bbox   = new THREE.Box3().setFromObject(object3d);
    const size   = bbox.getSize(new THREE.Vector3());
    const center = bbox.getCenter(new THREE.Vector3());
    object3d.position.sub(center);                     // centrar en el origen

    const radius = Math.max(size.x, size.y, size.z) || 1;
    grid.scale.setScalar(radius / 5);
    // Posición inicial: ligera elevación frontal, nunca mirando desde el suelo.
    const dist = radius * 1.8;
    camera.position.set(dist * 0.9, dist * 0.5, dist);
    camera.near = radius / 200; camera.far = radius * 200; camera.updateProjectionMatrix();
    controls.target.set(0, 0, 0);
    controls.minDistance = radius * 0.05;
    controls.maxDistance = radius * 15;
    controls.update();
    loading.style.display = 'none';
    try { computeStats(object3d, size); } catch (e) { /* métricas best-effort */ }
    setTimeout(window.__captureThumb, 1500);   // miniatura automática
}
function fail(msg) { loading.textContent = '⚠️ ' + msg; }

const material = new THREE.MeshStandardMaterial({ color: 0x9aa6ff, metalness: 0.25, roughness: 0.55, flatShading: false, side: THREE.DoubleSide });

try {
    if (fmt === 'stl') {
        new STLLoader().load(url, g => { g.computeVertexNormals(); frame(new THREE.Mesh(g, material)); },
            undefined, () => fail('No se pudo cargar el STL.'));
    } else if (fmt === 'obj') {
        new OBJLoader().load(url, obj => {
            obj.traverse(c => { if (c.isMesh) c.material = material; });
            frame(obj);
        }, undefined, () => fail('No se pudo cargar el OBJ.'));
    } else if (fmt === 'glb' || fmt === 'gltf') {
        // GLTFLoader sirve para .glb (binario) y .gltf (texto). El .gltf solo carga bien
        // si es autocontenido: con buffers/texturas embebidos (data URIs). Si referencia
        // archivos externos, no los encontrará al servirse como archivo único.
        new GLTFLoader().load(url, gltf => frame(gltf.scene),
            undefined, () => fail('No se pudo cargar el ' + fmt.toUpperCase() + '. Si es .gltf, debe ser autocontenido.'));
    } else if (fmt === 'fbx') {
        new FBXLoader().load(url, obj => {
            obj.traverse(c => { if (c.isMesh && !c.material) c.material = material; });
            frame(obj);
        }, undefined, () => fail('No se pudo cargar el FBX.'));
    } else {
        fail('Formato de malla no reconocido.');
    }
} catch (err) { fail('Error al inicializar el visor.'); }

addEventListener('resize', () => {
    renderer.setSize(box.clientWidth, box.clientHeight);
    camera.aspect = box.clientWidth / box.clientHeight;
    camera.updateProjectionMatrix();
});
(function loop() { requestAnimationFrame(loop); controls.update(); renderer.render(scene, camera); })();
</script>

<?php else: /* ============================== VOLUMÉTRICO: vtk.js ===== */ ?>
<!-- vtk.js fijado a 32.9.0: la versión "latest" (36.x) rompió el build UMD global (window.vtk). -->
<script src="https://unpkg.com/vtk.js@32.9.0/vtk.js"></script>
<script>
(function () {
    const box = document.getElementById('viewer');
    const loading = document.getElementById('loading');
    const fmt = <?= json_encode($m['formato']) ?>;
    const url = <?= json_encode($fileUrl) ?>;
    const fail = (msg) => { loading.textContent = '⚠️ ' + msg; };
    const setStat = (id, val) => { const el = document.getElementById(id); if (el) { el.textContent = val; el.closest('.stat').classList.remove('pending'); } };

    if (!window.vtk) { fail('No se pudo cargar vtk.js (revisa tu conexión).'); return; }

    try {
        const grw = vtk.Rendering.Misc.vtkGenericRenderWindow.newInstance({ background: [0.04, 0.04, 0.08] });
        grw.setContainer(box);
        grw.resize();
        const renderer = grw.getRenderer();
        const renderWindow = grw.getRenderWindow();

        // Fondo controlable (por defecto sigue el tema; ver el control de fondo arriba).
        window.__applyViewerBg = (hex) => {
            const c = window.__hexToRgb01(hex);
            renderer.setBackground(c[0], c[1], c[2]);
            renderWindow.render();
        };
        if (window.__refreshViewerBg) window.__refreshViewerBg();

        // .vti → datos de imagen (volumen). .vtp/.vtk → malla poligonal (superficie).
        if (fmt === 'vti') {
            const reader = vtk.IO.XML.vtkXMLImageDataReader.newInstance();
            reader.setUrl(url).then(() => {
                const data = reader.getOutputData();
                const range = data.getPointData().getScalars().getRange();
                const dims = data.getDimensions();
                setStat('st-vdims', dims.join(' × '));
                setStat('st-range', range[0].toFixed(1) + ' – ' + range[1].toFixed(1));
                setStat('st-count', (dims[0] * dims[1] * dims[2]).toLocaleString('es'));
                const mapper = vtk.Rendering.Core.vtkVolumeMapper.newInstance();
                mapper.setInputData(data);
                mapper.setSampleDistance(0.6);
                const actor = vtk.Rendering.Core.vtkVolume.newInstance();
                actor.setMapper(mapper);

                const lo = range[0], hi = range[1], span = (hi - lo) || 1;
                // Color: fondo oscuro → estructuras densas claras (estilo TAC).
                const ctf = vtk.Rendering.Core.vtkColorTransferFunction.newInstance();
                ctf.addRGBPoint(lo,                0.05, 0.05, 0.12);
                ctf.addRGBPoint(lo + 0.55 * span,  0.55, 0.60, 0.78);
                ctf.addRGBPoint(hi,                1.00, 0.98, 0.92);
                // Opacidad: fondo/tejido blando TRANSPARENTE; opaco solo en valores altos.
                const otf = vtk.Common.DataModel.vtkPiecewiseFunction.newInstance();
                otf.addPoint(lo,                0.0);
                otf.addPoint(lo + 0.45 * span,  0.0);
                otf.addPoint(lo + 0.70 * span,  0.12);
                otf.addPoint(lo + 0.90 * span,  0.45);
                otf.addPoint(hi,                0.90);

                const prop = actor.getProperty();
                prop.setRGBTransferFunction(0, ctf);
                prop.setScalarOpacity(0, otf);
                prop.setScalarOpacityUnitDistance(0, 1.5);
                prop.setInterpolationTypeToLinear();
                // Opacidad por gradiente: regiones homogéneas casi transparentes, bordes opacos
                // → quita la "niebla" del fondo y resalta las superficies (paredes de los vasos).
                prop.setUseGradientOpacity(0, true);
                prop.setGradientOpacityMinimumValue(0, 0);
                prop.setGradientOpacityMinimumOpacity(0, 0.0);
                prop.setGradientOpacityMaximumValue(0, 0.12 * span);
                prop.setGradientOpacityMaximumOpacity(0, 1.0);
                // Iluminación para dar volumen/profundidad.
                prop.setShade(true);
                prop.setAmbient(0.3);
                prop.setDiffuse(0.7);
                prop.setSpecular(0.3);
                prop.setSpecularPower(8.0);

                renderer.addVolume(actor);
                renderer.resetCamera();
                renderWindow.render();
                loading.style.display = 'none';
                setTimeout(window.__captureThumb, 1500);   // miniatura automática
            }).catch(() => fail('No se pudo cargar el .vti.'));
        } else {
            const Reader = fmt === 'vtp'
                ? vtk.IO.XML.vtkXMLPolyDataReader
                : vtk.IO.Legacy.vtkPolyDataReader;
            const reader = Reader.newInstance();
            reader.setUrl(url).then(() => {
                const data = reader.getOutputData();
                const b = data.getBounds();   // [xmin,xmax,ymin,ymax,zmin,zmax]
                setStat('st-vdims', (b[1]-b[0]).toFixed(1) + ' × ' + (b[3]-b[2]).toFixed(1) + ' × ' + (b[5]-b[4]).toFixed(1));
                const scal = data.getPointData() && data.getPointData().getScalars();
                setStat('st-range', scal ? (scal.getRange()[0].toFixed(1) + ' – ' + scal.getRange()[1].toFixed(1)) : '—');
                setStat('st-count', data.getNumberOfPoints().toLocaleString('es'));
                const mapper = vtk.Rendering.Core.vtkMapper.newInstance();
                mapper.setInputConnection(reader.getOutputPort());
                const actor = vtk.Rendering.Core.vtkActor.newInstance();
                actor.setMapper(mapper);
                actor.getProperty().setColor(0.6, 0.65, 1.0);
                renderer.addActor(actor);
                renderer.resetCamera();
                renderWindow.render();
                loading.style.display = 'none';
                setTimeout(window.__captureThumb, 1500);   // miniatura automática
            }).catch(() => fail('No se pudo cargar el ' + fmt + '.'));
        }

        addEventListener('resize', () => grw.resize());
    } catch (err) { fail('Error al inicializar vtk.js.'); }
})();
</script>
<?php endif; ?>
<?php endif; /* $fileExists */ ?>
