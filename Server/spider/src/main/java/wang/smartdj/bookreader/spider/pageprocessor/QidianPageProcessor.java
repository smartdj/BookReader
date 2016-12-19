package wang.smartdj.bookreader.spider.pageprocessor;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import us.codecraft.webmagic.Page;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.processor.PageProcessor;
import us.codecraft.webmagic.selector.Html;
import wang.smartdj.bookreader.spider.global.Constant;
import wang.smartdj.bookreader.spider.model.QidianBookModel;
import wang.smartdj.bookreader.spider.model.QidianChapterModel;
import wang.smartdj.bookreader.spider.model.QidianSectionModel;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.*;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Created by Arthur on 2016/12/9.
 */


public class QidianPageProcessor implements PageProcessor {
    final String pageNumberRegex = "\\/bookstore\\.aspx\\?sitetype=-1&categoryid=-1&subcategoryid=-1&action=-1&word=-1&vip=-1&orderid=6&update=-1&pageindex=(\\d+)";
    final Logger logger = LoggerFactory.getLogger(this.getClass());
    // 部分一：抓取网站的相关配置，包括编码、抓取间隔、重试次数等
    private Site site = Site.me()
            .setRetryTimes(3)
            .setSleepTime(1000)
            .setDomain("http://m.qidian.com");
            //.setUserAgent("Mozilla/5.0 (iPhone; CPU iPhone OS 9_2 like Mac OS X) AppleWebKit/601.1 (KHTML, like Gecko) CriOS/47\n" +
            //        ".0.2526.70 Mobile/13C71 Safari/601.1.46");

    // process是定制爬虫逻辑的核心接口，在这里编写抽取逻辑
    @Override
    public void process(Page page) {
        String currentURL = page.getUrl().toString();
        Html html = page.getHtml();

        Constant.PageType pageType = this.getPageTypeFrom(currentURL);
        //如果当前是列表页
        if (pageType == Constant.PageType.BookList) {
            Integer currentPageNumber = this.pageNumberFromURL(currentURL);

            if (currentPageNumber >= 0) {
                //页面中的所有小说详情页的URL
                List<String> bookDetailURLs = html.links().regex(".+\\/book\\/showbook\\.aspx\\?bookid=\\d+").all();
                page.addTargetRequests(bookDetailURLs);

                //查找下一页的url地址来抓取
                String nextPageURL = null;
                String nextPageURLRegex = pageNumberRegex.replace("(\\d+)", String.valueOf(currentPageNumber + 1));
                List<String> nextPageURLs = page.getHtml().links().regex(nextPageURLRegex).all();
                nextPageURL = nextPageURLs.get(0);

                if (nextPageURL != null) {
                    page.addTargetRequests(Arrays.<String>asList(nextPageURL));
                }
            }
        }
        else if (pageType == Constant.PageType.BookDetail) {
            //处理详情页
            QidianBookModel model = new QidianBookModel();
            String bookID = this.bookID(currentURL);

            String imgXPathSelector = "img[@width=\"15\"][@height=\"21\"]/@src";
            String titleXPathSelector = "//dt[@class='name']/a/text()";
            String authorXPathSelector = "//div[@class='book_r_box']/dl/dd[1]/span[1]/a/text()";
            String categoryXPathSelector = "//div[@class='book_r_box']/dl/dd[1]/span[2]/a/text()";
            String statusXPathSelector = "//div[@class='book_r_box']/dl/dd[2]/span[1]/text()";
            String wordsCountXPathSelector = "//div[@class='book_r_box']/dl/dd[2]/span[2]/text()";
            String lastestChapterXPathSelector = "//div[@class='book_about']/p/a/text()";
            String lastestChapterDescriptionXPathSelector = "//div[@class='book_about']/a[1]/text()";
            String lastestUpdateTimeXPathSelector = "//div[@class='book_about']/a[2]/text()";
            //相关分类
            String relationshipCategoryXPathSelector = "//div[@class='block_bd_main']/div[@class='gray_box clearfix']/ul/li/a/text()";
            String chapterCountXPathSelector = "//span[@class='g_num']/text()";

            String chapterListURL = this.getSite().getDomain() + "/book/bookchapterlist.aspx?bookid=" + bookID;
            page.addTargetRequest(chapterListURL);

            model.setId(Integer.parseInt(bookID));
            model.setCoverImageURL(html.xpath(imgXPathSelector).toString());
            model.setName(html.xpath(titleXPathSelector).toString());
            model.setAuthor(html.xpath(authorXPathSelector).toString());
            model.setSubCategory(html.xpath(categoryXPathSelector).toString());
            model.setStatus(this.captureStringWithRegex(html.xpath(statusXPathSelector).toString(), ":(.+)"));
            model.setWordsCount(this.convertWordsCount(html.xpath(wordsCountXPathSelector).toString()));
            model.setLastestChapter(html.xpath(lastestChapterXPathSelector).toString());
            model.setLastestChapterDescription(html.xpath(lastestChapterDescriptionXPathSelector).toString());
            model.setLastestUpdateTime(this.convertDate(html.xpath(lastestUpdateTimeXPathSelector).toString()));
            model.setRelationshipCategory(html.xpath(relationshipCategoryXPathSelector).toString());
            model.setChapterCount(this.convertChaperCount(html.xpath(chapterCountXPathSelector).toString()));
            model.setChapterListURL(chapterListURL);

            page.putField("bookModel", model);
        }else if(pageType == Constant.PageType.ChapterList){
            String bookId = this.bookID(currentURL);
            String chapterTitleXPathSelector = "//p[@class='chap_name_b']/a/text()";
            String chapterURLXPathSelector = "//p[@class='chap_name_b']/a/@href";
            String sectionValueXPathSelector = "//*[@id='volumeList1']/option/@value";
            String sectionTitleXPathSelector = "//*[@id='volumeList1']/option/text()";
            String currentSectionValueXPathSelector = "//*[@id='volumeList1']/option[@selected='selected']/@value";

            List<String> chaptersTitle = html.xpath(chapterTitleXPathSelector).all();
            List<String> chaptersURL = html.xpath(chapterURLXPathSelector).all();

            List<String> sectionValues = html.xpath(sectionValueXPathSelector).all();
            List<String> sectionTitles = html.xpath(sectionTitleXPathSelector).all();
            String currentSectionValue = html.xpath(currentSectionValueXPathSelector).toString();

            //组装目录URL
            Integer currentSection = Integer.parseInt(currentSectionValue);
            //相当于从下一章开始把目录的每一章的URL全部添加进去
            for (int i=currentSection.intValue(); i<sectionValues.size(); i++){
                String chapterListURL = "http://m.qidian.com/book/bookchapterlist.aspx?bookid="+bookId+"&pageindex="+sectionValues.get(i);
                page.addTargetRequest(chapterListURL);
            }

            QidianSectionModel sectionModel = new QidianSectionModel();
            sectionModel.setBookId(Integer.parseInt(bookId));
            sectionModel.setId(Integer.parseInt(currentSectionValue));
            sectionModel.setTitle(sectionTitles.get(Integer.parseInt(currentSectionValue)-1));

            List<QidianChapterModel> chapterModels = new ArrayList<QidianChapterModel>(chaptersTitle.size());

            for (int i=0; i<chaptersTitle.size(); i++){
                QidianChapterModel chapterModel = new QidianChapterModel();
                chapterModel.setBookId(Integer.parseInt(bookId));
                chapterModel.setTitle(chaptersTitle.get(i));
                chapterModel.setId(this.convertChapterId(chaptersURL.get(i)));
                chapterModel.setSection(sectionModel);

                chapterModels.add(chapterModel);
            }

            page.putField("chapterModels", chapterModels);
        }
    }

