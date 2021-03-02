<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => 'e0f10a77c326f00e899371eb7b3045bd3deba3f2',
    'name' => 'pocketmine/pocketmine-mp',
  ),
  'versions' => 
  array (
    'adhocore/json-comment' => 
    array (
      'pretty_version' => '0.1.0',
      'version' => '0.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8448076039389f558f39ad0553aab87db3f81614',
    ),
    'daverandom/callback-validator' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'pocketmine/binaryutils' => 
    array (
      'pretty_version' => '0.1.13',
      'version' => '0.1.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '0abee38d4e2861621f262c79a2a3d699d8a697f4',
    ),
    'pocketmine/callback-validator' => 
    array (
      'pretty_version' => '1.0.3',
      'version' => '1.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '64787469766bcaa7e5885242e85c23c25e8c55a2',
    ),
    'pocketmine/classloader' => 
    array (
      'pretty_version' => '0.1.2',
      'version' => '0.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '9757928424652393b178a3760073113aa7c9911b',
    ),
    'pocketmine/log' => 
    array (
      'pretty_version' => '0.2.1',
      'version' => '0.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '830b44a2cf96ef703c550abe64302f230231ca49',
    ),
    'pocketmine/log-pthreads' => 
    array (
      'pretty_version' => '0.1.3',
      'version' => '0.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e477ecf6ec214fdd4415ea1da3fdd9d73bf699ea',
    ),
    'pocketmine/math' => 
    array (
      'pretty_version' => '0.2.6',
      'version' => '0.2.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '43057cb8c179a9859677b496a788db922fd5cfc3',
    ),
    'pocketmine/nbt' => 
    array (
      'pretty_version' => '0.2.16',
      'version' => '0.2.16.0',
      'aliases' => 
      array (
      ),
      'reference' => 'be6d54a8a314967d938b501eee49d4641e2ab07d',
    ),
    'pocketmine/pocketmine-mp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => 'e0f10a77c326f00e899371eb7b3045bd3deba3f2',
    ),
    'pocketmine/raklib' => 
    array (
      'pretty_version' => '0.12.11',
      'version' => '0.12.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '9cce458b8bfde3e4dfdbf70c659fc7b7fe26b5c4',
    ),
    'pocketmine/snooze' => 
    array (
      'pretty_version' => '0.1.5',
      'version' => '0.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '70b5e7937a06878dd321a3182ceb76d56298f2cd',
    ),
    'pocketmine/spl' => 
    array (
      'pretty_version' => '0.4.2',
      'version' => '0.4.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '6b08b7cf8c4afa17139c9a1b3bf1b408531de161',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
