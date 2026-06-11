#!/usr/bin/env python3
"""
Basílica del Pilar (Zaragoza) — maqueta 3D mejorada.

Escala ~1:1 en metros.  Genera  proyectos/visor-3d/uploads/basilica-pilar.stl
Ejecutar desde la raíz del proyecto:
    python tools/gen-pilar.py
"""
import math, struct, os

_T = []   # colector de triángulos

# ── Conversión Z-up → Y-up (Three.js usa Y como eje vertical) ────────────────
def _yup(p):
    """(x, y_horiz, z_vertical) → (x, z_vertical, y_horiz)"""
    return (p[0], p[2], p[1])

# ── Colector ─────────────────────────────────────────────────────────────────
def tri(a, b, c):
    # _yup es una reflexión → invertir winding para mantener normales hacia afuera
    _T.append((_yup(a), _yup(c), _yup(b)))

def quad(a, b, c, d):
    tri(a, b, c); tri(a, c, d)

# ── Caja (normales hacia afuera, winding verificado) ─────────────────────────
def box(ox, oy, oz, sx, sy, sz):
    x0, x1 = ox, ox + sx
    y0, y1 = oy, oy + sy
    z0, z1 = oz, oz + sz
    quad((x0,y0,z0),(x0,y1,z0),(x1,y1,z0),(x1,y0,z0))  # bot  −Z
    quad((x0,y0,z1),(x1,y0,z1),(x1,y1,z1),(x0,y1,z1))  # top  +Z
    quad((x0,y0,z0),(x1,y0,z0),(x1,y0,z1),(x0,y0,z1))  # front−Y
    quad((x1,y1,z0),(x0,y1,z0),(x0,y1,z1),(x1,y1,z1))  # back +Y
    quad((x0,y1,z0),(x0,y0,z0),(x0,y0,z1),(x0,y1,z1))  # left −X
    quad((x1,y0,z0),(x1,y1,z0),(x1,y1,z1),(x1,y0,z1))  # right+X

# ── Anillo poligonal ──────────────────────────────────────────────────────────
def ring(cx, cy, z, r, n=8, a0=0.0):
    return [(cx + r*math.cos(a0 + 2*math.pi*i/n),
             cy + r*math.sin(a0 + 2*math.pi*i/n), z)
            for i in range(n)]

# ── Caras laterales entre dos anillos ─────────────────────────────────────────
def ring_sides(bot, top):
    n = len(bot)
    for i in range(n):
        j = (i+1) % n
        quad(bot[i], bot[j], top[j], top[i])

# ── Tapa en abanico ───────────────────────────────────────────────────────────
def ring_cap(center, pts, flip=False):
    n = len(pts)
    for i in range(n):
        j = (i+1) % n
        if flip: tri(center, pts[j], pts[i])
        else:    tri(center, pts[i], pts[j])

# ── Prisma / cilindro poligonal ───────────────────────────────────────────────
def prism(cx, cy, z0, r, h, n=12, a0=0.0, cap_bot=True, cap_top=False):
    bot = ring(cx, cy, z0,   r, n, a0)
    top = ring(cx, cy, z0+h, r, n, a0)
    ring_sides(bot, top)
    if cap_bot: ring_cap((cx, cy, z0),   bot, flip=True)
    if cap_top: ring_cap((cx, cy, z0+h), top)
    return top

# ── Aguja piramidal ───────────────────────────────────────────────────────────
def pyramid(base_ring, apex):
    n = len(base_ring)
    for i in range(n):
        j = (i+1) % n
        tri(base_ring[i], base_ring[j], apex)

