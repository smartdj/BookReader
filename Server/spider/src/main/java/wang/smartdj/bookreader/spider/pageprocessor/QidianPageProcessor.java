package wang.smartdj.bookreader.spider.pageprocessor;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import us.codecraft.webmagic.Page;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.processor.PageProcessor;
import us.codecraft.webmagic.selector.Html;
import wang.smartdj.bookreader.spider.global.Constant;
import wang.smartdj.bookreader.spider.global.Global;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.regex.Pattern;

/**
 * Created by Arthur on 2016/12/9.
 */



public class QidianPageProcessor implements PageProcessor {

    final Logger logger = LoggerFactory.getLogger(this.getClass());
    // 部分一：抓取网站的相关配置，包括编码、抓取间隔、重试次数等
    private Site site = Site.me().setRetryTimes(3).setSleepTime(1000);

    // process是定制爬虫逻辑的核心接口，在这里编写抽取逻辑
    @Override
    public void process(Page page) {
        String currentURL = page.getUrl().toString();
        // 部分二：定义如何抽取页面信息，并保存下来
        Html html = page.getHtml();

        //页面中的所有URL
        List<String> bookDetailURLs = html.links().regex(".+\\/book\\/showbook\\.aspx\\?bookid=\\d+").all();

        // 部分三：查找下一页的url地址来抓取
        String nextPageURL = null;

        List<String> preAndNextPageURLs = page.getHtml().links().regex("(\\/bookstore\\.aspx\\?sitetype=-1&categoryid=-1&subcategoryid=-1&action=-1&word=-1&vip=-1&orderid=6&update=-1&pageindex=\\d+)").all();
        for (int i=0; i < preAndNextPageURLs.size(); i++) {
            String URL = preAndNextPageURLs.get(i);
            if(!URL.equals(currentURL)){
                nextPageURL = URL;
                break;
            }
        }
        if(nextPageURL != null){
            page.addTargetRequests(Arrays.<String>asList(nextPageURL));
        }

    }

    @Override
    public Site getSite() {
        return site;
    }

//    private Array<> getBookItems(Html html){
//
//    }
}
