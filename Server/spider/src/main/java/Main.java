import org.apache.commons.cli.*;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.Spider;
import us.codecraft.webmagic.model.OOSpider;
import us.codecraft.webmagic.pipeline.ConsolePipeline;
import us.codecraft.webmagic.pipeline.MultiPagePipeline;
import wang.smartdj.bookreader.spider.global.Constant;
import wang.smartdj.bookreader.spider.model.QidianBookListItem;
import wang.smartdj.bookreader.spider.pageprocessor.QidianPageProcessor;

/**
 * Created by Arthur on 2016/12/9.
 */

class Main {

    private static Options opts = new Options();
    private static Site site = Site.me();
    static {
        // 配置两个参数
        // -h --help 帮助文档
        // -q --qidian
        opts.addOption("h", "help", false, "The command help");
        opts.addOption("q", "qidian", false, "抓取\"起点中文网\".");
        opts.addOption("c", "chuangshi", false, "抓取\"创世文学\".");
    }

    /**
     * 提供程序的帮助文档
     */
    static void printHelp(Options opts) {
        HelpFormatter hf = new HelpFormatter();
        hf.printHelp("The Cli app. Show how to use Apache common cli.", opts);
    }

    public static void main(String[] args) {
        // 解析参数
        try {
            CommandLineParser parser = new DefaultParser();
            CommandLine cmd = parser.parse(opts, args);

            if (cmd.hasOption("q")) {//起点
                site.setDomain("m.qidian.com");
//                OOSpider.create(site, QidianBookListItem.class)
//                        .addUrl(Constant.QidianStoreURL)
//                        .addPipeline(new MultiPagePipeline())
//                        .addPipeline(new ConsolePipeline())
//                        //开启5个线程抓取
//                        //.thread(5)
//                        .runAsync();
                Spider.create(new QidianPageProcessor())
                        //从"https://github.com/code4craft"开始抓
                        .addUrl(Constant.QidianStoreURL)
                        //开启5个线程抓取
                        .thread(5)
                        //启动爬虫
                        .run();

            } else if(cmd.hasOption("c")){//创世

            }

//            try {
//                Thread.sleep(10000);
//            } catch (InterruptedException e) {
//                e.printStackTrace();
//            }
//            System.out.println("The demo stopped!");
//            System.out.println("To more usage, try to customize your own Spider!");
//            System.exit(0);
        } catch (ParseException ex) {

        } finally {

        }
    }
}
