package wang.smartdj.bookreader.spider.global;

/**
 * Created by Arthur on 2016/12/12.
 */
public class Constant {
    public static final String QidianStoreURL = "http://m.qidian.com/bookstore.aspx?sitetype=-1&categoryid=-1&subcategoryid=-1&action=-1&word=-1&vip=-1&orderid=6&update=-1&pageindex=1";

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
