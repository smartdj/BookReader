package wang.smartdj.bookreader.spider.dao;

import org.springframework.stereotype.Component;
import wang.smartdj.bookreader.spider.model.QidianBookModel;
import wang.smartdj.bookreader.spider.model.QidianChapterModel;

import java.util.List;

/**
 * Created by Arthur on 2016/12/13.
 */
@Component
public class QidianDAO {

    public int addBook(QidianBookModel bookModel) {
        return 0;
    }

    public int addChapters(List<QidianChapterModel> chapterModels){
        return 0;
    }
}
