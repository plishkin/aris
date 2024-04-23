<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property int BookNumber 
 * @property integer CalculationID 
 * @property bool IsOffset 
 * @property integer OrderStatusID
 * @property integer UserID 
 * @property string OrderStatusEmailsSend
 * @property string Locale
 * @method Calculation Calculation()
 * @method OrderStatus OrderStatus() 
 * @method User User() 
 */

class ClientOrder extends \DataObject {
    
    private static $singular_name = "Client Order";
    
    private static $plural_name = "Client Orders";
    
    private static $db = array(
        'BookNumber' => 'Int',
        'IsOffset' => 'Boolean',
        'OrderStatusEmailsSend' => 'Varchar(255)',
        'Locale' => 'Varchar(5)',
    );

    private static $has_one = array(
        'User' => 'PC\User',
        'Calculation' => 'PC\Calculation',
        'OrderStatus' => 'PC\OrderStatus',
    );

    public function onAfterWrite() {
        parent::onAfterWrite();
        if ($this->isChanged('OrderStatusID')) {
            $this->sendOrderStatusChangeEmail();
        }
    }

    protected function sendOrderStatusChangeEmail() {
        /** @var OrderStatus|\TranslatableDataObject $os */
        $os = OrderStatus::get()->byID($this->OrderStatusID);
        $oiids = explode('|',$this->OrderStatusEmailsSend);

        $lastOSid = end($oiids);

        if ($this->OrderStatusID != $lastOSid && $os->IsUserEmailNotify){

            $locale = \i18n::get_locale();
            \i18n::set_locale($this->Locale);
            \Translatable::set_current_locale($this->Locale);

            $subjectViewer = new \SSViewer_FromString($os->T("EmailSubject",false));
            $subject = $subjectViewer->process(new \ArrayData(array('Order'=>$this)));

            $bodyViewer = new \SSViewer_FromString($os->T("EmailBody",false));
            $text = $bodyViewer->process(new \ArrayData(array('Order'=>$this)));

            $email = new \Email($os->T("EmailFrom",false),$this->User()->Email,$subject,$text);
            $email->send();
            if ($os->NotificationEmails) {
                $NotificationEmail = new \Email($os->T("EmailFrom",false),$os->T("NotificationEmails",false),$subject,$text);
                $NotificationEmail->send();
            }
            \Utils\Utils::NotifyAdminByEmail($text,$subject);
            $oiids[] = $this->OrderStatusID;
            $this->OrderStatusEmailsSend = implode('|',$oiids);

            \i18n::set_locale($locale);
            \Translatable::set_current_locale($locale);
        }

    }

}
