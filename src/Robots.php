<?php

namespace TractorCow\Robots;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\RequestHandler;
use Wilr\GoogleSitemaps\GoogleSitemap;

/**
 * Provides robots.txt functionality
 *
 * @author Damian Mooyman
 */
class Robots extends RequestHandler
{
    /**
     * Path to sitemap.xml to look for
     *
     * @var string
     */
    private static $sitemap = 'sitemap.xml';

    /**
     * Hide unsearchable pages
     *
     * @var bool
     */
    private static $disallow_unsearchable = true;

    /**
     * List of disallowed urls
     *
     * @var array
     */
    private static $disallowed_urls = [
        '/admin',
        '/dev',
    ];

    /**
     * List of allowed urls
     *
     * @config
     * @var array
     */
    private static $allowed_urls = [];

    /**
     * Determines if this is a public site
     *
     * @return boolean flag indicating if this robots is for a public site
     */
    protected function isPublic()
    {
        return Director::isLive();
    }

    /**
     * Generates the response containing the robots.txt content
     *
     * @return HTTPResponse
     */
    public function index()
    {
        $text = "";
        $text .= $this->renderSitemap();
        $text .= "User-agent: *\n";
        $text .= $this->renderDisallow();
        $text .= $this->renderAllow();

        $response = new HTTPResponse($text, 200);
        $response->addHeader("Content-Type", "text/plain; charset=\"utf-8\"");
        return $response;
    }

    /**
     * Renders the sitemap link reference
     *
     * @return string
     */
    protected function renderSitemap()
    {
        // No sitemap if not public
        if (!$this->isPublic()) {
            return '';
        }

        // Check if sitemap is configured
        $sitemap = static::config()->get('sitemap');
        if (empty($sitemap)) {
            return '';
        }

        // Skip sitemap if not available
        if (!class_exists(GoogleSitemap::class) && !Director::fileExists($sitemap)) {
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
    protected function renderDisallow()
    {
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
    protected function renderAllow()
    {
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
    protected function disallowedUrls()
    {
        // If not public, disallow all
        if (!$this->isPublic()) {
            return ["/"];
        }

        // Get configured disallowed urls
        $urls = (array)static::config()->get('disallowed_urls');

        // Add all pages where ShowInSearch is false
        if (static::config()->get('disallow_unsearchable')) {
            /** @var SiteTree[] $unsearchablePages */
            $unsearchablePages = SiteTree::get()->filter(['ShowInSearch' => false]);

            if (class_exists('SilverStripe\CMS\Model\RedirectorPage')) {
                $unsearchablePages = $unsearchablePages->exclude('ClassName', 'SilverStripe\CMS\Model\RedirectorPage');
            }

            foreach ($unsearchablePages as $page) {
                $link = $page->Link();

                // Don't disallow home page
                if ($link !== '/') {
                    $urls[] = $link;
                }
            }
        }

        return array_unique($urls);
    }

    /**
     * Returns an array of allowed URLs
     *
     * @return array
     */
    protected function allowedUrls()
    {
        return (array)static::config()->get('allowed_urls');
    }
}
