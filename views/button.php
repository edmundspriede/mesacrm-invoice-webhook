<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (has_permission('invoices', '', 'edit')) { ?>

<div class="btn-group pull-right mright5">
    <a href="<?= admin_url('invoice_webhook/send/' . $invoice->id); ?>"
       class="btn btn-info">
        <i class="fa fa-paper-plane"></i> Send to Webhook
    </a>
</div>

<?php } ?>