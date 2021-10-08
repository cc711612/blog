<?php
namespace App\Http\Composers\Mains;

use App\Sessions\StoreMains\MemberInfoSession;
use Illuminate\Contracts\View\View;

/**
 * Class MainComposer
 *
 * @package App\Http\Composers\Mains
 * @Author: Roy
 * @DateTime: 2021/10/7 上午 10:20
 */
class MainComposer
{
    /**
     * MainComposer constructor.
     *
     * @Author: Roy
     * @DateTime: 2021/10/7 上午 10:20
     */
    public function __construct(
    )
    {
    }

    /**
     * @param  \Illuminate\Contracts\View\View  $View
     *
     * @Author: Roy
     * @DateTime: 2021/10/7 上午 10:20
     */
    public function compose(View $View)
    {
        $is_login = false;
        
        $View->with('is_login', $is_login);
    }
}
