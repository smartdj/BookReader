import org.apache.commons.cli.*;
import wang.smartdj.bookreader.spider.launcher.QidianLauncher;
import org.springframework.context.ApplicationContext;
import org.springframework.context.annotation.*;
/**
 * Created by Arthur on 2016/12/9.
 */

@Configuration
@ComponentScan(basePackages = "wang.smartdj.bookreader.spider.*")
class Main {
    private static Options opts = new Options();

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
                ApplicationContext context =
                        new AnnotationConfigApplicationContext(Main.class);
                QidianLauncher qidian = context.getBean(QidianLauncher.class);
                qidian.run();

            } else if(cmd.hasOption("c")){//创世

            }

            try {
                Thread.sleep(20000);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            System.out.println("The demo stopped!");
            System.out.println("To more usage, try to customize your own Spider!");
            System.exit(0);
        } catch (ParseException ex) {

        } finally {

        }
    }
}
