<?php

function convertirFechaHora($fechaHora)
{
    // formato de fecha y hora: 12:00 pm - 12/12/2012
    return date('d/m/Y h:i a', strtotime($fechaHora));
}

function getIdTrasladoExterno()
{
    return 8; // id de traslado externo
}

function getIdConceptoPagoInscripcion()
{
    return [
        1, // id de concepto de pago de inscripcion
        8 // id de concepto de pago de inscripcion para traslado externo
    ];
}

//
