<?php

namespace Analytics;

/**
 * Conversion
 * ---
 * This Class access and makes available the 
 * Conversion requests.
 * 
 * @author joaoserpa@lvengine.com
 */
class Conversion extends Ga
{

    //
    // ─── PUBLIC METHODS ─────────────────────────────────────────────────────────────
    //
    public function revenueByDay()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('totalValue'),
                'dimensions' => array('date')
            )
        );
    }
    public function refundByDay()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('refundAmount'),
                'dimensions' => array('date')
            )
        );
    }
    public function campaignsRevenue()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('transactionRevenue'),
                'dimensions' => array('campaign'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:transactionRevenue',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                )
            )
        );
    }
    public function topProducts()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('itemRevenue','itemQuantity'),
                'dimensions' => array('productName'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:itemRevenue',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    ), 
                    array(
                        'fieldName' => 'ga:itemQuantity',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                "filters" => "ga:itemRevenue!=0"
            )
        );
    }
    public function topCategories()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('itemRevenue','itemQuantity'),
                'dimensions' => array('productCategoryHierarchy'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:itemRevenue',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    ), 
                    array(
                        'fieldName' => 'ga:itemQuantity',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                "filters" => "ga:itemRevenue!=0"
            )
        );
    }
    public function topBrands()
    {
        return $this->metricsByDimensions(
            array(
                'metrics' => array('itemRevenue','itemQuantity'),
                'dimensions' => array('productBrand'),
                'sorts' => array(
                    array(
                        'fieldName' => 'ga:itemRevenue',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    ), 
                    array(
                        'fieldName' => 'ga:itemQuantity',
                        'orderType' => 'VALUE',
                        'sortOrder' => 'DESCENDING'
                    )
                ),
                "filters" => "ga:itemRevenue!=0"
            )
        );
    }
    // ────────────────────────────────────────────────────────────────────────────────

}