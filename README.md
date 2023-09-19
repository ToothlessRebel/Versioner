# Versioner
Handle version numbers and versioning for various platforms.

## Installation

You can install the package via composer:

```sh
composer require exposuresoftware/versioner
```

## Usage

Simply provide the current version and use the helper methods to increment segments.

```php
VersionString::incrementPatch('v1.0.0'); // 'v1.0.1'
VersionString::incrementMinor('v1.0.0'); // 'v1.1.0'
VersionString::incrementMajor('v0.1.30-alpha'); // 'v2.0.0'
```

Note that any version bump will remove the pre-release label and/or build data if any were included.  
If you wish to keep the pre-release label you can do so:

```php
(string)(new VersionString('v2.0.0-alpha'))->preserveSuffix()->increment(VersionSegment::MINOR); // 'v2.1.0-alpha'
```

## Credits

 * [Marshall Davis](https://github.com/toothlessrebel)
 * [Everyone Who Has Contributed](../../contributors)

## License

Published under the MIT license (MIT). Complete details available in the [license file](LICENSE). 
