<?php

class ThemeHouse_UserModFront_Listener_FileHealthCheck
{

    public static function fileHealthCheck(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes = array_merge($hashes,
            array(
                'library/ThemeHouse/UserModFront/Extend/XenForo/ControllerPublic/Member.php' => 'efba921de5e81f4c097adc22595a1e14',
                'library/ThemeHouse/UserModFront/Install/Controller.php' => 'eeb24b38a9308e019d9e0d76d8df0237',
                'library/ThemeHouse/UserModFront/Listener/LoadClass.php' => '1fd3284e119ec9f965de73c8c2085374',
                'library/ThemeHouse/UserModFront/Listener/VisitorSetup.php' => '1e3b8b43b25d54d8faef128c58f2bb6c',
                'library/ThemeHouse/Install.php' => '18f1441e00e3742460174ab197bec0b7',
                'library/ThemeHouse/Install/20151109.php' => '2e3f16d685652ea2fa82ba11b69204f4',
                'library/ThemeHouse/Deferred.php' => 'ebab3e432fe2f42520de0e36f7f45d88',
                'library/ThemeHouse/Deferred/20150106.php' => 'a311d9aa6f9a0412eeba878417ba7ede',
                'library/ThemeHouse/Listener/ControllerPreDispatch.php' => 'fdebb2d5347398d3974a6f27eb11a3cd',
                'library/ThemeHouse/Listener/ControllerPreDispatch/20150911.php' => 'f2aadc0bd188ad127e363f417b4d23a9',
                'library/ThemeHouse/Listener/InitDependencies.php' => '8f59aaa8ffe56231c4aa47cf2c65f2b0',
                'library/ThemeHouse/Listener/InitDependencies/20150212.php' => 'f04c9dc8fa289895c06c1bcba5d27293',
                'library/ThemeHouse/Listener/LoadClass.php' => '5cad77e1862641ddc2dd693b1aa68a50',
                'library/ThemeHouse/Listener/LoadClass/20150518.php' => 'f4d0d30ba5e5dc51cda07141c39939e3',
                'library/ThemeHouse/Listener/VisitorSetup.php' => 'c2081eab8a0428070a4f0a32830ebcb2',
                'library/ThemeHouse/Listener/VisitorSetup/20150106.php' => '6326d26f4156c51ca5d289d44da5fad8',
            ));
    }
}