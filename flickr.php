<?php
$type = 'recent';
if(isset($_GET['type'])) {
	$type = $_GET['type'];
}

// Key: 3eadeac9abe8ac84d2d27ff9a741b41e
// Secret: 460d3c6f99dc9368

$userId = '79903062@N00';

if($type == 'recent') {
	$url = 'http://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=3eadeac9abe8ac84d2d27ff9a741b41e&user_id=' . $userId . '&per_page=8&extras=url_sq';
	$flickrData = simplexml_load_file($url);
	$photos = $flickrData->photos[0];
	$photosCollection = array();
	foreach ($photos->photo as $photo)
	{
		array_push($photosCollection, (object)array(
			'url' => 'http://www.flickr.com/photos/' . $userId . '/' . (string)$photo['id'],
			'image' => (string)$photo['url_sq']
		));
	}

	$json = json_encode($photosCollection);
	echo $json;
}
?>