package wang.smartdj.bookreader.spider.model;

import us.codecraft.webmagic.model.annotation.ExtractBy;
import us.codecraft.webmagic.model.annotation.HelpUrl;
import us.codecraft.webmagic.model.annotation.TargetUrl;

import java.util.Date;

/**
 * Created by Arthur on 2016/12/12.
 */
public class QidianBookModel {
    private Integer id;
    private String lastestChapter;
    private String name;
    private String author;
    private String mainCategory;
    private String subCategory;
    private String relationshipCategory;
    private String lastestChapterDescription;
    private Date lastestUpdateTime;
    private Integer chapterCount;
    private String coverImageURL;
    private String status;
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
