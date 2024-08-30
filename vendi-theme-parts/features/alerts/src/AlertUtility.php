<?php

namespace Vendi\Theme\Feature\Alert;

use Vendi\Theme\Feature\Alert\DTO\Alert;
use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;
use Vendi\Theme\Feature\Alert\Enum\AlertTypeEnum;

final class AlertUtility {

    protected static array $instance = [];

    private function __construct() {
        // NOOP
    }

    public static function getInstance(): self {
        static $instance = null;
        if ( null === $instance ) {
            $instance = new static();
        }

        return $instance;
    }

    private function getAlerts(): array {
        //Get all alerts in the supplied/default time range
        $args = [
            'posts_per_page'   => - 1,
            'meta_key'         => 'end_date',
            'order'            => 'DESC',
            'post_type'        => 'alert',
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'meta_query'       => [
                'relation' => 'AND',
                [
                    'key'     => 'start_date',
                    'value'   => date( 'Ymd' ),
                    'compare' => '<=',
                    'type'    => 'DATE',
                ],
                [
                    'key'     => 'end_date',
                    'value'   => date( 'Ymd' ),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ];

        return get_posts( $args );
    }

    /**
     * @return Alert[]
     */
    public function loadAlertsInfo( AlertAppearanceEnum $appearanceType ): array {
        $alertInfoArray = [];
        $alerts         = $this->getAlerts();

        foreach ( $alerts as $alert ) {

            $alertObj = Alert::createFromPost( $alert );
            if ( $alertObj->appearance !== $appearanceType ) {
                continue;
            }

            $alertInfoArray[] = $alertObj;
        }

        return $alertInfoArray;
    }

    public function getAlertTypeIcon( Alert $alert ): string {
        return match ( $alert->type ) {
            AlertTypeEnum::Critical => vendi_get_svg( '/features/alerts/svgs/exclamation.svg', false ),
            AlertTypeEnum::Warning => vendi_get_svg( '/features/alerts/svgs/warning.svg', false ),
            default => vendi_get_svg( '/features/alerts/svgs/info.svg', false ),
        };
    }
}
