package wang.smartdj.bookreader.spider.model;

/**
 * Created by Arthur on 2016/12/13.
 * 节
 */
public class QidianChapterModel {
    private Integer id;
    private String title;
    private Integer bookId;
    private QidianSectionModel section;//章

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public Integer getBookId() {
        return bookId;
    }

    public void setBookId(Integer bookId) {
        this.bookId = bookId;
    }

    public QidianSectionModel getSection() {
        return section;
    }

    public void setSection(QidianSectionModel section) {
        this.section = section;
    }
}
