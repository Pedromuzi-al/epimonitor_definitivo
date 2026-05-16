<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCPF implements Rule
{
    /**
     * Cria uma nova instancia da regra.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina se a validacao passou.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Remove caracteres nao numericos.
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Verifica se possui 11 digitos.
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os digitos sao iguais.
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Valida o primeiro digito verificador.
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $primeiroDigito = 11 - ($soma % 11);
        $primeiroDigito = $primeiroDigito >= 10 ? 0 : $primeiroDigito;

        if ((int) $cpf[9] != $primeiroDigito) {
            return false;
        }

        // Valida o segundo digito verificador.
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $segundoDigito = 11 - ($soma % 11);
        $segundoDigito = $segundoDigito >= 10 ? 0 : $segundoDigito;

        return (int) $cpf[10] == $segundoDigito;
    }

    /**
     * Retorna a mensagem de erro da validacao.
     *
     * @return string
     */
    public function message()
    {
        return 'CPF invalido.';
    }
}