    @Override
    public Site getSite() {
        return site;
    }

    private Integer pageNumberFromURL(String URL) {
        try {
            Pattern r = Pattern.compile(pageNumberRegex);
            Matcher m = r.matcher(URL);

            if (m.find()) {
                logger.debug("Found value: " + m.group(1));
                return Integer.parseInt(m.group(1));
            }
        } catch (Exception ex) {
            logger.error(ex.getMessage());
        }
        return -1;
    }

    private String bookID(String URL){
        try{
            Pattern r = Pattern.compile("bookid=(\\d+)");
            Matcher m = r.matcher(URL);

            if (m.find()) {
                logger.debug("Found value: " + m.group(1));
                return m.group(1);
            }
        }
        catch (Exception ex){

        }
        return null;
    }

    private Integer convertWordsCount(String wordsCount){
        wordsCount = this.captureStringWithRegex(wordsCount, "(\\d+)");
        if(wordsCount != null){
            return Integer.parseInt(wordsCount);
        }
        return 0;
    }

    private Date convertDate(String dateString){
        DateFormat format1 = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss", Locale.SIMPLIFIED_CHINESE);
        DateFormat format2 = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.SIMPLIFIED_CHINESE);
        Date date = null;
        try {
            date = format1.parse(dateString);
            if(date == null){
                date = format2.parse(dateString);
            }
            return date;
        } catch (ParseException e1) {
            try {
                date = format2.parse(dateString);
            }
            catch (ParseException e2) {
                e2.printStackTrace();
            }
        }
        return date;
    }

    private Integer convertChaperCount(String chaperCount){
        chaperCount = this.captureStringWithRegex(chaperCount, "(\\d+)");
        if(chaperCount != null){
            return Integer.parseInt(chaperCount);
        }
        return 0;
    }

    private Integer convertChapterId(String chapterURL){
        String chapterId = this.captureStringWithRegex(chapterURL, "chapterid=(\\d+)");
        if(chapterId != null){
            return Integer.parseInt(chapterId);
        }
        return -1;
    }

    private Constant.PageType getPageTypeFrom(String URL){
        if(this.isMatch(URL, ".+\\/bookstore\\.aspx")){
            return Constant.PageType.BookList;
        }else if(this.isMatch(URL, ".+\\/book\\/showbook\\.aspx")){
            return Constant.PageType.BookDetail;
        }else if(this.isMatch(URL, "\\/book\\/bookchapterlist\\.aspx")){
            return Constant.PageType.ChapterList;
        }
        return Constant.PageType.Unknow;
    }

    private String captureStringWithRegex(String str, String regex ){
        return this.captureStringWithRegex(str, regex, 1);
    }

    private String captureStringWithRegex(String str, String regex, Integer captureGroupIndex ){
        try{
            Pattern r = Pattern.compile(regex);
            Matcher m = r.matcher(str);

            if (m.find()) {
                return m.group(captureGroupIndex);
            }
        }
        catch (Exception ex){

        }
        return null;
    }

    private boolean isMatch(String str, String regex){
        try {
            Pattern r = Pattern.compile(regex);
            Matcher m = r.matcher(str);

            if (m.find()) {
                return true;
            }
        } catch (Exception ex) {

        }
        return false;
    }
}
