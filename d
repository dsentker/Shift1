[1mdiff --git a/Application/Logs/log.txt b/Application/Logs/log.txt[m
[1mindex 66088f8..b7bdce2 100644[m
[1m--- a/Application/Logs/log.txt[m
[1m+++ b/Application/Logs/log.txt[m
[36m@@ -170,3 +170,7 @@[m
 [m
 [m
 [m
[41m+[m
[41m+[m
[41m+[m
[41m+[m
[1mdiff --git a/Libs/Shift1/Core/Config/File/AbstractConfigFile.php b/Libs/Shift1/Core/Config/File/AbstractConfigFile.php[m
[1mindex 813e2ad..8cb1a77 100644[m
[1m--- a/Libs/Shift1/Core/Config/File/AbstractConfigFile.php[m
[1m+++ b/Libs/Shift1/Core/Config/File/AbstractConfigFile.php[m
[36m@@ -3,7 +3,7 @@[m [mnamespace Shift1\Core\Config\File;[m
 [m
 use Shift1\Core\Exceptions\FileNotFoundException;[m
 [m
[31m-abstract class AbstractConfigFile extends \SplFileObject implements ConfigFileInterface {[m
[32m+[m[32mabstract class AbstractConfigFile implements ConfigFileInterface {[m
 [m
     /**[m
      * @var string[m
warning: LF will be replaced by CRLF in Libs/Shift1/Core/Config/File/AbstractConfigFile.php.
The file will have its original line endings in your working directory.
