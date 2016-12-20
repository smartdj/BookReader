package wang.smartdj.bookreader.spider.global;

/**
 * Created by Arthur on 2016/12/12.
 */
public class Constant {
    public static final String QidianStoreURL = "http://m.qidian.com/bookstore.aspx?sitetype=-1&categoryid=-1&subcategoryid=-1&action=-1&word=-1&vip=-1&orderid=6&update=-1&pageindex=1";
    public static final String LeduwoSearchURL = "http://m.leduwo.com/modules/article/waps.php";

    public static final String FirefoxUserAgent = "Mozilla/5.0 (iPhone; CPU iPhone OS 9_2 like Mac OS X) AppleWebKit/601.1 (KHTML, like Gecko) CriOS/47\n" +
            ".0.2526.70 Mobile/13C71 Safari/601.1.46";

    public enum PageType{

        Unknow(0),
        BookList(1),//小说列表
        BookDetail(2),//小说详情
        ChapterList(3);//小说目录

        private int value;

        PageType(int value) {
            this.value = value;
        }

        public int getValue() {
            return value;
        }
    }
}
