<?php

/**
 * Provides robots.txt functionality
 * 
 * @author Damian Mooyman
 */
class Robots extends Controller {

	/**
	 * Determines if this is a public site
	 * 
	 * @return boolean flag indicating if this robots is for a public site
	 */
	protected function isPublic() {
		return Director::isLive();
	}

	/**
	 * Generates the response containing the robots.txt content
	 * 
	 * @return SS_HTTPResponse
	 */
	public function index() {
		$text = "";
		$text .= $this->renderSitemap();
		$text .= "User-agent: *\n";
		$text .= $this->renderDisallow();
		$text .= $this->renderAllow();

		$response = new SS_HTTPResponse($text, 200);
		$response->addHeader("Content-Type", "text/plain; charset=\"utf-8\"");
		return $response;
	}

	/**
	 * Renders the sitemap link reference
	 * 
	 * @return string
	 */
	protected function renderSitemap() {

		// No sitemap if not public
		if (!$this->isPublic()) return '';

		// Check if sitemap is configured
		$sitemap = Robots::config()->sitemap;
		if (empty($sitemap)) return '';

		// Skip sitemap if not available
		if (!class_exists('GoogleSitemap') && !Director::fileExists($sitemap)) {
			return '';
		}

		// Report the sitemap location
		return sprintf("Sitemap: %s\n", Director::absoluteURL($sitemap));
	}

	/**
	 * Renders the list of disallowed pages
	 * 
	 * @return string
	 */
	protected function renderDisallow() {
		// List only disallowed urls
		$text = '';
		foreach ($this->disallowedUrls() as $url) {
			$text .= sprintf("Disallow: %s\n", $url);
		}
		return $text;
	}

	/**
	 * Renders the list of allowed pages, if any
	 * 
	 * @return string
	 */
	protected function renderAllow() {
		$text = '';
		foreach ($this->allowedUrls() as $url) {
			$text .= sprintf("Allow: %s\n", $url);
		}
		return $text;
	}

	/**
	 * Returns an array of disallowed URLs
	 * 
	 * @return array
	 */
	protected function disallowedUrls() {

		// If not public, disallow all
		if (!$this->isPublic()) return array("/");

		// Get configured disallowed urls
		$urls = (array) Robots::config()->disallowed_urls;

		// Add all pages where ShowInSearch is false
		if (Robots::config()->disallow_unsearchable) {
			$unsearchablePages = SiteTree::get()->where('"SiteTree"."ShowInSearch" = 0');
			foreach ($unsearchablePages as $page) {
				$link = $page->Link();
				// Don't disallow home page
				if ($link !== '/') $urls[] = $link;
			}
		}

		return array_unique($urls);
	}

	/**
	 * Returns an array of allowed URLs
	 * 
	 * @return array
	 */
	protected function allowedUrls() {
		return (array) Robots::config()->allowed_urls;
	}

}
