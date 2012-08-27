<?php

/**
 * Provides robots.txt functionality
 */
class Robots extends Controller
{
    /**
     * Enables or disableds this module
     * @var boolean
     */
    public static $enabled = true;
    
    public static $disallowed_urls = array('/admin');
	
	public static function enable() {
		self::$enabled = true;
	}
	
	public static function disable() {
		self::$enabled = false;
	}
    
    /**
     * Determines if this is a public site
     * @return boolean flag indicating if this robots is for a public site
     */
    protected function isPublic()
    {
        return Director::isLive();
    }

    public function index($url)
    {
        $text = "";
        // Write sitemap
        $text .= $this->renderSitemap();
        $text .= "User-agent: *\n";
        $text .= $this->renderDisallow();
        $text .= $this->renderAllow();
        
        $response = new SS_HTTPResponse($text, 200);
        $response->addHeader("Content-Type", "text/plain; charset=\"utf-8\"");
        return $response;
    }
    
    protected function renderSitemap()
    {
        if(!$this->isPublic())
            return '';
        
        if(!class_exists('GoogleSitemap') && !Director::fileExists('/sitemap.xml'))
            return '';
        
        return sprintf("Sitemap: %s\n", Director::absoluteURL('/sitemap.xml'));
    }
    
    protected function renderDisallow()
    {
        // If not public, disallow all
        if(!$this->isPublic())
            return "Disallow: /\n";
        
        // List only disallowed urls
        $text = '';
        foreach(self::$disallowed_urls as $url)
            $text .= sprintf("Disallow: %s\n", $url);
        return $text;
    }
    
    
    
    protected function renderAllow()
    {
        return ''; // Override to allow specific urls
    }

}