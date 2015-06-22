## MPX PHP libraries
Basic libraries to help communicate to thePlatforms MPX services.


## Usage

Signing into account

```php
require_once('helper/MpxUser.php');
$mpx_user = new MpxUser;
$user = $mpx_user->signIn('username', 'password');
```


Get all media assosicated with the account including non published

```php
require_once('helper/MpxUser.php');
require_once('helper/MpxMedia.php');
$mpx_user = new MpxUser;
$mpx_media = new MpxMedia;
$user = $mpx_user->signIn('username', 'password');

Mpx::setAccountId('http://access.auth.theplatform.com/data/Account/________');

$search_criteria = array(
	'form' => 'cjson',
	'startIndex' => '1',
	'endIndex' => '5',
	'count' => true
);

$media = $mpx_media->getAccountMedia($search_criteria);
```

Creating new media object

```php
require_once('helper/MpxUser.php');
require_once('helper/MpxMedia.php');
$mpx_user = new MpxUser;
$mpx_media = new MpxMedia;
$user = $mpx_user->signIn('username', 'password');

Mpx::setAccountId('http://access.auth.theplatform.com/data/Account/________');

$metadata = array(
	'title' => 'new media',
	'keywords' => 'test1, test2, test3'
	'pl1$custom' => 'math, english, sports' // custom field, use pl1 namespace
);

$new_media = $mpx_media->createMedia($metadata);
```



## TODO
Upload service: I haven't been able to get the upload service to work, the code is there but most likely needs to be revised