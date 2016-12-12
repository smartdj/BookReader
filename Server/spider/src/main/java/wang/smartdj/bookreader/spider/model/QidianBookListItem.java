package wang.smartdj.bookreader.spider.model;

import us.codecraft.webmagic.model.annotation.ExtractBy;
import us.codecraft.webmagic.model.annotation.HelpUrl;
import us.codecraft.webmagic.model.annotation.TargetUrl;

import java.util.Date;

/**
 * Created by Arthur on 2016/12/12.
 */
public class QidianBookListItem {
    private Integer id;
    private String name;
    private String author;
    private String mainCategory;
    private String subCategory;
    private String lastestChapter;
    private Date lastestUpdateTime;
    //@ExtractBy(value = "//div[@class='book-list-detail btmline']/div[@class='photo']/img/@src")
    private String coverImageURL;
    //@ExtractBy("//div[@class='book-list-detail btmline']/a/@href")
    private String URL;
}
