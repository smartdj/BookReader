package wang.smartdj.bookreader.spider.dao;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Repository;
import wang.smartdj.bookreader.spider.entity.qidian.QidianSectionModel;

/**
 * Created by Arthur on 2016/12/16.
 */

@Repository
//@Component("QidianSectionDAO")
public interface QidianSectionDAO  extends JpaRepository<QidianSectionModel, Integer> {

}
