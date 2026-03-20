<?php

// database/seeders/PaymentMethodSeeder.php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::create([
            'method_name' => 'Dólares Efectivo',
            'contact_info' => 'Información de contacto de Dólares Efectivo',
        ]);

        PaymentMethod::create([
            'method_name' => 'Bs. Efectivo',
            'contact_info' => 'Información de contacto de Bolívares Efectivo',
        ]);

        PaymentMethod::create([
            'method_name' => 'Pago Movil',
            'contact_info' => 'Pago Móvil Telf.: 0414-6368644 Banco de Venezuela, CI.: 14657371',
        ]);

        PaymentMethod::create([
            'method_name' => 'Transferencia Bancaria',
            'contact_info' => 'Banco de Venezuela Cuenta Corriente 0102 0216 79000 1892741 CI.: 14657371',
        ]);

        PaymentMethod::create([
            'method_name' => 'Zelle',
            'contact_info' => 'Zelle: Monto Minimo a Transferir $20 email: dortega77.cc@gmail.com',
        ]);
    }
}
