package wang.smartdj.bookreader.spider.model.qidian;

import org.hibernate.annotations.CacheConcurrencyStrategy;

import javax.persistence.*;

/**
 * Created by Arthur on 2016/12/13.
 * 章
 */
@Entity
@Table(name = "qidian_sections")
@org.hibernate.annotations.Cache(usage = CacheConcurrencyStrategy.READ_ONLY)
public class QidianSectionModel {
    @Id
    @GeneratedValue(strategy= GenerationType.AUTO)
    private Integer id;

    private String title;

    @ManyToOne(fetch=FetchType.LAZY)
    @JoinColumn(name="book_id")
    private QidianBookModel bookModel;

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

    public QidianBookModel getBookModel() {
        return bookModel;
    }

    public void setBookModel(QidianBookModel bookModel) {
        this.bookModel = bookModel;
    }
}
