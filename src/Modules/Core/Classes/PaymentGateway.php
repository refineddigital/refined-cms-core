<?php
namespace RefinedDigital\CMS\Modules\Core\Classes;

use RefinedDigital\FormBuilder\Module\Models\FormPaymentTransaction;

class PaymentGateway {

    protected $view;

    protected $total;
    protected $description;
    protected $metaData = [];
    protected $currency;

    protected $typeId;
    protected $typeDetails;

    public function getView()
    {
        return $this->view;
    }

    public function formatAddress($fields)
    {
        $address = [];
        if ($fields['Address']) {
            $address[] = $fields['Address'];
        }
        if ($fields['Address 2']) {
            $address[] = $fields['Address 2'];
        }
        if (isset($fields['Suburb']) && $fields['Suburb']) {
            $address[] = $fields['Suburb'];
        }
        if (isset($fields['City / Town']) && $fields['City / Town']) {
            $address[] = $fields['City / Town'];
        }
        if ($fields['State']) {
            $address[] = $fields['State'];
        }
        if ($fields['Postcode']) {
            $address[] = $fields['Postcode'];
        }

        return $address;
    }

    public function logTransaction($form, $emailData, $response = false)
    {

        if ($response) {
            $transactionId = $response->getTransactionReference() ?: $response->getTransactionId();
            $responseMessage = [$response->getMessage()];
        } else {
            $transactionId = null;
            $responseMessage = null;
        }

        return FormPaymentTransaction::create([
            'form_id' => isset($form->id) ? $form->id : null,
            'type_id' => $this->typeId,
            'type_details' => $this->typeDetails,
            'transaction_id' => $transactionId,
            'request' => $emailData,
            'response' => $responseMessage,
        ]);
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function setTypeDetails($typeDetails)
    {
        $this->typeDetails = $typeDetails;
        return $this;
    }

    public function roundNumber($number)
    {
        return round($number, 2);
    }

}
