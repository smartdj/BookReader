package wang.smartdj.bookreader.spider.entity.qidian;

import org.hibernate.annotations.*;

import javax.persistence.*;
import javax.persistence.Entity;
import javax.persistence.Table;
import java.util.Date;

/**
 * Created by Arthur on 2016/12/12.
 */
@Entity
@Table(name = "qidian_books")
//@org.hibernate.annotations.Cache(usage = CacheConcurrencyStrategy.READ_ONLY)
public class QidianBookModel {
    @Id
    private Integer id;
    @Column(name = "lastest_chapter")
    private String lastestChapter;
    private String name;
    private String author;
    @Column(name = "main_category")
    private String mainCategory;
    @Column(name = "sub_category")
    private String subCategory;
    @Column(name = "relationship_category")
    private String relationshipCategory;
    @Column(name = "lastest_chapter_description")
    private String lastestChapterDescription;
    @Column(name = "lastest_update_time")
    private Date lastestUpdateTime;
    @Column(name = "chapter_count")
    private Integer chapterCount;
    @Column(name = "cover_image_url")
    private String coverImageURL;
    private String status;
    @Column(name = "words_count")
    private Integer wordsCount;

    public String getChapterListURL() {
        return chapterListURL;
    }

    public void setChapterListURL(String chapterListURL) {
        this.chapterListURL = chapterListURL;
    }

    private String chapterListURL;

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getLastestChapter() {
        return lastestChapter;
    }

    public void setLastestChapter(String lastestChapter) {
        this.lastestChapter = lastestChapter;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getAuthor() {
        return author;
    }

    public void setAuthor(String author) {
        this.author = author;
    }

    public String getMainCategory() {
        return mainCategory;
    }

    public void setMainCategory(String mainCategory) {
        this.mainCategory = mainCategory;
    }

    public String getSubCategory() {
        return subCategory;
    }

    public void setSubCategory(String subCategory) {
        this.subCategory = subCategory;
    }

    public String getRelationshipCategory() {
        return relationshipCategory;
    }

    public void setRelationshipCategory(String relationshipCategory) {
        this.relationshipCategory = relationshipCategory;
    }

    public String getLastestChapterDescription() {
        return lastestChapterDescription;
    }

    public void setLastestChapterDescription(String lastestChapterDescription) {
        this.lastestChapterDescription = lastestChapterDescription;
    }

    public Date getLastestUpdateTime() {
        return lastestUpdateTime;
    }

    public void setLastestUpdateTime(Date lastestUpdateTime) {
        this.lastestUpdateTime = lastestUpdateTime;
    }

    public Integer getChapterCount() {
        return chapterCount;
    }

    public void setChapterCount(Integer chapterCount) {
        this.chapterCount = chapterCount;
    }

    public String getCoverImageURL() {
        return coverImageURL;
    }

    public void setCoverImageURL(String coverImageURL) {
        this.coverImageURL = coverImageURL;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public Integer getWordsCount() {
        return wordsCount;
    }

    public void setWordsCount(Integer wordsCount) {
        this.wordsCount = wordsCount;
    }
}
