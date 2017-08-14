<?php

namespace Analytics;

/**
 * Audience
 * ---
 * This Class access and makes available the 
 * Audience requests.
 * 
 * @author joaoserpa@lvengine.com
 */
class Audience extends Ga
{
    
    //
    // ─── PUBLIC METHODS ─────────────────────────────────────────────────────────────
    //
    public function sessionsByGender()
    {
        return $this->metricsByDimensions( 
            array(
                'metrics' => array('sessions'), 
                'dimensions' => array('userGender') 
            )
        );
    }
    public function sessionsByAge()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('sessions'), 
                'dimensions' => array('userAgeBracket') 
            )
        );
    }
    public function sessionsByCountry()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('sessions'), 
                'dimensions' => array('country') 
            )
        );
    }
    public function sessionsAndUsers()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('sessions', 'users'), 
                'dimensions' => array('date') 
            )
        );
    }
    public function bounceRate()
    {
        return $this->metricsByDimensions( 
            array( 
                'metrics' => array('bounceRate'),
            )
        );
    }
    // ────────────────────────────────────────────────────────────────────────────────

}
