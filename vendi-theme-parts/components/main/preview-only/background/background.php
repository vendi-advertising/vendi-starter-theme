<?php

use Vendi\Theme\GenericComponent;

$component = new GenericComponent( 'component-preview-only-background' );

$component->componentStyles->addStyle( 'min-height', '50vh' );
$component->componentStyles->addStyle( 'margin', '10rem' );
$component->setKeyForBackgrounds( 'entity_backgrounds' );

$component->renderComponentWrapperStart();
$component->renderComponentWrapperEnd();
