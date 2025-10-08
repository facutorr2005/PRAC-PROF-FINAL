<?php
namespace App\Lib;

function generar_codigo_6(): string {
    return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function hash_codigo(string $codigo): string {
    return password_hash($codigo, PASSWORD_BCRYPT);
}