# ── Cúpula elipsoidal ─────────────────────────────────────────────────────────
def dome(cx, cy, z0, r, h, nlat=10, nlon=16, a0=0.0):
    """Semiesfera aplastada (r = radio base, h = altura)."""
    for i in range(nlat):
        la0 = math.pi/2 * i / nlat
        la1 = math.pi/2 * (i+1) / nlat
        for j in range(nlon):
            lo0 = a0 + 2*math.pi * j / nlon
            lo1 = a0 + 2*math.pi * (j+1) / nlon
            def p(la, lo):
                return (cx + r*math.cos(la)*math.cos(lo),
                        cy + r*math.cos(la)*math.sin(lo),
                        z0 + h*math.sin(la))
            v00, v01 = p(la0,lo0), p(la0,lo1)
            v10, v11 = p(la1,lo0), p(la1,lo1)
            if i == nlat-1:
                tri(v00, v01, (cx, cy, z0+h))
            else:
                quad(v00, v01, v11, v10)

# ── Cupulín completo ──────────────────────────────────────────────────────────
def cupulin(cx, cy, z0, r_drum, h_drum, r_dome, h_dome, r_lan=None, h_lan=4.0):
    prism(cx, cy, z0, r_drum, h_drum, n=16)
    dome(cx, cy, z0+h_drum, r_dome, h_dome, nlat=8, nlon=16)
    if r_lan:
        z_l  = z0 + h_drum + h_dome
        lan  = prism(cx, cy, z_l, r_lan, h_lan, n=8)
        pyramid(lan, (cx, cy, z_l + h_lan + r_lan*1.3))

# ── Torre característica de la Basílica del Pilar ────────────────────────────
def torre_pilar(cx, cy, z0, r_fuste, h_fuste,
                r_dome, h_dome, r_lan, h_lan, taper=0.84):
    """
    Torre octagonal con:
      · Fuste ahusado con balcón corrido a media altura
      · Tambor de transición circular
      · Cúpula ovalada (estilo churrigueresco)
      · Linterna octagonal con aguja
    """
    a0 = math.pi / 8  # rotar 22.5° para que las caras queden de frente

    # Fuste inferior (60 % de la altura)
    h1   = h_fuste * 0.60
    bot  = ring(cx, cy, z0,    r_fuste,          8, a0)
    mid  = ring(cx, cy, z0+h1, r_fuste*taper,    8, a0)
    ring_sides(bot, mid)
    ring_cap((cx, cy, z0), bot, flip=True)

    # Balcón corrido (anillo saliente ~12 % más ancho)
    h_bal  = h_fuste * 0.042
    r_bal  = r_fuste * taper * 1.13
    bal_b  = ring(cx, cy, z0+h1,        r_bal, 8, a0)
    bal_t  = ring(cx, cy, z0+h1+h_bal,  r_bal, 8, a0)
    ring_sides(mid,   bal_b)   # rampa de subida al vuelo del balcón
    ring_sides(bal_b, bal_t)
    ring_cap((cx, cy, z0+h1), bal_b, flip=True)   # suelo del balcón

    # Fuste superior (del balcón al remate)
    h2       = h_fuste - h1 - h_bal
    top_fus  = ring(cx, cy, z0+h1+h_bal+h2, r_fuste*taper*0.79, 8, a0)
    ring_sides(bal_t, top_fus)

    # Tambor de transición (16 caras, ensanche para la cúpula)
    z_td  = z0 + h_fuste
    r_td  = r_dome * 1.10
    h_td  = h_dome * 0.24
    td_t  = prism(cx, cy, z_td, r_td, h_td, n=16, cap_bot=False)

    # Cúpula
    dome(cx, cy, z_td+h_td, r_dome, h_dome, nlat=10, nlon=20)

    # Linterna con aguja
    z_l  = z_td + h_td + h_dome
    lan  = prism(cx, cy, z_l, r_lan, h_lan, n=8, a0=a0)
    pyramid(lan, (cx, cy, z_l + h_lan + r_lan*1.7))


# ═══════════════════════════════════════════════════════════════════════════════
#  ENSAMBLADO — Basílica del Pilar
# ═══════════════════════════════════════════════════════════════════════════════

