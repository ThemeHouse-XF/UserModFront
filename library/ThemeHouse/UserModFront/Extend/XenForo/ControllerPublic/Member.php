<?php

/**
 *
 * @see XenForo_ControllerPublic_Member
 */
class ThemeHouse_UserModFront_Extend_XenForo_ControllerPublic_Member extends XFCP_ThemeHouse_UserModFront_Extend_XenForo_ControllerPublic_Member
{

    /**
     * Shows a list of moderated users and allows them to be managed.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionModerated()
    {
        $visitor = XenForo_Visitor::getInstance();
        if (!XenForo_Permission::hasPermission($visitor['permissions'], 'general', 'userModFrontEnd')) {
            return $this->responseNoPermission();
        }
        
        $users = $this->_getUserModel()->getUsers(array(
            'user_state' => 'moderated'
        ), array(
            'limit' => 30
        ));
        
        $class = XenForo_Application::resolveDynamicClass('XenForo_Session');
        /**
         * @var $publicSession XenForo_Session
         */
        $publicSession = new $class();
        $publicSession->start();
        if ($publicSession->get('user_id') == XenForo_Visitor::getUserId()) {
            $sessionCounts = $publicSession->get('userModerationCounts');
            if (!is_array($sessionCounts) || $sessionCounts['total'] != count($users)) {
                $publicSession->remove('userModerationCounts');
                $publicSession->save();
                
                $this->getModelFromCache('XenForo_Model_User')->rebuildUserModerationQueueCache();
            }
        }
        
        if (!$users) {
            return $this->responseMessage(new XenForo_Phrase('no_users_awaiting_approval'));
        }
        
        /**
         * @var XenForo_Model_SpamPrevention $spamPreventionModel
         */
        $spamPreventionModel = $this->getModelFromCache('XenForo_Model_SpamPrevention');
        
        $spamLogs = $spamPreventionModel->getSpamTriggerLogsByContentIds('user', array_keys($users));
        $spamLogs = $spamPreventionModel->prepareSpamTriggerLogs($spamLogs);
        
        foreach ($users as &$user) {
            $ips = $this->_getUserModel()->getRegistrationIps($user['user_id']);
            $user['ip'] = $ips ? reset($ips) : false;
            $user['ipHost'] = $user['ip'] ? XenForo_Model_Ip::getHost($user['ip']) : false;
            
            if (isset($spamLogs[$user['user_id']])) {
                $user['spamDetails'] = $spamLogs[$user['user_id']]['detailsPrintable'];
            } else {
                $user['spamDetails'] = false;
            }
        }
        
        $viewParams = array(
            'users' => $users,
            'userEditFrontEnd' => $this->_userEditFrontEndCheck(),
        );
        
        return $this->responseView('XenForo_ViewAdmin_User_Moderated', 'th_user_moderated_usermodfrontend',
            $viewParams);
    }

    /**
     * Processes moderated users.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionModeratedUpdate()
    {
        $this->_assertPostOnly();
        
        $usersInput = $this->_input->filterSingle('users', XenForo_Input::ARRAY_SIMPLE);
        $users = $this->_getUserModel()->getUsersByIds(array_keys($usersInput));
        foreach ($users as $user) {
            if (!isset($usersInput[$user['user_id']])) {
                continue;
            }
            
            $userControl = $usersInput[$user['user_id']];
            if (empty($userControl['action']) || $userControl['action'] == 'none') {
                continue;
            }
            
            $notify = (!empty($userControl['notify']) ? true : false);
            $rejectionReason = (!empty($userControl['reject_reason']) ? $userControl['reject_reason'] : '');
            
            $this->getModelFromCache('XenForo_Model_UserConfirmation')->processUserModeration($user, 
                $userControl['action'], $notify, $rejectionReason);
        }
        
        return $this->responseRedirect(
            XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('members/moderated')
        );
    }
    
    protected function _userEditFrontEndCheck()
    {
        if (XenForo_Application::$versionId >= 1020000) {
            $addOns = XenForo_Application::get('addOns');
            if (isset($addOns['ThemeHouse_UserEditFrontEnd'])) {
                $visitor = XenForo_Visitor::getInstance()->toArray();
                if (!$this->_getUserModel()->canEditFullUser($visitor)) {
                    return false;
                }
                return true;
            }
        } else {
            /* @var $addOnModel XenForo_Model_AddOn */
            $addOnModel = XenForo_Model::create('XenForo_Model_AddOn');
            $addOn = $addOnModel->getAddOnById('ThemeHouse_UserEditFrontEnd');
            if ($addOn && $addOn['active']) {
                $visitor = XenForo_Visitor::getInstance()->toArray();
                if (!$this->_getUserModel()->canEditFullUser($visitor)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }
}