package wang.smartdj.bookreader.spider.model.qidian;

import org.hibernate.annotations.Cache;
import org.hibernate.annotations.CacheConcurrencyStrategy;

import javax.persistence.*;

/**
 * Created by Arthur on 2016/12/13.
 * 节
 */
@Entity
@Table(name="qidian_chapters")
@Cache(usage = CacheConcurrencyStrategy.READ_ONLY)
public class QidianChapterModel {
    @Id
    @GeneratedValue(strategy= GenerationType.AUTO)
    private Integer id;
    private String title;

    @ManyToOne(fetch=FetchType.LAZY)
    @JoinColumn(name="book_id")
    private QidianBookModel bookModel;

    @ManyToOne(fetch=FetchType.LAZY)
    @JoinColumn(name="section_id")
    private QidianSectionModel sectionModel;//章

    public QidianBookModel getBookModel() {
        return bookModel;
    }

    public void setBookModel(QidianBookModel bookModel) {
        this.bookModel = bookModel;
    }

    public QidianSectionModel getSectionModel() {
        return sectionModel;
    }

    public void setSectionModel(QidianSectionModel sectionModel) {
        this.sectionModel = sectionModel;
    }

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


}
