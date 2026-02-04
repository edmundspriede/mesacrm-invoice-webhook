<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_webhook extends AdminController
{
    public function send($invoice_id)
    {
        if (!has_permission('invoices', '', 'edit')) {
            access_denied('Invoices');
        }

        $this->load->model('invoices_model');

        $invoice = $this->invoices_model->get($invoice_id);

        if (!$invoice) {
            set_alert('danger', 'Invoice not found');
            redirect(admin_url('invoices'));
        }

        $webhook_url = get_option('invoice_webhook_url');

        if (empty($webhook_url)) {
            set_alert('danger', 'Webhook URL not configured');
            redirect(admin_url('invoices/list_invoices/' . $invoice_id));
        }

        $payload = $this->build_payload($invoice);

        $result = $this->send_to_webhook($webhook_url, $payload);

        if ($result === true) {
            set_alert('success', 'Invoice sent successfully');
        } else {
            set_alert('danger', 'Webhook failed: ' . $result);
        }

        redirect(admin_url('invoices/list_invoices/' . $invoice_id));
    }

    private function build_payload($invoice)
    {
        return [
            'invoice' => [
                'id'             => $invoice->id,
                'number'         => format_invoice_number($invoice->id),
                'client_id'      => $invoice->clientid,
                'date'           => $invoice->date,
                'due_date'       => $invoice->duedate,
                'subtotal'       => $invoice->subtotal,
                'total'          => $invoice->total,
                'status'         => $invoice->status,
                'currency'       => $invoice->currency_name,
            ],
            'items' => $invoice->items
        ];
    }

    private function send_to_webhook($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        return 'HTTP Error: ' . $httpCode;
    }
}