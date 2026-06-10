<?php
/**
 * Ejercicio 3: interfaz de pago con varias implementaciones.
 * Es el patrón real que usa un CRM profesional con Omnipay para Stripe/PayPal.
 */

declare(strict_types=1);

interface PasarelaPago
{
    public function cobrar(float $importe): bool;
    public function nombre(): string;
}

class Stripe implements PasarelaPago
{
    public function cobrar(float $importe): bool
    {
        echo "Cobrando {$importe}€ vía Stripe...\n";
        return true;
    }
    public function nombre(): string { return 'Stripe'; }
}

class PayPal implements PasarelaPago
{
    public function cobrar(float $importe): bool
    {
        echo "Cobrando {$importe}€ vía PayPal...\n";
        return true;
    }
    public function nombre(): string { return 'PayPal'; }
}

// El "checkout" no sabe ni le importa qué pasarela es: solo usa la interfaz.
function procesarPago(PasarelaPago $pasarela, float $importe): void
{
    echo "Procesando con {$pasarela->nombre()}\n";
    $ok = $pasarela->cobrar($importe);
    echo $ok ? "✅ Pago correcto\n\n" : "❌ Pago fallido\n\n";
}

procesarPago(new Stripe(), 49.99);
procesarPago(new PayPal(), 19.95);
