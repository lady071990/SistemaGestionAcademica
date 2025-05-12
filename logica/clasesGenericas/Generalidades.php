<?php


class Generalidades
{
    public static function getEstadoUsuario($idEstado)
    {
        return $idEstado == 1 ? 'Activo' : 'Inactivo';
    }

    public static function convertDate($datetime, $convert)
    {
        $fechaInicio = new DateTime($datetime);
        $date = '';
        if ($convert) {
            $date = $fechaInicio->format('Y-m-d H:i:s');
        } else {
            $date = $fechaInicio->format('d-m-Y');
        }
        return $date;
    }

    public static function getTooltip($accion, $texto)
    {
        switch ($accion) {
            case 1:
                return "<span class='as-tooltip'><i class='fas fa-edit'></i> <span class='as-tooltiptext-left'>Editar</span> </span>";
                break;
            case 2:
                return "<span class='as-tooltip'><i class='fas fa-trash'></i> <span class='as-tooltiptext-left'>Eliminar</span> </span>";
                break;
            case 3:
                $texto = $texto == '' ? 'Agregar' : $texto;
                return "<span class='as-tooltip'><i class='fas fa-plus-circle'></i> <span class='as-tooltiptext-left'>" . $texto . "</span> </span>";
                break;

            case 4:
                $texto = $texto == '' ? 'Agregar' : $texto;
                return "<span class='as-tooltip'><i class='fas fa-check'></i> <span class='as-tooltiptext-left'>" . $texto . "</span> </span>";
                break;

            case 5:
                $texto = $texto == '' ? 'Agregar' : $texto;
                return "<span class='as-tooltip'><i class='fas fa-print'></i> <span class='as-tooltiptext-left'>" . $texto . "</span> </span>";
                break;

            default:
                return "<span class='as-tooltip'><i class='fas fa-edit'></i> <span class='as-tooltiptext'>Otra</span> </span>";
                break;
        }
    }

    public static function getReduceCharacters($texto)
    {
        return substr($texto, 0, 20);
    }

    public static function getCountCharacters($texto)
    {
        return strlen($texto);
    }
}
