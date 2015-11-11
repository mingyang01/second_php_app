{/assign var='navbox' value=MenuManager::getMenu("tuan", $speed->id, "false")/}
<header class="navbar-inverse" id="top" role="banner" style="margin-bottom:20px;">
    <div class="navbar-header">
        <a class="navbar-brand" href="/auth/CleanRedirect" style="color:white">运营平台</a>
    </div>

    <nav class="collapse  navbar-collapse" role="navigation">
        <ul class="nav navbar-nav">
        {/foreach from=$navbox item=it key=key/}
            <li class="dropdown" >
                {/if $navbox[$key].name == '收藏夹'/}
                    {/continue/}
                {//if/}
                {/if count($navbox[$key].child) gt 0/}
                    <a href="#" data-toggle="dropdown"  class="dropdown-toggle"><span class="text">{/$navbox[$key].name/}</span><b class="caret"></b></a>
                <ul class="dropdown-menu">
                {/foreach from=$navbox[$key].child item=i key = u name=list/}
                    {/if $navbox neq ""/}
                        {/if count($navbox[$key].child.$u.child) gt 0/}
                        <li class="dropdown-submenu"><a title="" href="{/$navbox[$key].child.$u.url/}"><i class="icon-bar-chart"></i> {/$navbox[$key].child.$u.name/}</a>
                        <ul class="dropdown-menu">
                            {/if count($navbox[$key].child.$u.child) gt 0/}
                                {/foreach from=$navbox[$key].child.$u.child item=n key = m name=list/}
                                    <li><a  href="{/$navbox[$key].child.$u.child.$m.url/}" ><i class="icon-bar-chart"></i> {/$navbox[$key].child.$u.child.$m.name/}</a></li>
                                    {/if $smarty.foreach.list.last/}
                                        {/else/}
                                            <li class="divider"></li>
                                        {//if /}
                                {//foreach/}
                            {//if/}
                        </ul>
                        {/else/}
                            <li><a title="" href="{/$navbox[$key].child.$u.url/}"><i class="icon-bar-chart"></i> {/$navbox[$key].child.$u.name/}</a>
                        {//if/}
                        </li>
                    {//if/}
                    {/if $smarty.foreach.list.last/}
                    {/else/}
                         <li class="divider"></li>
                    {//if /}
                {//foreach/}
                </ul>
                {/else/}
                    <a href="{/$navbox[$key].url/}" ><span class="text">{/$navbox[$key].name/}</span></a>
                {//if/}
            </li>

        {//foreach/}
        </ul>

        <ul class="nav navbar-nav navbar-right">
            {/*<li class=""><a title="" href="#"><i class="icon icon-share-alt"></i> <span class="text">今日：{/date('Y-m-d')/}</span></a></li>*/}
            <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">{/Yii::app()->user->name/}</span><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a target="_blank" href="http://speed.meilishuo.com/user/profile/"><i class="icon-user"></i> 个人信息</a></li>
                    <li class="divider"></li>
                    <li><a target="_blank" href="http://speed.meilishuo.com/time/time_manage"><i class="icon-check"></i> 我的日程</a></li>
                    <li class="divider"></li>
                    <li><a href="/site/logout"><i class="icon-key"></i> 退出登录</a></li>
                </ul>
            </li>
            <li class=""><a title="" href="/site/logout"><i class="icon icon-share-alt"></i> <span class="text">退出登录</span></a></li>
        </ul>
    </nav>
</header>
<style>
@media only screen and (min-width : 768px) {
    /* Make Navigation Toggle on Desktop Hover */
    .dropdown:hover .dropdown-menu {
        display: block;
    }
}
</style>