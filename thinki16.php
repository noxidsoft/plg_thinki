<?php
######################################################################
# Thinki Google Analytics - Adapted from BigDaddy Analytics	         #
# Copyright (C) 2010 by Noxidsoft  	   	   	   	   	   	   	   	   	 #
# Homepage   : www.noxidsoft.com		   	   	   	   	   	   		 #
# Author     : Noel Dixon    		   	   	   	   	   	   	   	     #
# Email      : noel.dixon@noxidsoft.com 	   	   	   	   	   	   	 #
# Version    : 1.6                        	   	    	   	   	   	 #
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL          #
######################################################################

// no direct access
defined( '_JEXEC' ) or die;

jimport( 'joomla.plugin.plugin');

class plgSystemThinki extends JPlugin {
	function plgSystemThinki(&$subject, $config) {
		parent::__construct($subject, $config);
		
    $this->_plugin = JPluginHelper::getPlugin( 'system', 'thinki' );
	}
	
	function onAfterRender() {
		global $mainframe;
		
		$web_property_id = $this->params->get('web_property_id', '');
		
		if($web_property_id == '' || strpos($_SERVER["PHP_SELF"], "index.php") === false)
		{
			return;
		}

		$buffer = JResponse::getBody();

		$pos = strrpos($buffer, "</head>");

		$google_analytics_javascript = '
			<script type="text/javascript">
			  var _gaq = _gaq || [];
			  _gaq.push(["_setAccount", "'.$web_property_id.'"]);
			  _gaq.push(["_trackPageview"]);
			  (function() {
				var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
				ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>
		';

		if($pos > 0)
		{
			$buffer = substr($buffer, 0, $pos).$google_analytics_javascript.substr($buffer, $pos);

			JResponse::setBody($buffer);
		}
		
		return true;
	}
}