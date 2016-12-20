package wang.smartdj.bookreader.spider.launcher.booksource;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import us.codecraft.webmagic.Request;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.Spider;
import us.codecraft.webmagic.scheduler.QueueScheduler;
import us.codecraft.webmagic.utils.HttpConstant;
import wang.smartdj.bookreader.spider.duplicateremover.PostDuplicateRemover;
import wang.smartdj.bookreader.spider.global.Constant;
import wang.smartdj.bookreader.spider.pageprocessor.LeduwoPageProcessor;
import wang.smartdj.bookreader.spider.pipeline.LeduwoPipeLine;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
/**
 * Created by Arthur on 2016/12/20.
 */
@Component
public class LeduwoLauncher {
    private static Site site = Site.me().setUserAgent("Mozilla/5.0 (iPhone; CPU iPhone OS 9_2 like Mac OS X) AppleWebKit/601.1 (KHTML, like Gecko) CriOS/47\n" +
            ".0.2526.70 Mobile/13C71 Safari/601.1.46");
    @Autowired
    private LeduwoPipeLine pipeLine;

    public void search(String bookName, String authorName)  {
        site.setDomain("m.leduwo.com");

        try{
            Spider.create(new LeduwoPageProcessor())
                    .addRequest(request(bookName))
                    .setScheduler(new QueueScheduler().setDuplicateRemover(new PostDuplicateRemover()))
                    .addPipeline(pipeLine)//因为使用了JPA，因此必须使用Autowired，不能使用new QidianPipeLine()，否则DAO会初始化失败
                    //开启5个线程抓取
                    .thread(1)
                    //启动爬虫
                    .run();
        }
        catch (UnsupportedEncodingException ex){

        }
    }

    //因为搜索是通过POST方法，因此，需要自定义Request
    private Request request(String bookName)throws UnsupportedEncodingException{
        Request request = new Request(Constant.LeduwoSearchURL);

        NameValuePair[] nameValuePairs = {
                new BasicNameValuePair("searchtype", "articlename"),
                //new BasicNameValuePair("searchkey", URLEncoder.encode(bookName, "gb2312")),
                new BasicNameValuePair("searchkey", bookName),
                new BasicNameValuePair("submit", ""),
        };
//        new String(bookName.getBytes(), "gb2312")
        request.setMethod(HttpConstant.Method.POST);
        request.putExtra("nameValuePair", nameValuePairs);

        return request;
    }
}
