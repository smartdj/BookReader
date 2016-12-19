package wang.smartdj.bookreader.spider.dao;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Component;
import wang.smartdj.bookreader.spider.model.qidian.QidianChapterModel;

/**
 * Created by Arthur on 2016/12/16.
 */
@Component("QidianChapterDAO")
public interface QidianChapterDAO extends JpaRepository<QidianChapterModel, Integer> {

}
