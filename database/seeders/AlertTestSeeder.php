<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\Person;
use Illuminate\Database\Seeder;

class AlertTestSeeder extends Seeder
{
    private array $symptomSets = [
        ['Febre', 'Dor de cabeca', 'Dor no corpo', 'Fadiga', 'Calafrios'],
        ['Febre', 'Dor de barriga', 'Vomito', 'Diarreia', 'Fadiga'],
        ['Tosse', 'Dor de garganta', 'Coriza', 'Fadiga', 'Dor de cabeca'],
        ['Febre', 'Tosse', 'Dor no corpo', 'Dor de garganta', 'Calafrios'],
    ];

    public function run()
    {
        $diseases = Disease::all();

        if ($diseases->isEmpty()) {
            $this->call(DiseaseSeeder::class);
            $diseases = Disease::all();
        }

        $scenarios = [
            'Santa Zita' => 35,
            'Centro' => 24,
            'Limoeiro' => 21,
            'Bom Pastor' => 12,
        ];

        $sequence = 1;

        foreach ($scenarios as $neighborhood => $caseCount) {
            for ($i = 1; $i <= $caseCount; $i++) {
                $person = Person::updateOrCreate(
                    ['cpf' => $this->validCpfFromSeed(700000000 + $sequence)],
                    [
                        'name' => sprintf('Teste Alerta %s %02d', $neighborhood, $i),
                        'age' => 18 + ($sequence % 63),
                        'phone' => '3399' . str_pad((string) $sequence, 7, '0', STR_PAD_LEFT),
                        'neighborhood' => $neighborhood,
                        'zip_code' => '35300000',
                        'street' => 'Rua de Teste Epidemiologico',
                        'house_number' => (string) (100 + $i),
                        'housing_type' => $i % 2 === 0 ? 'casa' : 'apartamento',
                        'address_complement' => null,
                        'city' => 'Caratinga',
                        'state' => 'MG',
                        'user_id' => null,
                    ]
                );

                $disease = $diseases[($sequence - 1) % $diseases->count()];
                $symptoms = $this->symptomSets[($sequence - 1) % count($this->symptomSets)];

                Diagnosis::updateOrCreate(
                    [
                        'person_id' => $person->id,
                        'patient_notes' => 'Caso de teste para alerta epidemiologico.',
                    ],
                    [
                        'disease_id' => $disease->id,
                        'probability' => 65 + ($sequence % 31),
                        'symptoms' => $symptoms,
                        'neighborhood' => $neighborhood,
                        'alert_level' => $this->alertLevelForCount($caseCount),
                        'is_resolved' => false,
                        'resolved_at' => null,
                        'resolution_reason' => null,
                    ]
                );

                $sequence++;
            }
        }
    }

    private function alertLevelForCount(int $count): string
    {
        if ($count >= 30) {
            return 'critical';
        }

        if ($count >= 20) {
            return 'high';
        }

        if ($count >= 10) {
            return 'moderate';
        }

        return 'low';
    }

    private function validCpfFromSeed(int $seed): string
    {
        $base = substr(str_pad((string) $seed, 9, '0', STR_PAD_LEFT), -9);
        $firstDigit = $this->cpfDigit($base, 10);
        $secondDigit = $this->cpfDigit($base . $firstDigit, 11);

        return $base . $firstDigit . $secondDigit;
    }

    private function cpfDigit(string $numbers, int $weight): int
    {
        $sum = 0;

        for ($i = 0; $i < strlen($numbers); $i++) {
            $sum += (int) $numbers[$i] * ($weight - $i);
        }

        $digit = 11 - ($sum % 11);

        return $digit >= 10 ? 0 : $digit;
    }
}
