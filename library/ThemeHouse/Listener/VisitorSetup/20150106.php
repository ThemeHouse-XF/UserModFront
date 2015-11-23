<?php

class ThemeHouse_Listener_VisitorSetup
{

    /**
     *
     * @param XenForo_Visitor $visitor
     * @param string $dynamicClass
     */
    public static function extend(XenForo_Visitor &$visitor, $dynamicClass)
    {
        if ($visitor instanceof $dynamicClass) {
            return;
        }
        $visitor = serialize($visitor);
        $pattern = '#O:[0-9]*:"([^"]*)"#';
        preg_match($pattern, $visitor, $matches);
        if (isset($matches[1])) {
            $proxyClass = 'XFCP_' . $dynamicClass;
            if (!class_exists($proxyClass, false)) {
                eval('class ' . $proxyClass . ' extends ' . $matches[1] . ' {}');
                XenForo_Application::autoload($dynamicClass);
            }
            $replacement = 'O:' . strlen($dynamicClass) . ':"' . $dynamicClass . '"';
            $visitor = preg_replace($pattern, $replacement, $visitor);
        }
        $visitor = unserialize($visitor);
    }
}