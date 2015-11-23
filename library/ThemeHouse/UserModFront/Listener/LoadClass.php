<?php

class ThemeHouse_UserModFront_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_UserModFront' => array(
                'controller' => array(
                    'XenForo_ControllerPublic_Member'
                ), 
            ), 
        );
    }

    public static function loadClassController($class, array &$extend)
    {
        $loadClassController = new ThemeHouse_UserModFront_Listener_LoadClass($class, $extend, 'controller');
        $extend = $loadClassController->run();
    }
}