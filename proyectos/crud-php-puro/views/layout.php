<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestor de tareas — PHP puro</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; max-width: 640px; margin: 40px auto; padding: 0 16px; color: #222; }
        h1 { border-bottom: 2px solid #4f46e5; padding-bottom: 8px; }
        a { color: #4f46e5; }
        .btn { display: inline-block; padding: 8px 14px; background: #4f46e5; color: #fff;
               text-decoration: none; border-radius: 6px; border: none; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { display: flex; align-items: center; gap: 10px; padding: 10px;
             border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 8px; }
        .hecha { text-decoration: line-through; color: #9ca3af; }
        .acciones { margin-left: auto; display: flex; gap: 10px; font-size: 14px; }
        .error { background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; }
        input[type=text] { padding: 8px; width: 100%; margin: 8px 0; }
        .course-nav { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 22px; }
        .course-nav a { display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
            font-size: 14px; font-weight: 600; color: #4f46e5; padding: 7px 13px;
            border: 1px solid #c7d2fe; border-radius: 8px; background: #eef2ff; transition: all .2s; }
        .course-nav a:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }
    </style>
</head>
<body>
    <nav class="course-nav">
        <a href="../../index.php">🏠 Inicio del curso</a>
        <a href="../../modulo.php?m=proyectos">← Proyectos</a>
    </nav>
    <?= $contenido ?>
</body>
</html>
