<h3><i class="fa fa-comment fa-fw"></i>Synology Chat</h3>
<div class="panel">
    <?= $this->form->label(t('Webhook URL'), 'synologychat_webhook_url') ?>
    <?= $this->form->text('synologychat_webhook_url', $values) ?>

    <?= $this->form->hidden('synologychat_include_link', array('synologychat_include_link' => 0)) ?>
    <?= $this->form->checkbox('synologychat_include_link', t('Include link to task'), 1, isset($values['synologychat_include_link']) && $values['synologychat_include_link'] == 1) ?>
    <p class="form-help"><?= t('Not advised in current versions of Synology Chat (version <= 2.1.0-1228) since it causes url unfurling showing "Login"') ?></p>


    <p class="form-help"><a href="https://github.com/Kolossi/plugin-synology-chat#configuration" target="_blank"><?= t('Help on Synology Chat integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</div>
