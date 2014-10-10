<?php
class Magenticians_GrowlerMessages_Model_Observer
{
    public function relayMessages(Varien_Event_Observer $observer)
    {
        // Retrieve the messages collection from adminhtml session and put it in the registry
        // true parameter passed to getMessages, will make sure they get cleared from the session
        Mage::register('messages_collection', Mage::getSingleton('adminhtml/session')->getMessages(true));
    }

    public function alterMessageResponse(Varien_Event_Observer $observer) {
        if (! Mage::app()->getRequest()->isXmlHttpRequest()) {
            // Ignore non-AJAX requests
            return;
        }

        // Attempt to decode the response body, non-JSON responses will be skipped
        try {
            $responseBodyDecoded = Mage::helper('core')->jsonDecode($observer->getEvent()->getResponse()->getBody());
        } catch (Zend_Json_Exception $e) {
            return;
        }

        // Intercept our target keys and replace with our own JSON encoded array
        foreach (array('message', 'messages') as $target) {
            if (isset($responseBodyDecoded[$target])) {
                $responseBodyDecoded[$target] = json_encode(Mage::helper('growlermessages')->getMessages());
            }
        }

        // Ship our modified body to the response
        $observer->getEvent()->getResponse()->setBody(Mage::helper('core')->jsonEncode($responseBodyDecoded));
    }
}