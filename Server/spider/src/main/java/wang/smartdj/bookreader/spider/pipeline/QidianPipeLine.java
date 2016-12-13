package wang.smartdj.bookreader.spider.pipeline;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import us.codecraft.webmagic.ResultItems;
import us.codecraft.webmagic.Task;
import us.codecraft.webmagic.pipeline.Pipeline;
import wang.smartdj.bookreader.spider.dao.QidianDAO;
import wang.smartdj.bookreader.spider.model.QidianBookModel;
import wang.smartdj.bookreader.spider.model.QidianChapterModel;

import javax.annotation.Resource;
import java.util.List;

/**
 * Created by Arthur on 2016/12/13.
 */
@Component("QidianPipeLine")
public class QidianPipeLine implements Pipeline {
    @Autowired
    private QidianDAO qidianDAO;

    @Override
    public void process(ResultItems resultItems, Task task) {
        QidianBookModel bookModel = (QidianBookModel) resultItems.get("bookModel");
        List<QidianChapterModel> chapterModels = (List<QidianChapterModel>) resultItems.get("chapterModels");

        if(bookModel != null){
            qidianDAO.addBook(bookModel);
        }
        else if(chapterModels != null){
            qidianDAO.addChapters(chapterModels);
        }
    }
}
