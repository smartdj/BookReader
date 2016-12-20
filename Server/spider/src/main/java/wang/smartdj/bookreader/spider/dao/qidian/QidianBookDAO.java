package wang.smartdj.bookreader.spider.dao.qidian;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import wang.smartdj.bookreader.spider.entity.qidian.QidianBookModel;


/**
 * Created by Arthur on 2016/12/13.
 */
@Repository
//@Component("QidianBookDAO")
public interface QidianBookDAO extends JpaRepository<QidianBookModel, Integer> {

}
