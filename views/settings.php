<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="form-group">
    <label>Webhook URL</label>
    <input type="text"
           name="settings[invoice_webhook_url]"
           value="<?= get_option('invoice_webhook_url'); ?>"
           class="form-control">
</div>