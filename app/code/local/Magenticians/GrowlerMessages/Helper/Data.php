<?php
class Magenticians_GrowlerMessages_Helper_Data
{
    public function getMessages()
    {
        $_messages = Mage::registry('messages_collection');

        if (is_null($_messages)) {
            return array();
        }

        /** @var $_messages Mage_Core_Model_Message_Collection */
        $_messages = $_messages->getItems();

        if (empty($_messages)) {
            return array();
        }

        $_messagesData = array();

        foreach ($_messages as $_message) {
            /** @var $_message Mage_Core_Model_Message_Abstract */
            $_messagesData[] = array(
                'type' => $_message->getType(),
                'text' => $_message->getText()
            );
        }

        return $_messagesData;
    }
}