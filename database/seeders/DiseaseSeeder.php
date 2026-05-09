<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;

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
                'description' => 'Infecção viral que afeta principalmente as vias respiratórias',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeça' => 8,
                    'Dor de barriga' => 2,
                    'Tosse' => 9,
                    'Vômito' => 3,
                    'Diarreia' => 2,
                    'Dor no corpo' => 9,
                    'Dor de garganta' => 7,
                    'Coriza' => 8,
                    'Fadiga' => 9,
                    'Calafrios' => 8,
                    'Perda de olfato' => 5,
                ]
            ],
            [
                'name' => 'Resfriado',
                'description' => 'Infecção viral leve das vias respiratórias',
                'symptom_weights' => [
                    'Febre' => 4,
                    'Dor de cabeça' => 5,
                    'Dor de barriga' => 1,
                    'Tosse' => 7,
                    'Vômito' => 1,
                    'Diarreia' => 1,
                    'Dor no corpo' => 4,
                    'Dor de garganta' => 8,
                    'Coriza' => 9,
                    'Fadiga' => 6,
                    'Calafrios' => 3,
                    'Perda de olfato' => 6,
                ]
            ],
            [
                'name' => 'Dengue',
                'description' => 'Doença viral transmitida por mosquitos',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeça' => 10,
                    'Dor de barriga' => 6,
                    'Tosse' => 2,
                    'Vômito' => 7,
                    'Diarreia' => 5,
                    'Dor no corpo' => 10,
                    'Dor de garganta' => 3,
                    'Coriza' => 1,
                    'Fadiga' => 9,
                    'Calafrios' => 8,
                    'Perda de olfato' => 1,
                ]
            ],
            [
                'name' => 'Gastroenterite',
                'description' => 'Inflamação do estômago e intestinos',
                'symptom_weights' => [
                    'Febre' => 6,
                    'Dor de cabeça' => 4,
                    'Dor de barriga' => 10,
                    'Tosse' => 1,
                    'Vômito' => 9,
                    'Diarreia' => 10,
                    'Dor no corpo' => 5,
                    'Dor de garganta' => 1,
                    'Coriza' => 0,
                    'Fadiga' => 8,
                    'Calafrios' => 5,
                    'Perda de olfato' => 0,
                ]
            ],
            [
                'name' => 'COVID-19',
                'description' => 'Doença causada pelo novo coronavírus',
                'symptom_weights' => [
                    'Febre' => 9,
                    'Dor de cabeça' => 7,
                    'Dor de barriga' => 4,
                    'Tosse' => 10,
                    'Vômito' => 4,
                    'Diarreia' => 5,
                    'Dor no corpo' => 8,
                    'Dor de garganta' => 6,
                    'Coriza' => 7,
                    'Fadiga' => 10,
                    'Calafrios' => 7,
                    'Perda de olfato' => 10,
                ]
            ],
            [
                'name' => 'Leptospirose',
                'description' => 'Infecção bacteriana transmitida por roedores',
                'symptom_weights' => [
                    'Febre' => 10,
                    'Dor de cabeça' => 9,
                    'Dor de barriga' => 8,
                    'Tosse' => 3,
                    'Vômito' => 8,
                    'Diarreia' => 7,
                    'Dor no corpo' => 10,
                    'Dor de garganta' => 2,
                    'Coriza' => 1,
                    'Fadiga' => 10,
                    'Calafrios' => 9,
                    'Perda de olfato' => 1,
                ]
            ],
            [
                'name' => 'Infecção Intestinal',
                'description' => 'Infecção das vias intestinais',
                'symptom_weights' => [
                    'Febre' => 7,
                    'Dor de cabeça' => 3,
                    'Dor de barriga' => 10,
                    'Tosse' => 0,
                    'Vômito' => 8,
                    'Diarreia' => 10,
                    'Dor no corpo' => 4,
                    'Dor de garganta' => 0,
                    'Coriza' => 0,
                    'Fadiga' => 7,
                    'Calafrios' => 4,
                    'Perda de olfato' => 0,
                ]
            ],
            [
                'name' => 'Intoxicação Alimentar',
                'description' => 'Envenenamento causado por alimentos contaminados',
                'symptom_weights' => [
                    'Febre' => 4,
                    'Dor de cabeça' => 3,
                    'Dor de barriga' => 10,
                    'Tosse' => 0,
                    'Vômito' => 10,
                    'Diarreia' => 9,
                    'Dor no corpo' => 2,
                    'Dor de garganta' => 0,
                    'Coriza' => 0,
                    'Fadiga' => 6,
                    'Calafrios' => 2,
                    'Perda de olfato' => 0,
                ]
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::create([
                'name' => $disease['name'],
                'description' => $disease['description'],
                'symptom_weights' => json_encode($disease['symptom_weights']),
            ]);
        }
    }
}
