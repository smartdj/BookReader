package wang.smartdj.bookreader.spider.pipeline;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import us.codecraft.webmagic.ResultItems;
import us.codecraft.webmagic.Task;
import us.codecraft.webmagic.pipeline.Pipeline;
import wang.smartdj.bookreader.spider.dao.QidianBookDAO;
import wang.smartdj.bookreader.spider.dao.QidianChapterDAO;
import wang.smartdj.bookreader.spider.dao.QidianSectionDAO;
import wang.smartdj.bookreader.spider.model.qidian.QidianBookModel;
import wang.smartdj.bookreader.spider.model.qidian.QidianChapterModel;
import wang.smartdj.bookreader.spider.model.qidian.QidianSectionModel;

import javax.annotation.Resource;
import java.util.List;

/**
 * Created by Arthur on 2016/12/13.
 */
@Component("QidianPipeLine")
public class QidianPipeLine implements Pipeline {
    @Resource
    private QidianBookDAO qidianBookDAO;

    @Resource
    private QidianSectionDAO qidianSectionDAO;

    @Resource
    private QidianChapterDAO qidianChapterDAO;

    @Override
    public void process(ResultItems resultItems, Task task) {
        QidianBookModel bookModel = (QidianBookModel) resultItems.get("bookModel");
        List<QidianChapterModel> chapterModels = (List<QidianChapterModel>) resultItems.get("chapterModels");
        List<QidianSectionModel> sectionModel = (List<QidianSectionModel>)resultItems.get("sectionModel");

        if(bookModel != null){
            qidianBookDAO.save(bookModel);
        }

        if(sectionModel != null){
            qidianSectionDAO.save(sectionModel);
        }

        if(chapterModels != null){
            qidianChapterDAO.save(chapterModels);
        }
    }
}
