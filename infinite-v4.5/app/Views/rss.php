<?= '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?= xml_convert(convertToXMLCharacter($feedName ?? '')); ?></title>
<link><?= $feedURL; ?></link>
<description><?= xml_convert(convertToXMLCharacter($pageDescription ?? '')); ?></description>
<dc:language><?= $pageLanguage; ?></dc:language>
<?php if (!empty($baseSettings->copyright)): ?>
<dc:rights><?= xml_convert(convertToXMLCharacter($baseSettings->copyright ?? '')); ?></dc:rights>
<?php endif; ?>

<?php $showPostContent = false;
if ($generalSettings->rss_content_type == 'content') {
$showPostContent = true;
}
if (!empty($posts)):
foreach ($posts as $post):
$postBaseUrl = generateBaseURLByLangId($post->lang_id);
$postUrl = generatePostURL($post, $postBaseUrl);
$post->slug = urlencode($post->slug); ?>
<item>
<title><?= xml_convert(convertToXMLCharacter($post->title ?? '')); ?></title>
<link><?= $postUrl; ?></link>
<guid><?= $postUrl; ?></guid>
<description><![CDATA[ <?= esc($post->summary); ?> ]]></description>
<?php if (!empty($post->image_url)):
$imageUrl = $post->image_url;
if (strpos($imageUrl, '?') !== false) {
$imageUrl = strtok($imageUrl, "?");
} ?>
<enclosure url="<?= $imageUrl; ?>" length="49398" type="image/jpeg"/>
<?php else:
$imagePath = $post->image_big;
$imageUrl = getPostImage($post, 'big');
if (!empty($imagePath) && file_exists(FCPATH . $imagePath)) {
$fileSize = @filesize(FCPATH . $imagePath);
}
if (empty($fileSize) || $fileSize < 1) {
$fileSize = 49398;
}
if (!empty($fileSize)):?>
<enclosure url="<?= $imageUrl; ?>" length="<?= $fileSize; ?>" type="image/jpeg"/>
<?php endif;
endif; ?>
<pubDate><?= date('r', strtotime($post->created_at)); ?></pubDate>
<dc:creator><?= convertToXMLCharacter($post->username); ?></dc:creator>
<media:keywords><?= formatKeywordsForRss($post->keywords); ?></media:keywords>
<?php if ($showPostContent): ?>
<content:encoded><?php if (!empty($post->content)): ?><![CDATA[<?= $post->content; ?>]]> <?php endif; ?></content:encoded>
<?php endif; ?>
</item>

<?php endforeach;
endif; ?>
</channel>
</rss>