<?php
class ValidationHelper {
    public static function validateProduct($name, $price, $description) {
        $errors = [];
        if (empty($name)) {
            $errors[] = "O nome do produto é obrigatório.";
        }
        if (!is_numeric($price) || $price <= 0) {
            $errors[] = "O preço deve ser um número positivo.";
        }
        if (strlen($description) > 500) {
            $errors[] = "A descrição não pode ter mais de 500 caracteres.";
        }
        return $errors;
    }

    // Outros métodos de validação conforme necessário