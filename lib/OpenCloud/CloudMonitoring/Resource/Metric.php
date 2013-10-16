<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * Metric class.
 */
class Metric extends ReadOnlyResource
{

    protected static $json_name = 'metrics';
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'metrics';
    
    protected $dataPointParams = array(
    	'from',
    	'to',
    	'points',
    	'resolution',
    	'select'
    );

	public function fetchDataPoints($metricName, array $options = array())
	{
		$url = $this->url($metricName . '/plot');

		$parts = array();

		// Timestamps
		foreach (array('to', 'from', 'points') as $param) {
			if (isset($options[$param])) {
				$parts[$param] = $options[$param];
			}
		}

		if (!isset($parts['to'])) {
			throw new Exception\MetricException(sprintf(
				'Please specify a "to" value'
			));
		}

		if (!isset($parts['from'])) {
			throw new Exception\MetricException(sprintf(
				'Please specify a "from" value'
			));
		}

		if (isset($options['resolution'])) {
			$allowedResolutions = array('FULL', 'MIN5', 'MIN20', 'MIN60', 'MIN240', 'MIN1440');
			if (!in_array($options['resolution'], $allowedResolutions)) {
				throw new Exception\MetricException(sprintf(
					'%s is an invalid resolution type. Please use one of the following: %s',
					$options['resolution'],
					implode(', ', $allowedResolutions)
				));
			}
			$parts['resolution'] = $options['resolution'];
		}

		if (isset($options['select'])) {
			$allowedStats = array('average', 'variance', 'min', 'max');
			if (!in_array($options['select'], $allowedStats)) {
				throw new Exception\MetricException(sprintf(
					'%s is an invalid stat type. Please use one of the following: %s',
					$options['select'],
					implode(', ', $allowedStats)
				));
			}
			$parts['select'] = $options['select'];
		}

		if (!isset($parts['points']) && !isset($parts['resolution'])) {
			throw new Exception\MetricException(sprintf(
				'Please specify at least one point or resolution value'
			));
		}

		$url .= "?to={$parts['to']}";
		unset($parts['to']);
		foreach ($parts as $type => $val) {
			$url .= "&$type=$val";
		}

		return $this->getService()->collection(get_class(), $url);
	}
	
}