<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Core\Country;
use App\Models\Core\DocumentType;
use App\Models\Core\Person;

class CoreSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedCountries();
        $this->seedDocumentTypes();
        $this->seedGenders();
        $this->seedPaymentMethods();
    }

    private function seedCountries(): void
    {
        Country::create(
            [
                'code' => 'PE',
                'name' => 'Perú'
            ]
        );
    }

    private function seedDocumentTypes(): void
    {
        $documentTypes = [
            ['code' => '0', 'name' => 'Otros'],
            ['code' => '1', 'name' => 'DNI'],
            ['code' => '4', 'name' => 'Carnet de Extranjería'],
            ['code' => '6', 'name' => 'RUC'],
            ['code' => '7', 'name' => 'Pasaporte'],
        ];

        foreach ($documentTypes as $docType) {
            DocumentType::create($docType);
        }
    }

    private function seedGenders(): void
    {

        $genders = [
            ['code' => '1', 'name' => 'Masculino'],
            ['code' => '2', 'name' => 'Femenino'],
            ['code' => '3', 'name' => 'No binario'],
            ['code' => '9', 'name' => 'Prefiero no decirlo'],
        ];

        foreach ($genders as $gender) {
            \App\Models\Core\Gender::create($gender);
        }
    }
    private function seedPaymentMethods(): void
    {

        $paymentMethods = [
            ['name' => 'Efectivo', 'type' => 'cash', 'is_default' => true, 'is_active' => true],
            ['name' => 'Bancarizado', 'type' => 'bank', 'is_default' => false, 'is_active' => true],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            \App\Models\Core\PaymentMethods::create($paymentMethod);
        }
    }
}
