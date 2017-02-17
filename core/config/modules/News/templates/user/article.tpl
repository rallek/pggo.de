{ajaxheader ui=true imageviewer="true"}
{* For ajax modify and image uploading *}
{if $modvars.News.enableajaxedit}
    {if $modvars.News.picupload_enabled}
    {pageaddvar name='javascript' value='modules/News/javascript/multifile.js'}
    {/if}
{/if}

{if $modvars.News.enabledescriptionvar}
{setmetatag name='description' value=$info.hometext|notifyfilters:'news.filter_hooks.articles.filter'|strip_tags|trim|truncate:$modvars.News.descriptionvarchars}
{/if}

{nocache}{include file='user/menu.tpl'}{/nocache}
{insert name='getstatusmsg'}
<div class="white-space">
<div id="news_articlecontent">

    {include file='user/articlecontent.tpl'}
</div>

<div id="news_modify">&nbsp;</div>

{if $modvars.News.enablemorearticlesincat AND !empty($morearticlesincat)}
<div id="news_morearticlesincat">
<h4>{gt text='More articles in category '}
{foreach name='categorynames' from=$preformat.categorynames item='categoryname'}
{$categoryname}{if $smarty.foreach.categorynames.last neq true}&nbsp;&amp;&nbsp;{/if}
{/foreach}</h4>
<ul>
    {foreach from=$morearticlesincat item='morearticle'}
    <li><a href="{modurl modname='News' type='user' func='display' sid=$morearticle.sid}">{$morearticle.title|safehtml}</a> ({gt text='by %1$s on %2$s' tag1=$morearticle.contributor tag2=$morearticle.from|dateformat:'datebrief'})</li>
    {/foreach}
</ul>
</div>
{/if}
</div>
<div id="news-articledisplay-hooks">
{* the next code is to display any hooks (e.g. comments, ratings). All hooks are stored in $hooks and called individually. EZComments is not called when Commenting is not allowed *}
{notifydisplayhooks eventname='news.ui_hooks.articles.display_view' id=$info.sid assign='hooks'}
{foreach from=$hooks key='provider_area' item='hook'}
{if !(($provider_area eq 'provider.ezcomments.ui_hooks.comments') and ($info.allowcomments eq 0))}
{$hook}
{/if}
{/foreach}
</div>
{pageaddvar name='javascript' value='web/magnific-popup/jquery.magnific-popup.min.js'}

{pageaddvar name='stylesheet' value='web/magnific-popup/magnific-popup.css'}
{if $modvars.News.enableajaxedit}
<div id="news-quickedit-hooks">
{notifydisplayhooks eventname='news.ui_hooks.articles.form_edit' id=$info.sid}
</div>
{/if}


<script type="text/javascript">
(function($) {
    $(document).ready(function() { 
        $('.image-link').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
            titleSrc: 'title',
            verticalFit: true
        },
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            tPrev: Translator.__('Previous (Left arrow key)'),
            tNext: Translator.__('Next (Right arrow key)'),
            tCounter: '<span class="mfp-counter">%curr% ' + Translator.__('of') + ' %total%</span>'
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        }
    });
     });
})(jQuery)
</script>
