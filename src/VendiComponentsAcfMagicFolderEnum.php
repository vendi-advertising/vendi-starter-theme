<?php

namespace Vendi\Theme;

enum VendiComponentsAcfMagicFolderEnum: string
{
    case COMPONENTS = 'components';
    case ENTITIES = 'entities';
    case ENTITY_FIELDS = 'entity-fields';
    case BACKGROUNDS = 'backgrounds';
    case FIELDSETS = 'fieldsets';
    case THEME_SETTINGS = 'theme-settings';
    case BASE_COMPONENT = 'base-component';
    case FIELDS = 'fields';
    case OPTION_PAGES = 'option-pages';
}
