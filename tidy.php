<?php
/**
 * Created by PhpStorm.
 * User: Aaron
 * Date: 2018/4/27
 * Time: 16:42
 */

if ('cli' !== PHP_SAPI) {
    exit('请在cli模式下运行脚本');
}
//$args = getArgv();
$origin = 'E:/github/fulibuduo/-PHP-/img/xgmn/';
$target = 'E:/github/fulibuduo/-PHP-/img/ceshi/';
//print_r($argv);die;
sortFilesByExt($origin, $target);

/**
 * 获取argv参数
 */
function getArgv()
{
    array_shift($argv);
    if (empty($argv)) {
        return 0;
    }


    return $argv;
}

/**
 * 将多个目录中的文件放到一个目录中，并放到对应扩展名的文件夹中
 *
 * @param string $originPath 原路径
 * @param string $targetPath 目标路径
 */
function sortFilesByExt($originPath, $targetPath = '')
{
    if (is_dir($originPath)) {
        $scans = scandir($originPath);
//        print_r($scans);die;
        foreach ($scans  as $scan) {
            if ($scan == '.' || $scan == '..') {
                continue;
            }
            $path = $originPath . '/' . $scan;
            if (is_dir($path)) {
                sortFilesByExt($path, $targetPath);//如果是目录，递归
            } else {
                $targetPath = $targetPath ?: $originPath;//目标路径如果未设置，则使用原路径
                if (!is_dir($targetPath)) {
                    mkdir($targetPath);
                }
                $ext = pathinfo($path, PATHINFO_EXTENSION);//获取文件扩展名
                $fileName = date('YmdHis') . uniqid() . '.' . $ext;//拼接文件名称
                $targetDirName = $targetPath . '/' . $ext;
                if (!is_dir($targetDirName)) {
                    mkdir($targetDirName);
                }
                $result = copy($path, $targetDirName . '/' . $fileName);
                if ($result) {
                    unlink($path);
                    file_put_contents('data.json', $targetDirName . '/' . $fileName . PHP_EOL, FILE_APPEND);
                    echo $targetDirName . '/' . $fileName . "\n";
                }
            }
        }
//        rmdir($originPath);
    } else {
        exit('未找到对应的目录');
    }
}