L, W   = 130.0, 65.0    # largo (X) × ancho (Y) de la nave
H_nave = 30.0            # altura de la nave
cx_c   = L / 2           # centro X
cy_c   = W / 2           # centro Y

# ── Basamento escalonado ──────────────────────────────────────────────────────
box(-6,  -6,  0.0, L+12, W+12, 1.0)    # plataforma exterior
box(-3,  -3,  1.0, L+6,  W+6,  1.5)    # escalón intermedio
box( 0,   0,  2.5, L,    W,    0.5)    # pavimento base

# ── Cuerpo de la nave ─────────────────────────────────────────────────────────
box(0, 0, 3.0, L, W, H_nave)

# Pilastras en los flancos (añaden detalle y sombra)
for px in [14, 35, 57, 73, 95, 116]:
    for side_y, side_d in [(-1.8, 1.8), (W, 1.8)]:
        box(px-2.2, side_y, 3.0, 4.4, side_d, H_nave * 0.88)

# ── Cuatro torres de esquina ──────────────────────────────────────────────────
for tx, ty in [(11, 11), (L-11, 11), (11, W-11), (L-11, W-11)]:
    torre_pilar(tx, ty, z0=0,
                r_fuste=7.5, h_fuste=58,
                r_dome=5.8,  h_dome=9.5,
                r_lan=1.8,   h_lan=4.5)

# ── Cimborrio central (el mayor, más alto que las torres de esquina) ──────────
# Tambor decorativo sobre la cubierta de la nave
z_nave_top = 3.0 + H_nave
prism(cx_c, cy_c, z_nave_top, 14, 3.5, n=16, cap_bot=False, cap_top=False)
# Torre central
torre_pilar(cx_c, cy_c, z0=0,
            r_fuste=10.5, h_fuste=52,
            r_dome=9.5,   h_dome=14,
            r_lan=3.0,    h_lan=7,
            taper=0.87)

# ── Diez cupulines (2×4 a lo largo + 2 en los extremos) ──────────────────────
z_cup = z_nave_top
for sx in [22, 46, 84, 108]:
    for sy in [cy_c - 18, cy_c + 18]:
        cupulin(sx, sy, z_cup, 3.0, 2.5, 4.5, 6.5, r_lan=1.3, h_lan=3.0)
for sx in [14, L-14]:
    cupulin(sx, cy_c, z_cup, 3.0, 2.5, 4.5, 6.5, r_lan=1.3, h_lan=3.0)


# ═══════════════════════════════════════════════════════════════════════════════
#  ESCRITURA STL BINARIO
# ═══════════════════════════════════════════════════════════════════════════════

def normal3(a, b, c):
    ax, ay, az = b[0]-a[0], b[1]-a[1], b[2]-a[2]
    bx, by, bz = c[0]-a[0], c[1]-a[1], c[2]-a[2]
    nx = ay*bz - az*by
    ny = az*bx - ax*bz
    nz = ax*by - ay*bx
    l  = math.sqrt(nx*nx + ny*ny + nz*nz) or 1.0
    return nx/l, ny/l, nz/l

root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
out  = os.path.join(root, 'proyectos', 'visor-3d', 'uploads', 'basilica-pilar.stl')

hdr = b'Basilica del Pilar Zaragoza - maqueta esquematica mejorada v2'
with open(out, 'wb') as f:
    f.write(hdr + b'\x00' * (80 - len(hdr)))
    f.write(struct.pack('<I', len(_T)))
    for a, b, c in _T:
        n = normal3(a, b, c)
        f.write(struct.pack('<3f', *n))
        f.write(struct.pack('<3f', *a))
        f.write(struct.pack('<3f', *b))
        f.write(struct.pack('<3f', *c))
        f.write(struct.pack('<H', 0))

kb = os.path.getsize(out) / 1024
print(f'✓  {out}')
print(f'   {len(_T):,} triángulos  ·  {kb:.0f} KB')
