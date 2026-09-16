<?php

const DB_HOST = 'localhost';
const DB_NAME = 'synapsetech_db';
const DB_USER = 'root';
const DB_PASSWORD = '';
const RUTA_DOCUMENTOS = __DIR__ . '/../documentos/';

function responder($datos, $codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

function limpiar($valor)
{
    return trim((string)$valor);
}

