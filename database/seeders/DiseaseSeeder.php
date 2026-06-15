<?php

namespace Database\Seeders;

use App\Models\Disease;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $diseases = [
            [
                'name' => 'Gripe',
                'description' => 'Infeccao viral que afeta principalmente as vias respiratorias',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeca' => 8,
                    'Dor de barriga' => 2,
                    'Tosse' => 9,
                    'Vomito' => 3,
                    'Diarreia' => 2,
                    'Dor no corpo' => 9,
                    'Dor de garganta' => 7,
                    'Coriza' => 8,
                    'Fadiga' => 9,
                    'Calafrios' => 8,
                    'Perda de olfato' => 5,
                ],
            ],
            [
                'name' => 'Resfriado',
                'description' => 'Infeccao viral leve das vias respiratorias',
                'symptom_weights' => [
                    'Febre' => 4,
                    'Dor de cabeca' => 5,
                    'Dor de barriga' => 1,
                    'Tosse' => 7,
                    'Vomito' => 1,
                    'Diarreia' => 1,
                    'Dor no corpo' => 4,
                    'Dor de garganta' => 8,
                    'Coriza' => 9,
                    'Fadiga' => 6,
                    'Calafrios' => 3,
                    'Perda de olfato' => 6,
                ],
            ],
            [
                'name' => 'Dengue',
                'description' => 'Doenca viral transmitida por mosquitos',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeca' => 10,
                    'Dor de barriga' => 6,
                    'Tosse' => 2,
                    'Vomito' => 7,
                    'Diarreia' => 5,
                    'Dor no corpo' => 10,
                    'Dor de garganta' => 3,
                    'Coriza' => 1,
                    'Fadiga' => 9,
                    'Calafrios' => 8,
                    'Perda de olfato' => 1,
                ],
            ],
            [
                'name' => 'Gastroenterite',
                'description' => 'Inflamacao do estomago e intestinos',
                'symptom_weights' => [
                    'Febre' => 6,
                    'Dor de cabeca' => 4,
                    'Dor de barriga' => 10,
                    'Tosse' => 1,
                    'Vomito' => 9,
                    'Diarreia' => 10,
                    'Dor no corpo' => 5,
                    'Dor de garganta' => 1,
                    'Coriza' => 0,
                    'Fadiga' => 8,
                    'Calafrios' => 5,
                    'Perda de olfato' => 0,
                ],
            ],
            [
                'name' => 'COVID-19',
                'description' => 'Doenca causada pelo novo coronavirus',
                'symptom_weights' => [
                    'Febre' => 9,
                    'Dor de cabeca' => 7,
                    'Dor de barriga' => 4,
                    'Tosse' => 10,
                    'Vomito' => 4,
                    'Diarreia' => 5,
                    'Dor no corpo' => 8,
                    'Dor de garganta' => 6,
                    'Coriza' => 7,
                    'Fadiga' => 10,
                    'Calafrios' => 7,
                    'Perda de olfato' => 10,
                ],
            ],
            [
                'name' => 'Leptospirose',
                'description' => 'Infeccao bacteriana transmitida por roedores',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeca' => 9,
                    'Dor de barriga' => 8,
                    'Tosse' => 3,
                    'Vomito' => 8,
                    'Diarreia' => 7,
                    'Dor no corpo' => 10,
                    'Dor de garganta' => 2,
                    'Coriza' => 1,
                    'Fadiga' => 10,
                    'Calafrios' => 9,
                    'Perda de olfato' => 1,
                ],
            ],
            [
                'name' => 'Infeccao Intestinal',
                'description' => 'Infeccao das vias intestinais',
                'symptom_weights' => [
                    'Febre' => 7,
                    'Dor de cabeca' => 3,
                    'Dor de barriga' => 10,
                    'Tosse' => 0,
                    'Vomito' => 8,
                    'Diarreia' => 10,
                    'Dor no corpo' => 4,
                    'Dor de garganta' => 0,
                    'Coriza' => 0,
                    'Fadiga' => 7,
                    'Calafrios' => 4,
                    'Perda de olfato' => 0,
                ],
            ],
            [
                'name' => 'Intoxicacao Alimentar',
                'description' => 'Envenenamento causado por alimentos contaminados',
                'symptom_weights' => [
                    'Febre' => 4,
                    'Dor de cabeca' => 3,
                    'Dor de barriga' => 10,
                    'Tosse' => 0,
                    'Vomito' => 10,
                    'Diarreia' => 9,
                    'Dor no corpo' => 2,
                    'Dor de garganta' => 0,
                    'Coriza' => 0,
                    'Fadiga' => 6,
                    'Calafrios' => 2,
                    'Perda de olfato' => 0,
                ],
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::updateOrCreate(
                ['name' => $disease['name']],
                [
                    'description' => $disease['description'],
                    'symptom_weights' => $disease['symptom_weights'],
                ]
            );
        }
    }
}
