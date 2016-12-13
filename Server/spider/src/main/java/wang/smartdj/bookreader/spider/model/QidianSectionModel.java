package wang.smartdj.bookreader.spider.model;

/**
 * Created by Arthur on 2016/12/13.
 * 章
 */
public class QidianSectionModel {
    private Integer id;
    private String title;
    private Integer bookId;

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
}
