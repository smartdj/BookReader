package wang.smartdj.bookreader.spider.launcher;

import org.springframework.stereotype.Component;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.Spider;
import wang.smartdj.bookreader.spider.global.Constant;
import wang.smartdj.bookreader.spider.pageprocessor.QidianPageProcessor;
import wang.smartdj.bookreader.spider.pipeline.QidianPipeLine;

/**
 * Created by Arthur on 2016/12/13.
 */

@Component
public class QidianLauncher {

    private static Site site = Site.me().setUserAgent("Mozilla/5.0 (iPhone; CPU iPhone OS 9_2 like Mac OS X) AppleWebKit/601.1 (KHTML, like Gecko) CriOS/47\n" +
            ".0.2526.70 Mobile/13C71 Safari/601.1.46");

    public void run() {
        site.setDomain("m.qidian.com");

//        OOSpider.create(site, qidianDaoPipeline, QidianBookModel.class)
//                .addUrl(Constant.QidianStoreURL)
//                .thread(5)
//                .run();
        Spider.create(new QidianPageProcessor())
                .addPipeline(new QidianPipeLine())
                .addUrl(Constant.QidianStoreURL)
                //开启5个线程抓取
                .thread(5)
                //启动爬虫
                .run();
    }
}
