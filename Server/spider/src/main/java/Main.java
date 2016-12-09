import org.apache.commons.cli.*;
import us.codecraft.webmagic.Spider;
import wang.smartdj.bookreader.spider.pageprocessor.QidianPageProcessor;

/**
 * Created by Arthur on 2016/12/9.
 */

public class Main {

    static Options opts = new Options();

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
                Spider.create(new QidianPageProcessor()).addUrl("https://github.com/code4craft").thread(5).run();
            } else if(cmd.hasOption("c")){//创世

            }
        } catch (ParseException ex) {

        } finally {

        }
    }
}
