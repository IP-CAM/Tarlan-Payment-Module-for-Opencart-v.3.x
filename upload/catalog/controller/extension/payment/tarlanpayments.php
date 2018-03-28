<?php

class ControllerExtensionPaymentTarlanpayments extends Controller
{

    public function index()
    {
        $this->load->language('extension/payment/tarlanpayments');
        
        $this->load->model('extension/payment/tarlanpayments');
        
        $data['action'] = $this->url->link('extension/payment/tarlanpayments/checkout', '', true);
        $data['capture_on_gw'] = $this->model_extension_payment_tarlanpayments->isCaptureOnGatewaySide();
        $data['text_heading'] = $data['capture_on_gw'] ? $this->language->get('text_redirect') : $this->language->get('text_card_details');
        
        return $this->load->view('extension/payment/tarlanpayments', $data);
    }

    public function checkout()
    {
        $this->load->language('extension/payment/tarlanpayments');
        
        $this->load->model('extension/payment/tarlanpayments');
        
        $this->load->library('tarlanpayments');
        
        $error_message = '';
        
        if (false == $this->model_extension_payment_tarlanpayments->isCaptureOnGatewaySide()) {
            if (empty($this->request->post['card_number'])) {
                $error_message = $error_message . ' ' . $this->language->get('card_number_error');
            }
            
            if (empty($this->request->post['card_expiry'])) {
                $error_message = $error_message . ' ' . $this->language->get('card_expiry_error');
            }
            
            if (empty($this->request->post['card_cvv'])) {
                $error_message = $error_message . ' ' . $this->language->get('card_cvv_error');
            }
            
            if (empty($this->request->post['card_cardholder_name'])) {
                $error_message = $error_message . ' ' . $this->language->get('card_cardholder_name_error');
            }
        }
        
        if (! empty($error_message)) {
            $json['error'] = $error_message;
        } else {
            $payment_method = $this->config->get('payment_tarlanpayments_payment_method');
            if ($this->cart->hasRecurringProducts() > 0) {
                $payment_method = $this->tarlanpayments->getInitRecurrentMethod($payment_method);
            }
            
            if (FALSE == $payment_method) {
                $json['error'] = $this->language->get('error_recurring_not_supported');
            } else {
                $this->load->model('checkout/order');
                $this->load->model('localisation/country');
                $this->load->model('extension/payment/tarlanpayments');
                
                $order_id = $this->session->data['order_id'];
                $order_info = $this->model_checkout_order->getOrder($order_id);
                
                $billing_country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
                $shipping_country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
                
                if (! empty($billing_country_info)) {
                    $billing_address_country = $billing_country_info['iso_code_2'];
                    $billing_address_state = $order_info['payment_zone'] . ' ';
                    $billing_address_city = $order_info['payment_city'] . ' ';
                    $billing_address_street = $order_info['payment_address_1'] . ' ';
                    $billing_address_house = $order_info['payment_address_2'] . ' ';
                    $billing_address_flat = ' ';
                    $billing_address_zip = $order_info['payment_postcode'] . ' ';
                } else {
                    $billing_address_country = ' ';
                    $billing_address_state = ' ';
                    $billing_address_city = ' ';
                    $billing_address_street = ' ';
                    $billing_address_house = ' ';
                    $billing_address_flat = ' ';
                    $billing_address_zip = ' ';
                }
                
                if (! empty($shipping_country_info)) {
                    $shipping_address_country = $shipping_country_info['iso_code_2'];
                    $shipping_address_state = $order_info['shipping_zone'] . ' ';
                    $shipping_address_city = $order_info['shipping_city'] . ' ';
                    $shipping_address_street = $order_info['shipping_address_1'] . ' ';
                    $shipping_address_house = $order_info['shipping_address_2'] . ' ';
                    $shipping_address_flat = ' ';
                    $shipping_address_zip = $order_info['shipping_postcode'] . ' ';
                } else {
                    $shipping_address_country = ' ';
                    $shipping_address_state = ' ';
                    $shipping_address_city = ' ';
                    $shipping_address_street = ' ';
                    $shipping_address_house = ' ';
                    $shipping_address_flat = ' ';
                    $shipping_address_zip = ' ';
                }
                
                $customer_email = $this->customer->isLogged() ? $this->customer->getEmail() : $this->session->data['guest']['email'];
                $customer_phone = $this->customer->isLogged() ? $this->customer->getTelephone() : $this->session->data['guest']['telephone'];
                
                $merchant_url = $this->url->link('/', '');
                
                $amount = $this->tarlanpayments->lowestDenomination($order_info['total'], $order_info['currency_code']);
                $currency = $order_info['currency_code'];
                
                $user_ip = $this->request->server['REMOTE_ADDR'];
                
                $pan = isset($this->request->post['card_number']) ? str_replace(' ', '', $this->request->post['card_number']) : ' ';
                $card_exp = isset($this->request->post['card_expiry']) ? str_replace(' ', '', $this->request->post['card_expiry']) : ' ';
                $cvv = isset($this->request->post['card_cvv']) ? str_replace(' ', '', $this->request->post['card_cvv']) : ' ';
                $cardholder_name = isset($this->request->post['card_cardholder_name']) ? $this->request->post['card_cardholder_name'] : ' ';
                
                try {
                    $json = $this->tarlanpayments->createTransaction($payment_method, $customer_email, $customer_phone, $billing_address_country, $billing_address_state, $billing_address_city, $billing_address_street, $billing_address_house, $billing_address_flat, $billing_address_zip, $shipping_address_country, $shipping_address_state, $shipping_address_city, $shipping_address_street, $shipping_address_house, $shipping_address_flat, $shipping_address_zip, $amount, $currency, $order_id, $merchant_url, $user_ip, $pan, $card_exp, $cvv, $cardholder_name);
                    
                    $transaction_guid = $json['gw']['gateway-transaction-id'];
                    $this->session->data['transaction_guid'] = $transaction_guid;
                    $transaction_status = $json['gw']['status-code'];
                    $this->model_extension_payment_tarlanpayments->addTransaction($transaction_guid, $transaction_status, $payment_method, $amount, $currency, $order_id, $user_ip);
                    
                    $transaction_status_name = $this->tarlanpayments->getTransactionStatusName($transaction_status);
                    $order_status_id = $this->config->get('payment_tarlanpayments_transaction_status_' . strtolower($transaction_status_name));
                    
                    if ($order_status_id) {
                        if ($this->cart->hasRecurringProducts() && $this->tarlanpayments->isSuccessTransaction($payment_method, $transaction_status)) {
                            foreach ($this->cart->getRecurringProducts() as $item) {
                                if ($item['recurring']['trial']) {
                                    $trial_price = $this->tax->calculate($item['recurring']['trial_price'] * $item['quantity'], $item['tax_class_id']);
                                    $trial_amt = $this->currency->format($trial_price, $this->session->data['currency']);
                                    $trial_text = sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);
                                    
                                    $item['recurring']['trial_price'] = $trial_price;
                                } else {
                                    $trial_text = '';
                                }
                                
                                $recurring_price = $this->tax->calculate($item['recurring']['price'] * $item['quantity'], $item['tax_class_id']);
                                $recurring_amt = $this->currency->format($recurring_price, $this->session->data['currency']);
                                $recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);
                                
                                $item['recurring']['price'] = $recurring_price;
                                
                                if ($item['recurring']['duration'] > 0) {
                                    $recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
                                }
                                
                                if (! $item['recurring']['trial']) {
                                    // We need to override this value for the proper calculation in updateRecurringExpired
                                    $item['recurring']['trial_duration'] = 0;
                                }
                                
                                $this->model_extension_payment_tarlanpayments->createRecurringOrder($item, $this->session->data['order_id'], $recurring_description, $transaction_guid);
                            }
                        }
                        
                        $order_status_comment = $this->language->get('transaction_status_' . strtolower($transaction_status_name) . '_comment');
                        
                        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $order_status_comment, true);
                    }
                    
                    if (empty($json['error'])) {
                        $json['redirect'] = $this->url->link('checkout/success', '', true);
                    }
                    
                    if (! empty($json['gw']['redirect-url'])) {
                        $json['redirect'] = $json['gw']['redirect-url'];
                    }
                } catch (Exception $e) {
                    $json['error'] = $e->getMessage();
                }
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function callback()
    {
        if (isset($this->request->post['json'])) {
            $json = json_decode(html_entity_decode($this->request->post['json']), true);
            
            $json_status = json_last_error();
            if (JSON_ERROR_NONE == $json_status && isset($json['result-data']['gw']['gateway-transaction-id']) && isset($json['result-data']['gw']['status-code'])) {
                $transaction_guid = $json['result-data']['gw']['gateway-transaction-id'];
                $transaction_status = (int) $json['result-data']['gw']['status-code'];
                
                $this->load->model('extension/payment/tarlanpayments');
                $this->load->model('checkout/order');
                
                $transactions = $this->model_extension_payment_tarlanpayments->getTransactions(array(
                    'transaction_guid' => $transaction_guid
                ));
                
                if (! empty($transactions)) {
                    $this->model_extension_payment_tarlanpayments->updateTransactionStatus($transactions[0]['transaction_id'], $transaction_status);
                    
                    $transaction_status_name = $this->tarlanpayments->getTransactionStatusName($transaction_status);
                    $order_status_id = $this->config->get('payment_tarlanpayments_transaction_status_' . strtolower($transaction_status_name));
                    $order_status_comment = $this->language->get('transaction_status_' . strtolower($transaction_status_name) . '_comment');
                    
                    $this->model_checkout_order->addOrderHistory($transactions[0]['order_id'], $order_status_id, $order_status_comment, true);
                }
            }
        }
    }

    public function redirect()
    {
        $this->load->model('extension/payment/tarlanpayments');
        
        $this->load->library('tarlanpayments');
        
        $transaction_guid = isset($this->session->data['transaction_guid']) ? $this->session->data['transaction_guid'] : '';
        unset($this->session->data['transaction_guid']);
        
        if (! empty($transaction_guid)) {
            $transactions = $this->model_extension_payment_tarlanpayments->getTransactions(array(
                'transaction_guid' => $transaction_guid
            ));
        }
        
        if (! empty($transaction_guid) && ! empty($transactions) && $this->tarlanpayments->isSuccessTransaction($transactions[0]['payment_method'], $transactions[0]['transaction_status'])) {
            $this->response->redirect($this->url->link('checkout/success', '', true));
        } else {
            $this->response->redirect($this->url->link('checkout/failure', '', true));
        }
    }
}
