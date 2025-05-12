<?php

class MenuLista
{
    public static function getMenu($rol)
    {
        $listaMenu = '';
        $totalMenus = Menu::getListaEnObjetos(null, 'posicion');

        // Agrupar menús por ID para evitar duplicados
        $menusProcesados = [];

        foreach ($totalMenus as $menu) {
            if ($menu->getTipo() == 1 && !isset($menusProcesados[$menu->getId()])) {
                $listaMenu .= self::construirHijos($menu, $rol);
                $menusProcesados[$menu->getId()] = true;
            }
        }

        return $listaMenu;
    }

    public static function construirHijos($item, $rol)
    {
        $permiso = Permiso::getListaEnObjetos("id_rol={$rol} AND id_menu={$item->getId()}", null);
        
        // Verificar si el usuario tiene permiso para este menú
        $tienePermiso = false;
        foreach ($permiso as $key) {
            if ($key->getEstado() == 1) {
                $tienePermiso = true;
                break;
            }
        }
        
        if (!$tienePermiso) {
            return '';
        }

        // Obtener hijos del menú actual
        $hijos = Menu::getListaEnObjetos("es_hijo={$item->getId()} AND tipo=2", 'posicion');
        $tieneHijos = !empty($hijos);

        if ($tieneHijos) {
            $html = '<li class="menu__item as-dropdown-submenu">';
            $html .= '<a href="' . $item->getRuta() . '" class="as-menu__link as-submenu-btn">';
            $html .= '<span>' . $item->getNombre() . '</span> <i class="fas fa-chevron-down"></i>';
            $html .= '</a><ul class="as-submenu">';

            foreach ($hijos as $hijo) {
                $permisoHijo = Permiso::getListaEnObjetos("id_rol={$rol} AND id_menu={$hijo->getId()}", null);
                
                foreach ($permisoHijo as $perm) {
                    if ($perm->getEstado() == 1) {
                        $html .= '<li class="menu__item">';
                        $html .= '<a href="' . $hijo->getRuta() . '" class="as-menu__link as-submenu-color">';
                        $html .= $hijo->getNombre();
                        $html .= '</a></li>';
                        break;
                    }
                }
            }

            $html .= '</ul></li>';
            return $html;
        }

        // Menú sin hijos
        return '<li class="menu__item">' .
               '<a href="' . $item->getRuta() . '" class="as-menu__link">' .
               $item->getNombre() .
               '</a></li>';
    }
}