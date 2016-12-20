package wang.smartdj.bookreader.spider.launcher;

import wang.smartdj.bookreader.spider.launcher.booksource.LeduwoLauncher;

/**
 * Created by Arthur on 2016/12/20.
 * 盗版小说站的统一入口
 */


public class BookSourceLauncher {
    public static void search(String bookName, String author){
        //乐读窝
        LeduwoLauncher leduwoLauncher = new LeduwoLauncher();
        leduwoLauncher.search(bookName, author);
    }
}
