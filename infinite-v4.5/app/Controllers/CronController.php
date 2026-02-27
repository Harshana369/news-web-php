<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PostAdminModel;
use App\Models\RssModel;
use App\Models\SitemapModel;

class CronController extends BaseController
{
    /**
     * Update Sitemap
     */
    public function updateSitemap()
    {
        $model = new SitemapModel();
        $postsCount = $model->getPostsCount();
        $numSitemaps = $postsCount > SITEMAP_URL_LIMIT ? ceil($postsCount / SITEMAP_URL_LIMIT) : 1;
        for ($i = 0; $i < $numSitemaps; $i++) {
            $model->generateSitemap($i);
        }
        echo "Sitemap has been successfully updated!";
    }

    /**
     * Check Feed Posts
     */
    public function checkFeedPosts()
    {
        $model = new RssModel();
        $feedsNotUpdated = $model->getFeedsNotUpdated();
        if (empty($feedsNotUpdated)) {
            $model->resetCronFlag();
        }
        $feeds = $model->getFeedsCron();
        if (!empty($feeds)) {
            foreach ($feeds as $feed) {
                if (!empty($feed->feed_url)) {
                    $model->addFeedPosts($feed->id);
                    $model->setFeedAsUpdated($feed->id);
                }
            }
            resetCacheDataOnChange();
        }
        echo "Feeds have been checked.";
    }
}
