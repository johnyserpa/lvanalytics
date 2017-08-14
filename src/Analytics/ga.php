<?php

namespace Analytics;

/**
 * Dependencies
 */
use Google_Client;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_OrderBy;
use \Exception;

/**
*  LvGa
*  ---
*  Main class to interact with GA reporting API.
*
*  @author joaoserpa@lvengine.com
*/
class Ga 
{

	/**
	 * Configurações necessárias.
	 */
	protected $config = [];

	/**
	 * Google Client.
	 */
	protected $client;
	/**
	 * Google Analytics Service.
	 * ---
	 * A Google Service needs a Google Client.
	 */
	protected $analytics;

	/**
	 * Date Range.
	 */
	protected $date_range;
	/**
	 * Sort.
	 */
	protected $sorts;
	/**
	 * Page Size.
	 */
	protected $page_size;
	/**
	 * Filters.
	 */
	protected $filters;
	/**
	 * Query metrics.
	 */
	protected $metrics;
	/**
	 * Query dimensions.
	 */
	protected $dimensions;
	/**
	 * Query request.
	 */
	protected $request;
	/**
	 * Query report.
	 */
	protected $report;

	/**
	 * Constructor function.
	 */
	function __construct() {
		/**
		 * Default Configs.
		 */
		$this->config['app_name'] = "LVEngine - Google Analytics";

		/**
		 * Config Google_Client
		 */
		$this->googleClientConfig();
	 }


	//
	// ─── CONFIGURATION Methods ──────────────────────────────────────────────────────────────
	//
		/**
		 * Method to set default Google Configuration.
		 *
		 * @return void
		 */
		private function googleClientConfig()
		{
			/**
			 * Qual o melhor local para o ficheiro?
			 */
			$KEY_FILE_LOCATION = __DIR__ . '/credentials.json';
			$this->client = new Google_Client();
			$this->client->setApplicationName($this->config['app_name']);
			$this->client->setAuthConfig($KEY_FILE_LOCATION);
			//$this->client->useApplicationDefaultCredentials();
			$this->client->addScope("https://www.googleapis.com/auth/analytics.readonly");
			$this->analytics = new Google_Service_AnalyticsReporting($this->client);
		}

		/**
		 * Set the View ID.
		 *
		 * @param [string] $view_id
		 * @return void
		 */
		public function setViewId( $view_id )
		{
			$this->config['view_id'] = $view_id;
		}
	// ────────────────────────────────────────────────────────────────────────────────


	//
	// ─── METHODS TO HANDLE THE QUERIES ──────────────────────────────────────────────
	//

		//
		// ─── PRIVATE METHODS ─────────────────────────────────────────────
		//
			/**
			 * Validate the Request by checking the required fields.
			 *
			 * @return void
			 */
			private function validateRequest()
			{
				/**
				 * View ID.
				 */
				if ( !isset($this->config['view_id']) || empty($this->config['view_id']) )
					throw new Exception('No VIEW ID set. Use the method ->setViewId([string] $view_id).');
				/**
				 * Dates.
				 */
				if (!$this->date_range)
					throw new Exception('No date range selected. Use the method ->setDate($begin, $end).');
				else {
					if (!$this->date_range->getStartDate())
						throw new Exception('No start date selected. Use the method ->setDate($begin, $end).');
					if (!$this->date_range->getEndDate())
						throw new Exception('No end date selected. Use the method ->setDate($begin, $end).');
				}
			}

			/**
			 * Method to set the sorts.
			 *
			 * @param array $sorts
			 * @return void
			 */
			private function setSorts( array $sorts )
			{
				/**
				 * Create the sorts array to be inserted in Request.
				 */
				$this->sorts = array();

				/**
				 * Iterate all sorts and add to array.
				 */
				foreach ($sorts as $sort) {
					$new_sort = new Google_Service_AnalyticsReporting_OrderBy();
					$new_sort->setFieldName( $sort['fieldName'] );
					$new_sort->setOrderType( $sort['orderType'] );
					$new_sort->setSortOrder( $sort['sortOrder'] );
					$this->sorts[] = $new_sort;
				}
			}

			/**
			 * Method to limit the returned results.
			 *
			 * @param [int] $limit
			 * @return void
			 */
			private function setPageSize($limit)
			{
				$this->page_size = $limit;
			}

			/**
			 * Methdo to set filters.
			 *
			 * @param [string] $filters
			 * @return void
			 */
			private function setFilters($filters)
			{
				$this->filters = $filters;
			}

			/**
			 * Method to set metrics for Request.
			 *
			 * @param array $metrics
			 * @return void
			 */
			private function setMetrics( array $metrics )
			{
				/**
				 * Create the metrics array to be inserted in Request.
				 */
				$this->metrics = array();

				/**
				 * Iterate all metrics passed and add to array.
				 */
				foreach ($metrics as $metric) {
					$new_metric = new Google_Service_AnalyticsReporting_Metric();
					$new_metric->setExpression('ga:' . $metric);				
					$this->metrics[] = $new_metric;
				}
			}

			/**
			 * Method to set dimensions for Request.
			 *
			 * @param array $dimensions
			 * @return void
			 */
			private function setDimensions( array $dimensions )
			{
				/**
				 * Create the dimensions array to be inserted in Request.
				 */
				$this->dimensions = array();

				/**
				 * Iterate all dimensions and add to array.
				 */
				foreach ($dimensions as $dimension) {
					$new_dimension = new Google_Service_AnalyticsReporting_Dimension();
					$new_dimension->setName('ga:' . $dimension);
					$this->dimensions[] = $new_dimension;
				}
			}

