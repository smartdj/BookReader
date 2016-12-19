package wang.smartdj.bookreader.spider.dao;

import org.springframework.data.jpa.repository.JpaRepository;
import wang.smartdj.bookreader.spider.model.qidian.QidianBookModel;


/**
 * Created by Arthur on 2016/12/13.
 */


public interface QidianBookDAO extends JpaRepository<QidianBookModel, Integer> {

}
