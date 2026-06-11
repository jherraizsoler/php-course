<?php
/**
 * Catálogo de SECTORES con su "ficha profesional": para qué sirve el visor 3D
 * en cada campo, casos de uso reales y qué métricas / análisis de datos aportan.
 *
 * Es la fuente única de verdad: de aquí salen el selector de subida, los iconos
 * del catálogo, la página "Usos por sector" y las métricas sugeridas en el visor.
 *
 * Cada sector:
 *   icono     emoji representativo
 *   label     etiqueta corta (con icono) para selects y chips
 *   nombre    nombre completo
 *   tipo      naturaleza del dato ('Malla', 'Volumétrico' o ambos)
 *   resumen   una línea de contexto
 *   casos[]   casos de uso concretos
 *   metricas[] métricas / análisis de datos típicos del sector
 *   formatos[] formatos habituales (claves de config 'formatos')
 */

declare(strict_types=1);

return [
    'dental' => [
        'icono'    => '🦷',
        'label'    => '🦷 Odontología',
        'nombre'   => 'Odontología / Dental',
        'tipo'     => 'Malla (escáner intraoral) + Volumétrico (CBCT)',
        'resumen'  => 'Del escáner intraoral al TAC dental: planificación protésica y ortodóncica sobre el modelo real del paciente.',
        'casos'    => [
            'Planificación de implantes dentales',
            'Diseño CAD/CAM de coronas, puentes y prótesis',
            'Ortodoncia y alineadores invisibles',
            'Análisis de oclusión y mordida',
            'Guías quirúrgicas impresas en 3D',
        ],
        'metricas' => [
            'Mediciones lineales y angulares (mm / °)',
            'Volumen y densidad ósea (Unidades Hounsfield)',
            'Hueso disponible y grosor de mucosa',
            'Espacio interoclusal y puntos de contacto',
            'Simetría de arcada y línea media',
        ],
        'formatos' => ['stl', 'vti'],
    ],

    'cirugia' => [
        'icono'    => '🩺',
        'label'    => '🩺 Cirugía',
        'nombre'   => 'Cirugía / Planificación quirúrgica',
        'tipo'     => 'Volumétrico (TAC/RM) + Malla',
        'resumen'  => 'Reconstrucción 3D a partir de TAC/RM para planificar la intervención antes de entrar a quirófano.',
        'casos'    => [
            'Planificación preoperatoria',
            'Guías de corte y plantillas personalizadas',
            'Modelos anatómicos impresos para ensayo',
            'Simulación de trayectorias de abordaje',
            'Comunicación y consentimiento del paciente',
        ],
        'metricas' => [
            'Volumen de tumor o lesión (cm³)',
            'Márgenes de resección',
            'Distancia a estructuras críticas (vasos, nervios)',
            'Segmentación de tejidos por densidad',
            'Ángulos y longitudes de la trayectoria',
        ],
        'formatos' => ['vti', 'stl'],
    ],

    'radiologia' => [
        'icono'    => '☢️',
        'label'    => '☢️ Radiología',
        'nombre'   => 'Radiología / Imagen médica',
        'tipo'     => 'Volumétrico (TAC, RM, PET)',
        'resumen'  => 'Exploración volumétrica de estudios DICOM con renderizado por funciones de color y opacidad.',
        'casos'    => [
            'Render volumétrico diagnóstico',
            'Reconstrucción multiplanar (MPR)',
            'Cuantificación de lesiones',
            'Seguimiento evolutivo entre estudios',
            'Apoyo a segunda opinión',
        ],
        'metricas' => [
            'Densidad radiológica (HU)',
            'Volúmenes por umbral / segmentación',
            'Intensidad de señal (RM)',
            'Captación metabólica (SUV en PET)',
            'Histogramas de intensidad',
        ],
        'formatos' => ['vti', 'vtk'],
    ],

    'ortopedia' => [
        'icono'    => '🦴',
        'label'    => '🦴 Ortopedia',
        'nombre'   => 'Ortopedia y prótesis',
        'tipo'     => 'Malla + Volumétrico',
        'resumen'  => 'Del hueso escaneado a la prótesis a medida: geometría, alineación y planificación de implantes.',
        'casos'    => [
            'Diseño de prótesis e implantes a medida',
            'Planificación de osteotomías',
            'Análisis de alineación y ejes articulares',
            'Plantillas quirúrgicas',
            'Estudios biomecánicos',
        ],
        'metricas' => [
            'Ejes y ángulos articulares',
            'Longitud y simetría de miembros',
            'Volumen y calidad ósea',
            'Áreas de contacto articular',
            'Desviación respecto al plan quirúrgico',
        ],
        'formatos' => ['stl', 'vti'],
    ],

    'ingenieria' => [
        'icono'    => '⚙️',
        'label'    => '⚙️ Ingeniería',
        'nombre'   => 'Ingeniería (FEA / CFD)',
        'tipo'     => 'Volumétrico (resultados) + Malla',
        'resumen'  => 'Visualización de resultados de simulación: campos de tensión, flujo y temperatura sobre la malla.',
        'casos'    => [
            'Análisis de tensiones (FEA)',
            'Dinámica de fluidos (CFD)',
            'Validación de diseño',
            'Detección de puntos críticos',
            'Optimización topológica',
        ],
        'metricas' => [
            'Tensión de von Mises (MPa)',
            'Factor de seguridad',
            'Deformación / desplazamiento',
            'Velocidad y presión del fluido',
            'Gradientes térmicos y mapas de calor',
        ],
        'formatos' => ['vtp', 'vti', 'vtk'],
    ],

    'geologia' => [
        'icono'    => '⛰️',
        'label'    => '⛰️ Geología',
        'nombre'   => 'Geología / Geociencia',
        'tipo'     => 'Volumétrico (sísmica) + superficies',
        'resumen'  => 'Modelos 3D de subsuelo y volúmenes sísmicos para exploración y caracterización de reservorios.',
        'casos'    => [
            'Modelado de reservorios',
            'Interpretación sísmica 3D',
            'Estimación de recursos',
            'Análisis estructural y de fallas',
            'Geotecnia',
        ],
        'metricas' => [
            'Porosidad y permeabilidad',
            'Volumen de roca / mineral',
            'Leyes de mineral y ley de corte',
            'Isosuperficies por umbral',
            'Gradientes y horizontes',
        ],
        'formatos' => ['vti', 'vtp'],
    ],

    'impresion' => [
        'icono'    => '🖨️',
        'label'    => '🖨️ Impresión 3D',
        'nombre'   => 'Impresión 3D / Fabricación',
        'tipo'     => 'Malla',
        'resumen'  => 'Preparación y verificación de modelos antes de imprimir: integridad de malla y estimación de material.',
        'casos'    => [
            'Preparación de impresión (slicing)',
            'Verificación de malla (manifold / agujeros)',
            'Estimación de material y coste',
            'Control dimensional',
            'Ingeniería inversa',
        ],
        'metricas' => [
            'Volumen (cm³ → g de material)',
            'Área de superficie',
            'Dimensiones (caja envolvente)',
            'Número de triángulos',
            'Errores de malla (no-manifold, agujeros)',
        ],
        'formatos' => ['stl', 'obj', 'glb'],
    ],

    'arquitectura' => [
        'icono'    => '🏛️',
        'label'    => '🏛️ Arquitectura',
        'nombre'   => 'Arquitectura / Patrimonio',
        'tipo'     => 'Malla',
        'resumen'  => 'Modelos de edificación y digitalización de patrimonio por fotogrametría o escaneo láser.',
        'casos'    => [
            'Revisión de diseño y BIM',
            'Digitalización de patrimonio',
            'Documentación y conservación',
            'Recorridos virtuales',
            'Detección de interferencias',
        ],
        'metricas' => [
            'Superficies y volúmenes (m² / m³)',
            'Volúmenes de obra y mediciones',
            'Distancias y cotas',
            'Desviación respecto a proyecto',
            'Densidad de malla / nube de puntos',
        ],
        'formatos' => ['glb', 'obj', 'stl'],
    ],

    'otro' => [
        'icono'    => '📦',
        'label'    => '📦 Otro',
        'nombre'   => 'Otros usos',
        'tipo'     => 'Malla / Volumétrico',
        'resumen'  => 'Educación, investigación, arte o prototipado: cualquier uso fuera de los sectores anteriores.',
        'casos'    => [
            'Educación y divulgación',
            'Investigación',
            'Arte y diseño',
            'Prototipado rápido',
        ],
        'metricas' => [
            'Dimensiones (caja envolvente)',
            'Volumen y área de superficie',
            'Número de elementos / triángulos',
        ],
        'formatos' => ['stl', 'obj', 'glb', 'vtp', 'vti', 'vtk'],
    ],
];