			/**
			 * Method to set and config the Request.
			 *
			 * @return void
			 */
			private function setRequest()
			{
				// Create the ReportRequest object.
				$this->request = new Google_Service_AnalyticsReporting_ReportRequest();
				$this->request->setViewId( $this->config['view_id'] );
				$this->request->setDateRanges( $this->date_range );
				$this->request->setMetrics( $this->metrics );
				$this->request->setDimensions( $this->dimensions );
				$this->request->setOrderBys( $this->sorts );
				$this->request->setPageSize( $this->page_size );
				$this->request->setFiltersExpression( $this->filters );
			}

			/**
			 * Method to set and config report.
			 *
			 * @return void
			 */
			private function setReport()
			{
				/**
				 * Sets and configs the Request.
				 */
				$this->setRequest();

				/**
				 * Create and set Report.
				 */
				$this->report = new Google_Service_AnalyticsReporting_GetReportsRequest();
				$this->report->setReportRequests( array( $this->request ) );

				$report = $this->analytics->reports->batchGet( $this->report );
				return $this->getData( $report );
			}
		// ─────────────────────────────────────────────────────────────────

		//
		// ─── PUBLIC METHODS ──────────────────────────────────────────────
		//
			/**
			 * Method to set the date range for report.
			 *
			 * @param [date] $begin
			 * @param [date] $end
			 * @return void
			 */
			public function setDate( $begin, $end )
			{
				/**
				 * Create date object.
				 */
				$this->date_range = new Google_Service_AnalyticsReporting_DateRange();
				/**
				 * Set the date ranges.
				 */
				$this->date_range->setStartDate( $begin );
				$this->date_range->setEndDate( $end );
			}
		// ─────────────────────────────────────────────────────────────────

	// ────────────────────────────────────────────────────────────────────────────────



	//
	// ─── METHODS TO HANDLE RESULTS ──────────────────────────────────────────────────
	//
		/**
		 * Method to extract Dimensions from Report.
		 *
		 * @param [Report] $report
		 * @return void
		 */
		private function getDimensions( $report )
		{	
			/**
			 * Prevent dimensions error.
			 */
			$dimensions = $report->getColumnHeader()->getDimensions();
			if ( !$dimensions ) return null;

			/**
			 * Only executes if there are dimensions.
			 */
			$ret_dimensions = array();
			foreach ($dimensions as $dimension) {
				$ret_dimensions[] = explode("ga:", $dimension)[1];
			}
			return $ret_dimensions;
		}

		/**
		 * Method to extract Metrics Headers from Report.
		 *
		 * @param [Report] $reports
		 * @return void
		 */
		private function getMetrics( $report )
		{
			$metrics = array();
			foreach ($report->getColumnHeader()->getMetricHeader()->getMetricHeaderEntries() as $metric) {
				$metrics[] = explode('ga:', $metric->getName())[1];
			}
			return $metrics;
		}

		private function getData( $reports )
		{
			$data = array(
				"rows" => array()
			);
			foreach ($reports as $report) {
				/**
				 * Get headers.
				 */
				$dimensionHeaders = $this->getDimensions( $report );
				if ( $dimensionHeaders ) // Dimensions are not required.
					$data['dimensions'] = $dimensionHeaders;

				$metricHeaders = $this->getMetrics( $report ); // Metrics are required.

				/**
				 * Get Rows and iterate.
				 */
				$rows = $report->getData()->getRows();
				foreach ($rows as $row) {

					/**
					 * Handle Metrics.
					 */
					$metrics = $row->getMetrics();
					$ret_metrics = array();
					foreach ($metrics as $metric) {
						$i = 0; // Counter.
						foreach ($metric->getValues() as $value) {
							$ret_metrics[] = array(
								"label" => $metricHeaders[$i],
								"value" => $value
							);
							$i++; // Increase counter.
						}
					}

					/**
					 * Only add the dimensions if they exist.
					 */
					if ( $dimensionHeaders ) {
						$data['rows'][] = array(
							"dimensions" => $row->getDimensions(),
							"metrics" => $ret_metrics
						);
					} else {
						$data['rows'] = $ret_metrics;
					}
				}
				
			}
			return $data;
		}
	// ────────────────────────────────────────────────────────────────────────────────



	//
	// ─── API Generic METHODS ────────────────────────────────────────────────────────────────
	//
		/**
		 * Generic Method to build the Requests.
		 * ----
		 * $req = array(
		 * 	  "metrics" => array(),
		 * 	  "dimensions" => array(),
		 *    "sorts" => array(),
		 *    "page_size" => int,
		 *    "filters" => string, // comma separated conditions
		 * )
		 *
		 * @return [array] $data
		 */
		protected function metricsByDimensions(array $req)
		{
			try {
				/**
				 * Checks necessary fields.
				 */
				$this->validateRequest();

				/**
				 * Add metrics to request.
				 */
				$this->setMetrics( $req['metrics'] );

				/**
				 * Add dimensions to request.
				 */
				if ( $req['dimensions'] )
					$this->setDimensions( $req['dimensions'] );

				/**
				 * Add Sorts/PageSize/Filters to request.
				 */
				if ( $req['sorts'] ) 
					$this->setSorts( $req['sorts'] );

				if ( $req['page_size'] )
					$this->setPageSize( $req['page_size'] );

				if ( $req['filters'] )
					$this->setFilters( $req['filters'] );

				/**
				 * Make the report.
				 * ---
				 * Also formats data.
				 */
				$data = $this->setReport();

				/**
				 * Return $data.
				 */
				return $data;

			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
	// ─────────────────────────────────────────────────────────────────


}

