<?php

function convertirFechaHora($fechaHora)
{
    // formato de fecha y hora: 12:00 pm - 12/12/2012
    return date('d/m/Y h:i a', strtotime($fechaHora));
}

//
