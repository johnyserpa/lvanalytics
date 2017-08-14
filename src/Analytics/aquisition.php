<?php

namespace Analytics;

/**
 * Aquisition
 * ---
 * This Class access and makes available the 
 * Aquisition requests.
 * 
 * @author joaoserpa@lvengine.com
 */
class Aquisition extends Ga
{
    
    //
    // ─── PUBLIC METHODS ─────────────────────────────────────────────────────────────
    //
    public function sessionsByChannel()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('sessions'), 
                'dimensions' => array('channelGrouping') 
            )
        );
    }
    public function sessionsBySourcesMediums()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('sessions'), 
                'dimensions' => array('sourceMedium') 
            )
        );
    }
    public function campaigns()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('users', 'newUsers', 'sessions', 'bounceRate', 'avgSessionDuration'),
                'dimensions' => array('campaign'),
                "sorts" => array(
                    array(
                        'fieldName' => 'ga:users',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                'page_size' => 21
            )
        );
    }
    // ────────────────────────────────────────────────────────────────────────────────

}