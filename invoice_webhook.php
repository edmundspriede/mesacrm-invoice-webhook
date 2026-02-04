<?php

/*
Module Name: Invoice Webhook
Description: This is a sample custom module for Perfex CRM.
Version: 1.0.0
Requires at least: 2.3.*
Author: Ed
*/

defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('admin_init', 'invoice_webhook_add_settings');
hooks()->add_action('after_invoice_view_as_admin', 'invoice_webhook_add_button');

register_activation_hook('invoice_webhook', 'invoice_webhook_activate');

function invoice_webhook_activate()
{
    add_option('invoice_webhook_url', '');
}

/**
 * Add settings field
 */
function invoice_webhook_add_settings()
{
    $CI = &get_instance();

    $CI->app_tabs->add_settings_tab('invoice_webhook', [
        'name'     => 'Invoice Webhook',
        'view'     => 'invoice_webhook/settings',
        'position' => 40
    ]);
}

/**
 * Inject Button Using JS
 */
function invoice_webhook_inject_button()
{
    $CI = &get_instance();

    // Only on invoice view/edit page
    if (strpos(current_url(), 'admin/invoices/list_invoices') !== false ||
        strpos(current_url(), 'admin/invoices/invoice') !== false) {

        echo $CI->load->view('invoice_webhook/button_js', [], true);
    }
}