<?php

namespace Analytics;

/**
 * Behavior
 * ---
 * This Class access and makes available the 
 * Behavior requests.
 * 
 * @author joaoserpa@lvengine.com
 */
class Behavior extends Ga
{
    //
    // ─── PUBLIC METHODS ─────────────────────────────────────────────────────────────
    //
    public function internalSearches()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('searchUniques'),
                'dimensions' => array('searchKeyword'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:searchUniques',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                "page_size" => 25
            )
        );
    }
    public function internalSearchesWithoutResults()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('totalEvents', 'uniqueEvents'),
                'dimensions' => array('eventLabel'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:totalEvents',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                'page_size' => 25,
                'filters' => 'ga:eventAction==Sem Resultados'
            )
        );
    }
    public function eCommerceEvents()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('totalEvents', 'uniqueEvents'),
                'dimensions' => array('eventAction'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:totalEvents',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                'page_size' => 25,
                'filters' => 'ga:eventCategory==eCommerce,ga:eventAction!=Product Impression'
            )
        );
    }
    // ────────────────────────────────────────────────────────────────────────────────
}