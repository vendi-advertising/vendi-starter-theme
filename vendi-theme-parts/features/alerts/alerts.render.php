<?php

use Vendi\Theme\Feature\Alert\AlertUtility;
use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;

global $vendi_feature_object_state;

$appearanceType = $vendi_feature_object_state['appearance'] ?? null;
if ( ! $appearanceType instanceof AlertAppearanceEnum) {
    return;
}

$alertUtility = AlertUtility::getInstance();

$alertInfo = $alertUtility->loadAlertsInfo($appearanceType);
if (empty($alertInfo)) {
    return;
}

?>
<div class="alerts-container">
    <?php foreach ($alertInfo as $alert) : ?>
        <?php if ( ! $alert->hasText()): ?>
            <?php continue; ?>
        <?php endif; ?>
        <div
            class="single-alert hidden <?php esc_attr_e($alert->type->value); ?>"
            data-role="alert"
            data-id="<?php esc_attr_e($alert->id); ?>"
            data-version="<?php esc_attr_e($alert->version); ?>"
            data-end-date="<?php esc_attr_e($alert->endDate->format('Y-m-d H:i:s')); ?>"
            data-start-date="<?php esc_attr_e($alert->startDate->format('Y-m-d H:i:s')); ?>"
        >
            <div class="alert-icon"><?php echo $alertUtility->getAlertTypeIcon($alert); ?></div>
            <div class="alert-copy">
                <?php if ($alert->headline) : ?>
                    <div class="alert-headline"><?php esc_html_e($alert->headline); ?></div>
                <?php endif; ?>
                <div class="alert-body">
                    <div class="alert-copy">
                        <?php if ($copy = $alert->getCopyHtmlEscaped()) : ?>
                            <?php echo $copy; ?>
                        <?php endif; ?>
                    </div>
                    <div class="alert-cta">
                        <?php if ($link = $alert->callToAction) : ?>
                            <?php echo $link->toHtml(cssClasses: 'alert-cta'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if ($alert->dismissible): ?>
                <div class="dismiss-alert" data-role="dismiss-alert">
                    <?php vendi_get_svg(VENDI_CUSTOM_THEME_FEATURE_FOLDER_NAME . '/alerts/svgs/close.svg'); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
