<?php

class ThemeHouse_UserModFront_Listener_VisitorSetup
{

    protected static $_runOnce = false;

    public static function visitorSetup(XenForo_Visitor &$visitor)
    {
        if ($visitor->user_id && !self::$_runOnce) {
            self::$_runOnce = true;
            if (XenForo_Application::isRegistered('session')) {
                if (XenForo_Permission::hasPermission($visitor['permissions'], 'general', 'userModFrontEnd')) {
                    if (XenForo_Application::isRegistered('userModerationCounts')) {
                        $counts = XenForo_Application::get('userModerationCounts');
                    } else {
                        $counts = XenForo_Model::create('XenForo_Model_User')->rebuildUserModerationQueueCache();
                    }
                    
                    /* @var $session XenForo_Session */
                    $session = XenForo_Application::get('session');
                    $sessionCounts = $session->get('userModerationCounts');
                    
                    if (!is_array($sessionCounts) || $sessionCounts['lastBuildDate'] < $counts['lastModifiedDate']) {
                        $sessionCounts = array(
                            'total' => $counts['total'],
                            'lastBuildDate' => XenForo_Application::$time
                        );
                        
                        $session->set('userModerationCounts', $sessionCounts);
                    }
                }
            }
        }
        return $visitor;
    }
}