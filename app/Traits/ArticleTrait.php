<?php
/**
 * @Author: Roy
 * @DateTime: 2022/12/15 上午 10:51
 */

namespace App\Traits;

trait ArticleTrait
{
    private function getShortContent(string $string, int $limit = 20, string $add = "")
    {
        return sprintf('%s%s',
            mb_substr(str_replace(["\r", "\n", "\r\n", "\n\r", PHP_EOL, "&nbsp"], '', $string), 0, $limit), $add);
    }
}
