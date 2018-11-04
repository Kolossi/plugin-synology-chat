<h3><i class="fa fa-synologychat fa-fw"></i>Synology Chat</h3>
<div class="panel">
    <?= $this->form->label(t('Webhook URL'), 'synology_chat_webhook_url') ?>
    <?= $this->form->text('synology_chat_webhook_url', $values) ?>

    <p class="form-help"><a href="https://github.com/Kolossi/plugin-synology-chat#configuration" target="_blank"><?= t('Help on Synology Chat integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</div>
