package wang.smartdj.bookreader.spider.pageprocessor;

import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import us.codecraft.webmagic.Page;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.processor.PageProcessor;
import us.codecraft.webmagic.selector.Html;
import wang.smartdj.bookreader.spider.dao.qidian.QidianBookDAO;

/**
 * Created by Arthur on 2016/12/20.
 */
public class LeduwoPageProcessor implements PageProcessor {
    // 部分一：抓取网站的相关配置，包括编码、抓取间隔、重试次数等
    private Site site = Site.me()
            .setRetryTimes(3)
            .setSleepTime(1000)
            .setDomain("http://m.qidian.com");
    //.setUserAgent();

    final Logger logger = LoggerFactory.getLogger(this.getClass());

    @Autowired
    private QidianBookDAO qidianBookDAO;

    //搜索结果的解析分为两种，一种是唯一结果（书名没有重复的），
    // 第二种是存在多个搜索结果（例如：搜索"爱"，搜索结果时所有包含"爱"的小说）
    @Override
    public void process(Page page) {
        final String bookNameXPathSelector = "//a[@class='blue']";
        final String authorXPathSelector = "//p[@class='line']/a[3]";

        Html html = page.getHtml();
        Elements results =  html.getDocument().body().getElementsByAttributeValueContaining("class", "line");

        for (Element line:
                results) {
            String bookName = line.getElementsByIndexEquals(0).toString();
            String authorName = line.getElementsByIndexEquals(3).toString();

            logger.debug(bookName, authorName);
        }
    }

    @Override
    public Site getSite() {
        return site;
    }
}
